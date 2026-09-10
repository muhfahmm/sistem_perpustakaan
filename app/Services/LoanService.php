<?php

namespace App\Services;

use App\Enums\LoanStatus;
use App\Exceptions\LoanException;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanService
{
    public function requestLoan(int $userId, int $bookId, string $idempotencyKey): Loan
    {
        return DB::transaction(function () use ($userId, $bookId, $idempotencyKey) {
            $requestHash = hash('sha256', $userId.'|'.$bookId);
            $existing = Loan::where('idempotency_key', $idempotencyKey)->first();

            if ($existing) {
                if ($existing->request_hash !== $requestHash) {
                    throw new LoanException('Kunci permintaan sudah digunakan untuk data berbeda.');
                }

                return $existing;
            }

            $book = Book::query()->whereKey($bookId)->lockForUpdate()->first();
            if (!$book) {
                throw new LoanException('Buku tidak ditemukan.');
            }

            if ($book->available < 1) {
                throw new LoanException('Stok buku tidak tersedia.');
            }

            $activeStatuses = [LoanStatus::PENDING->value, LoanStatus::BORROWED->value, LoanStatus::OVERDUE->value];
            $hasActive = Loan::query()
                ->where('user_id', $userId)
                ->where('book_id', $bookId)
                ->whereIn('status', $activeStatuses)
                ->lockForUpdate()
                ->exists();

            if ($hasActive) {
                throw new LoanException('Anda masih memiliki pinjaman aktif untuk buku ini.');
            }

            $activeCount = Loan::query()->where('user_id', $userId)->whereIn('status', $activeStatuses)->count();
            $maxActive = (int) config('library.max_active_loans', 3);
            if ($activeCount >= $maxActive) {
                throw new LoanException("Maksimal {$maxActive} pinjaman aktif.");
            }

            $loan = Loan::create([
                'loan_code' => $this->generateLoanCode(),
                'idempotency_key' => $idempotencyKey,
                'request_hash' => $requestHash,
                'user_id' => $userId,
                'book_id' => $bookId,
                'loan_date' => now()->toDateString(),
                'due_date' => now()->addDays((int) config('library.default_loan_days', 7))->toDateString(),
                'status' => LoanStatus::PENDING,
            ]);

            // Pending loan reserves one copy; approval must not decrement it again.
            $book->decrement('available');
            $loan->user()->increment('active_loans_count');

            LoanLog::create([
                'loan_id' => $loan->id,
                'action' => 'requested',
                'actor_id' => $userId,
                'description' => 'Pengajuan dibuat dan satu stok dicadangkan.',
            ]);

            return $loan;
        }, 3);
    }

    public function approveLoan(Loan $loan, int $adminId, string $dueDate): Loan
    {
        return DB::transaction(function () use ($loan, $adminId, $dueDate) {
            $fresh = Loan::query()->whereKey($loan->id)->lockForUpdate()->firstOrFail();
            $this->ensureTransition($fresh, LoanStatus::BORROWED);

            $fresh->update([
                'status' => LoanStatus::BORROWED,
                'approved_by' => $adminId,
                'due_date' => $dueDate,
            ]);

            LoanLog::create([
                'loan_id' => $fresh->id,
                'action' => 'approved',
                'actor_id' => $adminId,
                'description' => "Disetujui, jatuh tempo {$dueDate}.",
            ]);

            return $fresh->fresh();
        }, 3);
    }

    public function rejectLoan(Loan $loan, int $adminId, ?string $notes = null): Loan
    {
        return DB::transaction(function () use ($loan, $adminId, $notes) {
            $fresh = Loan::query()->whereKey($loan->id)->lockForUpdate()->firstOrFail();
            $this->ensureTransition($fresh, LoanStatus::REJECTED);
            $book = Book::query()->whereKey($fresh->book_id)->lockForUpdate()->firstOrFail();

            $fresh->update(['status' => LoanStatus::REJECTED, 'notes' => $notes]);
            $book->increment('available');
            $fresh->user()->decrement('active_loans_count');

            LoanLog::create([
                'loan_id' => $fresh->id,
                'action' => 'rejected',
                'actor_id' => $adminId,
                'description' => $notes ?? 'Pengajuan ditolak.',
            ]);

            return $fresh->fresh();
        }, 3);
    }

    public function returnBook(string $loanCode, int $actorId): array
    {
        return DB::transaction(function () use ($loanCode, $actorId) {
            $loan = Loan::query()->where('loan_code', $loanCode)->lockForUpdate()->first();
            if (!$loan) {
                throw new LoanException('Kode pinjaman tidak ditemukan.');
            }

            if ($loan->status === LoanStatus::RETURNED) {
                return ['loan' => $loan, 'duplicate' => true, 'message' => 'Buku sudah dikembalikan sebelumnya.'];
            }

            $this->ensureTransition($loan, LoanStatus::RETURNED);
            $book = Book::query()->whereKey($loan->book_id)->lockForUpdate()->firstOrFail();
            $loan->update(['status' => LoanStatus::RETURNED, 'return_date' => now()->toDateString()]);
            $book->increment('available');
            $loan->user()->decrement('active_loans_count');

            LoanLog::create([
                'loan_id' => $loan->id,
                'action' => 'returned',
                'actor_id' => $actorId,
                'description' => 'Buku dikembalikan.',
            ]);

            return ['loan' => $loan->fresh(), 'duplicate' => false, 'message' => 'Buku berhasil dikembalikan.'];
        }, 3);
    }

    private function ensureTransition(Loan $loan, LoanStatus $next): void
    {
        $current = $loan->status;
        if (!$current instanceof LoanStatus || !$current->canTransitionTo($next)) {
            throw new LoanException("Transisi {$current?->value} ke {$next->value} tidak valid.");
        }
    }

    private function generateLoanCode(): string
    {
        do {
            $code = 'LN-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        } while (Loan::where('loan_code', $code)->exists());

        return $code;
    }
}
