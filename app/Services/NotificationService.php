<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send WhatsApp & Email notifications to borrower when a loan is created or approved.
     */
    public function sendLoanNotification(Loan $loan): void
    {
        $loan->loadMissing(['user', 'book']);
        $user = $loan->user;
        $book = $loan->book;

        if (!$user) {
            return;
        }

        $nama = $user->nama;
        $email = $user->email;
        $phone = $user->telepon;
        $kodePinjam = $loan->kode_pinjam;
        $judulBuku = $book->judul ?? 'Buku Perpustakaan';
        $tglPinjam = $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : date('d M Y');
        $jatuhTempo = $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : date('d M Y', strtotime('+7 days'));

        // 1. NOTIFIKASI WHATSAPP
        $waMessage = "Halo {$nama},\n\n"
            . "Peminjaman buku Anda di Perpustakaan Digital telah BERHASIL dicatat!\n\n"
            . "📌 Kode Pinjam: {$kodePinjam}\n"
            . "📚 Judul Buku: {$judulBuku}\n"
            . "📅 Tanggal Pinjam: {$tglPinjam}\n"
            . "⏰ Jatuh Tempo: {$jatuhTempo}\n\n"
            . "Harap kembalikan buku sebelum tanggal jatuh tempo. Terima kasih!";

        $waNotifId = DB::table('tb_notifikasi')->insertGetId([
            'pinjaman_id' => $loan->id,
            'user_id' => $user->id,
            'saluran' => 'wa',
            'tipe' => 'approved',
            'telepon' => substr($phone ?? '', 0, 20),
            'pesan' => $waMessage,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // Kirim via Fonnte WA Gateway API jika token tersedia
        try {
            $token = config('services.fonnte.token', env('FONNTE_TOKEN'));
            if ($token) {
                $response = Http::withHeaders(['Authorization' => $token])
                    ->timeout(5)
                    ->post('https://api.fonnte.com/send', [
                        'target' => $phone,
                        'message' => $waMessage,
                    ]);

                DB::table('tb_notifikasi')->where('id', $waNotifId)->update([
                    'respon' => $response->body(),
                    'status' => $response->successful() ? 'sent' : 'failed',
                ]);
            }
        } catch (\Exception $e) {
            Log::warning("Gagal pengiriman WhatsApp API: " . $e->getMessage());
        }

        // 2. NOTIFIKASI EMAIL
        $emailMessage = "Halo {$nama},\n\n"
            . "Terima kasih telah melakukan peminjaman buku di Perpustakaan Digital.\n\n"
            . "Detail Peminjaman Buku:\n"
            . "- Kode Transaksi : {$kodePinjam}\n"
            . "- Judul Buku     : {$judulBuku}\n"
            . "- Tanggal Pinjam : {$tglPinjam}\n"
            . "- Jatuh Tempo    : {$jatuhTempo}\n\n"
            . "Salam hangat,\nTim Perpusku";

        DB::table('tb_notifikasi')->insert([
            'pinjaman_id' => $loan->id,
            'user_id' => $user->id,
            'saluran' => 'email',
            'tipe' => 'approved',
            'telepon' => substr($phone ?: $email, 0, 20),
            'pesan' => $emailMessage,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // Kirim email langsung ke alamat email peminjam
        try {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::raw($emailMessage, function ($message) use ($email, $nama, $kodePinjam) {
                    $message->to($email, $nama)
                            ->subject("Konfirmasi Peminjaman Buku [{$kodePinjam}] - Perpusku");
                });
            }
        } catch (\Exception $e) {
            Log::warning("Gagal mengirim email peminjaman: " . $e->getMessage());
        }
    }

    public function sendWaNotification(Loan $loan): array
    {
        $loan->loadMissing(['user', 'book']);
        $user = $loan->user;
        $book = $loan->book;

        if (!$user) {
            return ['success' => false, 'message' => 'Peminjam tidak ditemukan.'];
        }

        $nama = $user->nama;
        $phone = $user->telepon;
        $kodePinjam = $loan->kode_pinjam;
        $judulBuku = $book->judul ?? 'Buku Perpustakaan';
        $tglPinjam = $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : date('d M Y');
        $jatuhTempo = $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : date('d M Y', strtotime('+7 days'));

        $waMessage = "Halo {$nama},\n\n"
            . "Pengingat Notifikasi Peminjaman Buku di Perpustakaan Digital:\n\n"
            . "📌 Kode Pinjam: {$kodePinjam}\n"
            . "📚 Judul Buku: {$judulBuku}\n"
            . "📅 Tanggal Pinjam: {$tglPinjam}\n"
            . "⏰ Jatuh Tempo: {$jatuhTempo}\n\n"
            . "Harap kembalikan buku sebelum tanggal jatuh tempo. Terima kasih!";

        $waNotifId = DB::table('tb_notifikasi')->insertGetId([
            'pinjaman_id' => $loan->id,
            'user_id' => $user->id,
            'saluran' => 'wa',
            'tipe' => 'approved',
            'telepon' => substr($phone ?? '', 0, 20),
            'pesan' => $waMessage,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        try {
            $token = config('services.fonnte.token', env('FONNTE_TOKEN'));
            if ($token) {
                $response = Http::withHeaders(['Authorization' => $token])
                    ->timeout(5)
                    ->post('https://api.fonnte.com/send', [
                        'target' => $phone,
                        'message' => $waMessage,
                    ]);

                DB::table('tb_notifikasi')->where('id', $waNotifId)->update([
                    'respon' => $response->body(),
                    'status' => $response->successful() ? 'sent' : 'failed',
                ]);
            }
        } catch (\Exception $e) {
            Log::warning("Gagal pengiriman WhatsApp API: " . $e->getMessage());
        }

        // Format link WA API (WhatsApp Web / App direct chat)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone ?? '');
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $waUrl = $cleanPhone ? "https://api.whatsapp.com/send?phone={$cleanPhone}&text=" . urlencode($waMessage) : null;

        return [
            'success' => true,
            'message' => "Notifikasi WhatsApp diproses untuk {$nama} ({$phone})!",
            'wa_url' => $waUrl,
        ];
    }

    public function sendEmailNotification(Loan $loan): array
    {
        $loan->loadMissing(['user', 'book']);
        $user = $loan->user;
        $book = $loan->book;

        if (!$user) {
            return ['success' => false, 'message' => 'Peminjam tidak ditemukan.'];
        }

        $nama = $user->nama;
        $email = $user->email;
        $phone = $user->telepon;
        $kodePinjam = $loan->kode_pinjam;
        $judulBuku = $book->judul ?? 'Buku Perpustakaan';
        $tglPinjam = $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : date('d M Y');
        $jatuhTempo = $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : date('d M Y', strtotime('+7 days'));

        $emailMessage = "Halo {$nama},\n\n"
            . "Berikut adalah Notifikasi Peminjaman Buku di Perpustakaan Digital.\n\n"
            . "Detail Peminjaman Buku:\n"
            . "- Kode Transaksi : {$kodePinjam}\n"
            . "- Judul Buku     : {$judulBuku}\n"
            . "- Tanggal Pinjam : {$tglPinjam}\n"
            . "- Jatuh Tempo    : {$jatuhTempo}\n\n"
            . "Salam hangat,\nTim Perpusku";

        DB::table('tb_notifikasi')->insert([
            'pinjaman_id' => $loan->id,
            'user_id' => $user->id,
            'saluran' => 'email',
            'tipe' => 'approved',
            'telepon' => substr($phone ?: $email, 0, 20),
            'pesan' => $emailMessage,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $subject = "Notifikasi Peminjaman Buku [{$kodePinjam}] - Perpusku";

        try {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::raw($emailMessage, function ($message) use ($email, $nama, $subject) {
                    $message->to($email, $nama)
                            ->subject($subject);
                });
            }
        } catch (\Exception $e) {
            Log::warning("Gagal mengirim email peminjaman: " . $e->getMessage());
        }

        $gmailUrl = filter_var($email, FILTER_VALIDATE_EMAIL)
            ? "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($email) . "&su=" . urlencode($subject) . "&body=" . urlencode($emailMessage)
            : null;

        return [
            'success' => true,
            'message' => "Notifikasi Email diproses untuk {$nama} ({$email})!",
            'gmail_url' => $gmailUrl,
        ];
    }
}
