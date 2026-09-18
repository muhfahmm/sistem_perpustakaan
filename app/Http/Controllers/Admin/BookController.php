<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function lookupIsbn(Request $request)
    {
        $isbn = preg_replace('/[^0-9X]/i', '', $request->query('isbn', ''));

        if (!$isbn) {
            return response()->json(['found' => false, 'message' => 'ISBN tidak valid.']);
        }

        // Check in local database first
        $existing = Book::where('isbn', $isbn)->first();
        if ($existing) {
            return response()->json([
                'found' => true,
                'source' => 'local',
                'title' => $existing->judul,
                'kategori_id' => $existing->kategori_id,
                'stock' => $existing->stok,
                'message' => 'Buku sudah ada di database lokal.'
            ]);
        }

        // Query OpenLibrary API
        try {
            $olUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
            $olResponse = Http::timeout(4)->get($olUrl);
            if ($olResponse->successful()) {
                $data = $olResponse->json();
                $key = "ISBN:{$isbn}";
                if (isset($data[$key])) {
                    $bookData = $data[$key];
                    $title = $bookData['title'] ?? '';

                    if ($title) {
                        return response()->json([
                            'found' => true,
                            'source' => 'openlibrary',
                            'title' => $title,
                            'message' => 'Detail buku ditemukan dari OpenLibrary.'
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {}

        // Query Google Books API fallback
        try {
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
                        'message' => 'Detail buku ditemukan dari Google Books.'
                    ]);
                }
            }
        } catch (\Exception $e) {}

        return response()->json(['found' => false, 'message' => 'Metadata buku tidak ditemukan secara online. Silakan isi manual.']);
    }

    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
        }

        $books = $query->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = DB::table('tb_kategori')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => ['required', 'exists:tb_kategori,id'],
            'judul' => ['required', 'string', 'max:200'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:tb_data_buku,isbn'],
            'stok' => ['required', 'integer', 'min:1'],
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih sebelum menyimpan buku.',
        ]);

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
        $data = $request->validate([
            'kategori_id' => ['required', 'exists:tb_kategori,id'],
            'judul' => ['required', 'string', 'max:200'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:tb_data_buku,isbn,'.$book->id],
            'stok' => ['required', 'integer', 'min:0'],
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
        ]);

        $diff = $data['stok'] - $book->stok;
        $data['tersedia'] = max(0, $book->tersedia + $diff);

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
