<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminUserReceiptTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('tb_user_peminjam', function ($table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->boolean('status_aktif')->default(true);
        });

        Schema::create('tb_data_buku', function ($table) {
            $table->id();
            $table->string('judul');
            $table->string('isbn')->nullable();
        });

        Schema::create('tb_pinjaman', function ($table) {
            $table->id();
            $table->string('kode_pinjam');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('buku_id');
            $table->date('tanggal_pinjam');
            $table->date('jatuh_tempo');
        });

        DB::table('tb_user_peminjam')->insert([
            'id' => 4,
            'nama' => 'Peminjam Uji',
            'email' => 'peminjam@example.test',
            'telepon' => '628123456789',
        ]);
        DB::table('tb_data_buku')->insert([
            'id' => 9,
            'judul' => 'Buku Uji',
            'isbn' => '9780000000000',
        ]);
        DB::table('tb_pinjaman')->insert([
            'id' => 13,
            'kode_pinjam' => 'LN-RECEIPT-001',
            'user_id' => 4,
            'buku_id' => 9,
            'tanggal_pinjam' => '2026-10-07',
            'jatuh_tempo' => '2026-10-14',
        ]);
    }

    public function test_latest_receipt_contains_member_identifier_and_loan_details(): void
    {
        $response = app(UserController::class)->latestReceipt(User::findOrFail(4));
        $data = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('AGT-4', $data['member_code']);
        $this->assertSame('LN-RECEIPT-001', $data['kode_pinjam']);
        $this->assertSame('Buku Uji', $data['book_judul']);
    }
}
