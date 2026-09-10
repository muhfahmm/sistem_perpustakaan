<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_user', function (Blueprint $table) {
            $table->unsignedInteger('active_loans_count')->default(0);
        });

        Schema::table('tb_loans', function (Blueprint $table) {
            $table->uuid('idempotency_key')->nullable()->unique()->after('loan_code');
            $table->string('request_hash', 64)->nullable()->after('idempotency_key');
            $table->index(['user_id', 'book_id', 'status'], 'idx_loans_active_lookup');
        });

        Schema::table('tb_notifications', function (Blueprint $table) {
            $table->string('dedup_key', 128)->nullable()->unique()->after('loan_id');
            $table->index(['loan_id', 'type', 'sent_at'], 'idx_notification_loan_type_date');
        });

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_prevent_negative_book_stock
BEFORE UPDATE ON tb_books
FOR EACH ROW
BEGIN
    IF NEW.available < 0 OR NEW.available > NEW.stock THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok buku tidak valid';
    END IF;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_prevent_final_loan_change
BEFORE UPDATE ON tb_loans
FOR EACH ROW
BEGIN
    IF OLD.status IN ('returned', 'rejected', 'lost') AND NEW.status <> OLD.status THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Status pinjaman sudah final';
    END IF;
    IF OLD.return_date IS NOT NULL AND NOT (NEW.return_date <=> OLD.return_date) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Buku sudah pernah dikembalikan';
    END IF;
END
SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_final_loan_change');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_negative_book_stock');
        Schema::table('tb_notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notification_loan_type_date');
            $table->dropUnique(['dedup_key']);
            $table->dropColumn('dedup_key');
        });
        Schema::table('tb_loans', function (Blueprint $table) {
            $table->dropIndex('idx_loans_active_lookup');
            $table->dropUnique(['idempotency_key']);
            $table->dropColumn(['idempotency_key', 'request_hash']);
        });
        Schema::table('tb_user', fn (Blueprint $table) => $table->dropColumn('active_loans_count'));
    }
};
