<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'tb_kategori';
    public $timestamps = false;

    protected $fillable = ['kategori'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'kategori_id');
    }
}
