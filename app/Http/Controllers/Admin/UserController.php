<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
        }

        $users = $query->withCount(['loans as active_loans_count' => function ($q) {
            $q->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue']);
        }])->latest('id')->paginate(10);
        $books = Book::where('tersedia', '>', 0)->orderBy('judul')->get();

        return view('admin.users.index', compact('users', 'books'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:tb_user_peminjam,email'],
            'telepon' => ['required', 'string', 'max:20'],
            'status_aktif' => ['required', 'boolean'],
        ]);

        $data['telepon'] = '62' . preg_replace('/^(\+62|62|0)/', '', trim($data['telepon']));

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Data peminjam baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:tb_user_peminjam,email,'.$user->id],
            'telepon' => ['required', 'string', 'max:20'],
            'status_aktif' => ['required', 'boolean'],
        ]);

        $data['telepon'] = '62' . preg_replace('/^(\+62|62|0)/', '', trim($data['telepon']));

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data peminjam berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $activeLoans = Loan::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'borrowed', 'approved', 'overdue'])
                ->get();

            foreach ($activeLoans as $loan) {
                Book::where('id', $loan->buku_id)->increment('tersedia');
            }

            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Data peminjam berhasil dihapus dan stok buku pinjaman dikembalikan.');
    }

    public function userLoans(User $user)
    {
        $loans = Loan::with(['book'])
            ->where('user_id', $user->id)
            ->latest('id')
            ->paginate(10);

        return view('admin.users.loans', compact('user', 'loans'));
    }
}
