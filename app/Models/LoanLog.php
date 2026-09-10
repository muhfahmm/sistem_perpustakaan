<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanLog extends Model
{
    protected $table = 'tb_loan_logs';
    public $timestamps = false;

    protected $fillable = ['loan_id', 'action', 'actor_id', 'description', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
