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

            if ($book->tersedia < 1) {
                throw new LoanException('Stok buku tidak tersedia.');
            }

            $activeStatuses = [LoanStatus::PENDING->value, LoanStatus::BORROWED->value, LoanStatus::OVERDUE->value];
            $hasActive = Loan::query()
                ->where('user_id', $userId)
                ->where('buku_id', $bookId)
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
                'kode_pinjam' => $this->generateLoanCode(),
                'idempotency_key' => $idempotencyKey,
                'request_hash' => $requestHash,
                'user_id' => $userId,
                'buku_id' => $bookId,
                'tanggal_pinjam' => now()->toDateString(),
                'jatuh_tempo' => now()->addDays((int) config('library.default_loan_days', 7))->toDateString(),
                'status' => LoanStatus::PENDING,
            ]);

            $book->decrement('tersedia');

            LoanLog::create([
                'pinjaman_id' => $loan->id,
                'aksi' => 'requested',
                'aktor_id' => $userId,
                'keterangan' => 'Pengajuan dibuat dan satu stok dicadangkan.',
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
                'disetujui_oleh' => $adminId,
                'jatuh_tempo' => $dueDate,
            ]);

            LoanLog::create([
                'pinjaman_id' => $fresh->id,
                'aksi' => 'approved',
                'aktor_id' => $adminId,
                'keterangan' => "Disetujui, jatuh tempo {$dueDate}.",
            ]);

            return $fresh->fresh();
        }, 3);
    }

    public function rejectLoan(Loan $loan, int $adminId, ?string $notes = null): Loan
    {
        return DB::transaction(function () use ($loan, $adminId, $notes) {
            $fresh = Loan::query()->whereKey($loan->id)->lockForUpdate()->firstOrFail();
            $this->ensureTransition($fresh, LoanStatus::REJECTED);
            $book = Book::query()->whereKey($fresh->buku_id)->lockForUpdate()->firstOrFail();

            $fresh->update(['status' => LoanStatus::REJECTED, 'catatan' => $notes]);
            $book->increment('tersedia');

            LoanLog::create([
                'pinjaman_id' => $fresh->id,
                'aksi' => 'rejected',
                'aktor_id' => $adminId,
                'keterangan' => $notes ?? 'Pengajuan ditolak.',
            ]);

            return $fresh->fresh();
        }, 3);
    }

    public function returnBook(string $loanCode, int $actorId): array
    {
        return DB::transaction(function () use ($loanCode, $actorId) {
            $loan = Loan::query()->where('kode_pinjam', $loanCode)->lockForUpdate()->first();
            if (!$loan) {
                throw new LoanException('Kode pinjaman tidak ditemukan.');
            }

            if ($loan->status === LoanStatus::RETURNED) {
                return ['loan' => $loan, 'duplicate' => true, 'message' => 'Buku sudah dikembalikan sebelumnya.'];
            }

            $this->ensureTransition($loan, LoanStatus::RETURNED);
            $book = Book::query()->whereKey($loan->buku_id)->lockForUpdate()->firstOrFail();
            $loan->update(['status' => LoanStatus::RETURNED, 'tanggal_kembali' => now()->toDateString()]);
            $book->increment('tersedia');

            LoanLog::create([
                'pinjaman_id' => $loan->id,
                'aksi' => 'returned',
                'aktor_id' => $actorId,
                'keterangan' => 'Buku dikembalikan.',
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
        } while (Loan::where('kode_pinjam', $code)->exists());

        return $code;
    }
}
