<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLoanListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('tb_user_peminjam', function ($table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
        });

        Schema::create('tb_data_buku', function ($table) {
            $table->id();
            $table->string('judul');
        });

        Schema::create('tb_pinjaman', function ($table) {
            $table->id();
            $table->string('kode_pinjam');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('buku_id');
            $table->string('status');
            $table->date('tanggal_pinjam')->nullable();
            $table->date('jatuh_tempo')->nullable();
            $table->date('tanggal_kembali')->nullable();
        });

        DB::table('tb_user_peminjam')->insert([
            'id' => 1,
            'nama' => 'Peminjam Uji',
            'email' => 'peminjam@example.test',
            'telepon' => '628123456789',
        ]);
        DB::table('tb_data_buku')->insert(['id' => 1, 'judul' => 'Buku Uji']);
        DB::table('tb_pinjaman')->insert([
            [
                'id' => 1,
                'kode_pinjam' => 'LN-ACTIVE',
                'user_id' => 1,
                'buku_id' => 1,
                'status' => 'borrowed',
                'tanggal_kembali' => null,
            ],
            [
                'id' => 2,
                'kode_pinjam' => 'LN-RETURNED',
                'user_id' => 1,
                'buku_id' => 1,
                'status' => 'returned',
                'tanggal_kembali' => now()->toDateString(),
            ],
        ]);
    }

    public function test_returned_loans_are_checked_and_hidden_from_active_list_by_default(): void
    {
        $controller = new LoanController();

        $activeResponse = $controller->index(Request::create('/admin-panel/loans'));
        $activeLoans = $activeResponse->getData()['loans']->getCollection();

        $this->assertSame(['borrowed'], $activeLoans->pluck('status')->map(fn ($status) => $status->value)->all());

        $returnedResponse = $controller->index(Request::create('/admin-panel/loans', 'GET', ['status' => 'returned']));
        $returnedLoans = $returnedResponse->getData()['loans']->getCollection();

        $this->assertSame(['returned'], $returnedLoans->pluck('status')->map(fn ($status) => $status->value)->all());

        $allResponse = $controller->index(Request::create('/admin-panel/loans', 'GET', ['status' => 'semua']));
        $allStatuses = $allResponse->getData()['loans']->getCollection()
            ->pluck('status')
            ->map(fn ($status) => $status->value)
            ->sort()
            ->values()
            ->all();

        $this->assertSame(['borrowed', 'returned'], $allStatuses);
    }
}
