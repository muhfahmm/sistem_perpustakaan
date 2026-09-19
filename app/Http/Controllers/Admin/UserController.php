<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;

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

        $users = $query->latest('id')->paginate(10);
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

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data peminjam berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Data peminjam berhasil dihapus.');
    }
}
