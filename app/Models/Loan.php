<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $table = 'tb_pinjaman';
    public $timestamps = false;

    protected $fillable = [
        'kode_pinjam', 'idempotency_key', 'request_hash', 'user_id', 'buku_id',
        'disetujui_oleh', 'tanggal_pinjam', 'jatuh_tempo', 'tanggal_kembali', 'status',
        'qr_code_path', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'status' => LoanStatus::class,
            'tanggal_pinjam' => 'date',
            'jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'disetujui_oleh');
    }
}
