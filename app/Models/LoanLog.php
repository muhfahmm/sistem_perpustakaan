<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanLog extends Model
{
    protected $table = 'tb_log_pinjaman';
    public $timestamps = false;

    protected $fillable = ['pinjaman_id', 'aksi', 'aktor_id', 'keterangan', 'waktu'];

    protected function casts(): array
    {
        return ['waktu' => 'datetime'];
    }
}
