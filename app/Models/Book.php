<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'tb_data_buku';
    public $timestamps = false;

    protected $fillable = [
        'kategori_id', 'judul', 'penulis', 'penerbit', 'kota_terbit',
        'tahun_terbit', 'edisi', 'jumlah_halaman', 'bahasa', 'klasifikasi',
        'lokasi_rak', 'deskripsi', 'isbn', 'stok', 'tersedia',
    ];

    protected function casts(): array
    {
        return [
            'tahun_terbit' => 'integer',
            'jumlah_halaman' => 'integer',
            'stok' => 'integer',
            'tersedia' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'buku_id');
    }
}
