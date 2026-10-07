<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Support\Isbn;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function lookupIsbn(Request $request)
    {
        $input = $request->query('isbn');
        $isbn = is_string($input) ? Isbn::normalize($input) : null;

        if (!$isbn || !Isbn::isValid($isbn)) {
            return response()->json(['found' => false, 'message' => 'ISBN harus berformat ISBN-10 atau ISBN-13 yang valid.']);
        }

        $existing = Book::where('isbn', $isbn)->first();
        if ($existing) {
            return response()->json([
                'found' => true,
                'source' => 'local',
                'title' => $existing->judul,
                'author' => $existing->penulis,
                'publisher' => $existing->penerbit,
                'publication_year' => $existing->tahun_terbit,
                'pages' => $existing->jumlah_halaman,
                'language' => $existing->bahasa,
                'description' => $existing->deskripsi,
                'kategori_id' => $existing->kategori_id,
                'stock' => $existing->stok,
                'message' => 'Buku sudah ada di database lokal.'
            ]);
        }

        try {
            $olUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
            $olResponse = Http::timeout(4)->get($olUrl);
            if ($olResponse->successful()) {
                $data = $olResponse->json();
                $key = "ISBN:{$isbn}";
                if (isset($data[$key])) {
                    $bookData = $data[$key];
                    return response()->json([
                        'found' => true,
                        'source' => 'openlibrary',
                        'title' => $bookData['title'] ?? '',
                        'author' => implode(', ', array_column($bookData['authors'] ?? [], 'name')),
                        'publisher' => implode(', ', array_column($bookData['publishers'] ?? [], 'name')),
                        'publication_year' => $this->extractYear($bookData['publish_date'] ?? null),
                        'pages' => $bookData['number_of_pages'] ?? null,
                        'language' => $this->openLibraryLanguages($bookData['languages'] ?? []),
                        'description' => $this->metadataText($bookData['notes'] ?? null),
                        'message' => 'Metadata ditemukan dari OpenLibrary. Periksa kembali sebelum disimpan.',
                    ]);
                }
            }

            $gbUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}";
            $gbResponse = Http::timeout(4)->get($gbUrl);
            if ($gbResponse->successful()) {
                $data = $gbResponse->json();
                if (!empty($data['items'][0]['volumeInfo'])) {
                    $info = $data['items'][0]['volumeInfo'];
                    return response()->json([
                        'found' => true,
                        'source' => 'googlebooks',
                        'title' => $info['title'] ?? '',
                        'author' => implode(', ', $info['authors'] ?? []),
                        'publisher' => $info['publisher'] ?? '',
                        'publication_year' => $this->extractYear($info['publishedDate'] ?? null),
                        'pages' => $info['pageCount'] ?? null,
                        'language' => $info['language'] ?? '',
                        'description' => $this->metadataText($info['description'] ?? null),
                        'message' => 'Metadata ditemukan dari Google Books. Periksa kembali sebelum disimpan.',
                    ]);
                }
            }
        } catch (ConnectionException $e) {
            Log::warning('Book metadata lookup could not reach an external provider.', [
                'isbn' => $isbn,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'found' => false,
                'message' => 'Layanan metadata buku tidak dapat dihubungi. Silakan isi data secara manual.',
            ], 503);
        }

        return response()->json(['found' => false, 'message' => 'Metadata buku tidak ditemukan secara online. Silakan isi manual.']);
    }

    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = '%'.$request->string('search')->toString().'%';
            $query->where(function ($query) use ($search) {
                $query->where('judul', 'like', $search)
                    ->orWhere('penulis', 'like', $search)
                    ->orWhere('penerbit', 'like', $search)
                    ->orWhere('isbn', 'like', $search)
                    ->orWhere('lokasi_rak', 'like', $search);
            });
        }

        $books = $query->with('category')->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = DB::table('tb_kategori')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->bookRules(), $this->bookMessages());

        $data['isbn'] = Isbn::normalize($data['isbn'] ?? null);
        $data['tersedia'] = $data['stok'];

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku baru berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        $categories = DB::table('tb_kategori')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate($this->bookRules($book), $this->bookMessages());

        $data['isbn'] = Isbn::normalize($data['isbn'] ?? null);
        $diff = $data['stok'] - $book->stok;
        $data['tersedia'] = max(0, $book->tersedia + $diff);

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $hasLoans = DB::table('tb_pinjaman')->where('buku_id', $book->id)->exists();
        if ($hasLoans) {
            return redirect()->route('admin.books.index')->with('cannot_delete_book', [
                'title' => $book->judul,
                'isbn' => $book->isbn ?? '-',
                'message' => "Buku '{$book->judul}' tidak dapat dihapus karena memiliki riwayat transaksi peminjaman. Riwayat tersebut dipertahankan sebagai catatan perpustakaan."
            ]);
        }

        try {
            $book->delete();
            return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.books.index')->with('cannot_delete_book', [
                'title' => $book->judul,
                'isbn' => $book->isbn ?? '-',
                'message' => "Buku '{$book->judul}' tidak dapat dihapus karena masih terikat dengan data lain di sistem database."
            ]);
        }
    }

    private function bookRules(?Book $book = null): array
    {
        return [
            'kategori_id' => ['required', 'exists:tb_kategori,id'],
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:150'],
            'kota_terbit' => ['nullable', 'string', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1000,'.(now()->year + 1)],
            'edisi' => ['nullable', 'string', 'max:50'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'bahasa' => ['nullable', 'string', 'max:50'],
            'klasifikasi' => ['nullable', 'string', 'max:30'],
            'lokasi_rak' => ['nullable', 'string', 'max:60'],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'isbn' => ['nullable', 'string', 'max:20', function ($attribute, $value, $fail) use ($book) {
                $normalized = Isbn::normalize($value);

                if ($normalized === null) {
                    return;
                }

                if ($book && $normalized === Isbn::normalize($book->isbn)) {
                    return;
                }

                if (!Isbn::isValid($normalized)) {
                    $fail('ISBN harus memiliki format ISBN-10 atau ISBN-13 yang valid.');
                    return;
                }

                $duplicate = Book::where('isbn', $normalized)
                    ->when($book, fn ($query) => $query->where('id', '!=', $book->id))
                    ->exists();

                if ($duplicate) {
                    $fail('ISBN sudah digunakan oleh buku lain.');
                }
            }],
            'stok' => ['required', 'integer', 'min:'.($book ? 0 : 1)],
        ];
    }

    private function bookMessages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
        ];
    }

    private function extractYear(?string $date): ?int
    {
        return preg_match('/\b\d{4}\b/', $date ?? '', $matches) ? (int) $matches[0] : null;
    }

    private function metadataText(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        return is_array($value) ? ($value['value'] ?? null) : null;
    }

    private function openLibraryLanguages(array $languages): string
    {
        return collect($languages)
            ->map(fn (array $language) => basename($language['key'] ?? ''))
            ->filter()
            ->implode(', ');
    }
}
