<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->timestamps();
        });

        Schema::create('tb_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('tb_categories')->nullOnDelete();
            $table->string('title', 200);
            $table->string('author', 150);
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('cover')->nullable();
            $table->unsignedInteger('stock')->default(1);
            $table->unsignedInteger('available')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('title');
            $table->index('available');
        });

        Schema::create('tb_loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code', 30)->unique();
            $table->foreignId('user_id')->constrained('tb_user')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('tb_books')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('tb_admin')->nullOnDelete();
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue', 'lost'])->default('pending');
            $table->string('qr_code_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'book_id', 'status'], 'uniq_active_loan');
            $table->index('due_date');
            $table->index('status');
            $table->index('user_id');
        });

        Schema::create('tb_loan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('tb_loans')->cascadeOnDelete();
            $table->string('action', 50);
            $table->foreignId('actor_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('tb_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('tb_loans')->nullOnDelete();
            $table->foreignId('user_id')->constrained('tb_user')->cascadeOnDelete();
            $table->enum('channel', ['wa', 'email', 'system'])->default('wa');
            $table->enum('type', ['reminder_h1', 'reminder_h', 'overdue', 'approved', 'returned']);
            $table->string('phone', 20);
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('status');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_notifications');
        Schema::dropIfExists('tb_loan_logs');
        Schema::dropIfExists('tb_loans');
        Schema::dropIfExists('tb_books');
        Schema::dropIfExists('tb_categories');
    }
};
