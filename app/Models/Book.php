<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'tb_books';
    protected $fillable = [
        'category_id', 'title', 'author', 'publisher', 'year', 'isbn',
        'cover', 'stock', 'available', 'description',
    ];

    protected function casts(): array
    {
        return ['stock' => 'integer', 'available' => 'integer', 'year' => 'integer'];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
