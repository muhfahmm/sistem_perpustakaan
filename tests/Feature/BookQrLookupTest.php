<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ScanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BookQrLookupTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('tb_data_buku', function ($table) {
            $table->id();
            $table->string('judul');
            $table->string('isbn')->nullable();
            $table->unsignedInteger('stok')->default(1);
            $table->unsignedInteger('tersedia')->default(1);
        });

        DB::table('tb_data_buku')->insert([
            'id' => 7,
            'judul' => 'Buku Tanpa ISBN',
            'isbn' => null,
            'stok' => 2,
            'tersedia' => 2,
        ]);
    }

    public function test_book_qr_identifier_resolves_to_book(): void
    {
        $response = app(ScanController::class)->lookupBook(
            Request::create('/admin-panel/scan/lookup-book', 'POST', ['code' => 'BOOK-7'])
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
        $this->assertSame(7, $response->getData(true)['book']['id']);
    }
}
