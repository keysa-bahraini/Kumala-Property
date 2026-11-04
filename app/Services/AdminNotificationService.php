<?php

namespace App\Services;

use App\Models\AdminNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AdminNotificationService
{
    /**
     * Buat notifikasi pengajuan listing baru.
     */
    public static function notifyListing($submission): ?AdminNotification
    {
        try {
            $rawId = is_array($submission) ? ($submission['id'] ?? '') : ($submission->id ?? '');
            $title = is_array($submission) ? ($submission['title'] ?? 'Listing Properti') : ($submission->title ?? 'Listing Properti');

            $cleanId = ltrim((string)$rawId, '#');
            $displayId = $cleanId ? ('#TP-' . preg_replace('/[^0-9]/', '', $cleanId)) : '#TP-Listing';
            if (!empty($rawId) && str_starts_with((string)$rawId, '#')) {
                $displayId = (string)$rawId;
            }

            $targetUrl = '/admin/listing-masuk?id=' . rawurlencode($cleanId);
            $notifTitle = 'Pengajuan Listing Baru';
            $notifMsg = mb_substr("{$title} ({$displayId}) menunggu kurasi & verifikasi berkas.", 0, 255);

            return self::createNotification('listing', $notifTitle, $notifMsg, $targetUrl, $cleanId);
        } catch (\Throwable $e) {
            Log::warning('AdminNotificationService: Gagal membuat notifikasi listing: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Buat notifikasi inquiry baru (dari detail properti maupun tentang-kami).
     */
    public static function notifyInquiry($lead): ?AdminNotification
    {
        try {
            $rawId = is_array($lead) ? ($lead['id'] ?? '') : ($lead->id ?? '');
            $clientName = is_array($lead) ? ($lead['client_name'] ?? 'Calon Pembeli VIP') : ($lead->client_name ?? 'Calon Pembeli VIP');
            $propTitle = is_array($lead) ? ($lead['property_title'] ?? 'Properti Terpilih') : ($lead->property_title ?? 'Properti Terpilih');

            $cleanId = ltrim((string)$rawId, '#');
            $targetUrl = '/admin/inquiry';
            $notifTitle = 'Inquiry Baru';
            $notifMsg = mb_substr("{$clientName} menanyakan {$propTitle}.", 0, 255);

            return self::createNotification('inquiry', $notifTitle, $notifMsg, $targetUrl, $cleanId);
        } catch (\Throwable $e) {
            Log::warning('AdminNotificationService: Gagal membuat notifikasi inquiry: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Buat notifikasi jadwal survei baru.
     */
    public static function notifySurvey($survey): ?AdminNotification
    {
        try {
            $rawId = is_array($survey) ? ($survey['id'] ?? '') : ($survey->id ?? '');
            $clientName = is_array($survey) ? ($survey['client_name'] ?? ($survey['client'] ?? 'Klien VIP')) : ($survey->client_name ?? ($survey->client ?? 'Klien VIP'));
            $propTitle = is_array($survey) ? ($survey['property_title'] ?? ($survey['property'] ?? 'Unit Terpilih')) : ($survey->property_title ?? ($survey->property ?? 'Unit Terpilih'));
            $surveyDate = is_array($survey) ? ($survey['survey_date'] ?? '') : ($survey->survey_date ?? '');
            if ($surveyDate instanceof \DateTimeInterface) {
                $surveyDate = $surveyDate->format('Y-m-d');
            }
            $surveyTime = is_array($survey) ? ($survey['survey_time'] ?? ($survey['time'] ?? '10:30 WIB')) : ($survey->survey_time ?? ($survey->time ?? '10:30 WIB'));

            $targetUrl = '/admin/inquiry';
            $notifTitle = 'Jadwal Survei Baru';
            $dateDisplay = $surveyDate ?: 'Tanggal Terkonfirmasi';
            $notifMsg = mb_substr("Survei {$propTitle} pada {$dateDisplay} ({$surveyTime}) bersama {$clientName}.", 0, 255);

            return self::createNotification('survey', $notifTitle, $notifMsg, $targetUrl, (string)$rawId);
        } catch (\Throwable $e) {
            Log::warning('AdminNotificationService: Gagal membuat notifikasi survei: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simpan record notifikasi ke database dengan validasi path aman.
     */
    public static function createNotification(
        string $type,
        string $title,
        string $message,
        string $targetUrl,
        ?string $referenceId = null
    ): ?AdminNotification {
        try {
            // Validasi keamanan: target_url wajib merupakan path internal yang diawali /admin/
            if (!str_starts_with($targetUrl, '/admin/')) {
                $targetUrl = '/admin/dashboard';
            }

            // Sanitasi panjang kolom database
            $cleanTitle = mb_substr(trim($title), 0, 150);
            $cleanMsg = mb_substr(trim($message), 0, 255);
            $cleanRef = $referenceId ? mb_substr(trim($referenceId), 0, 50) : null;

            return AdminNotification::create([
                'type' => $type,
                'title' => $cleanTitle,
                'message' => $cleanMsg,
                'target_url' => $targetUrl,
                'reference_id' => $cleanRef,
                'read_at' => null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AdminNotificationService: Gagal menyimpan notifikasi: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hitung total notifikasi belum dibaca.
     */
    public static function getUnreadCount(): int
    {
        try {
            return (int) AdminNotification::whereNull('read_at')->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Ambil hingga $limit notifikasi terbaru (maksimal 15, mengabaikan yang sudah dibaca > 30 hari).
     */
    public static function getLatest(int $limit = 15): array
    {
        try {
            $threshold = now()->subDays(30);

            $items = AdminNotification::where(function ($q) use ($threshold) {
                $q->whereNull('read_at')
                  ->orWhere('read_at', '>=', $threshold);
            })
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

            return $items->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'target_url' => $n->target_url,
                    'reference_id' => $n->reference_id,
                    'is_read' => !is_null($n->read_at),
                    'time_ago' => self::formatTimeAgo($n->created_at),
                    'created_at' => $n->created_at ? $n->created_at->toIso8601String() : null,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public static function markAsRead($id): bool
    {
        try {
            $notif = AdminNotification::find($id);
            if ($notif && is_null($notif->read_at)) {
                $notif->read_at = now();
                $notif->save();
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('AdminNotificationService: Gagal menandai dibaca: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tandai semua notifikasi belum dibaca sebagai sudah dibaca.
     */
    public static function markAllAsRead(): int
    {
        try {
            return (int) AdminNotification::whereNull('read_at')->update(['read_at' => now()]);
        } catch (\Throwable $e) {
            Log::warning('AdminNotificationService: Gagal menandai semua dibaca: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Format waktu relatif Bahasa Indonesia berbasis selisih jam server.
     */
    public static function formatTimeAgo($date): string
    {
        if (!$date) {
            return 'Baru saja';
        }

        try {
            $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
            $seconds = abs(now()->diffInSeconds($carbon));

            if ($seconds < 60) {
                return 'Baru saja';
            }
            if ($seconds < 3600) {
                $minutes = (int) floor($seconds / 60);
                return $minutes . ' menit lalu';
            }
            if ($seconds < 86400) {
                $hours = (int) floor($seconds / 3600);
                return $hours . ' jam lalu';
            }
            if ($seconds < 172800) {
                return 'Kemarin';
            }
            $days = (int) floor($seconds / 86400);
            return $days . ' hari lalu';
        } catch (\Throwable $e) {
            return 'Baru saja';
        }
    }
}

