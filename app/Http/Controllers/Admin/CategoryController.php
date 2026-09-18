<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('tb_kategori')
            ->leftJoin('tb_data_buku', 'tb_kategori.id', '=', 'tb_data_buku.kategori_id')
            ->select('tb_kategori.id', 'tb_kategori.kategori', DB::raw('COUNT(tb_data_buku.id) as total_buku'))
            ->groupBy('tb_kategori.id', 'tb_kategori.kategori')
            ->orderBy('tb_kategori.id', 'desc')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100', 'unique:tb_kategori,kategori'],
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori buku berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100', 'unique:tb_kategori,kategori,' . $category->id],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori buku berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori buku berhasil dihapus.');
    }
}
