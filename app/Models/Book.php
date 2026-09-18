<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'tb_data_buku';
    public $timestamps = false;

    protected $fillable = [
        'kategori_id', 'judul', 'penulis', 'isbn', 'stok', 'tersedia',
    ];

    protected function casts(): array
    {
        return ['stok' => 'integer', 'tersedia' => 'integer'];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'buku_id');
    }
}
