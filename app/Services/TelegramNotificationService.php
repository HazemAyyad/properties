<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    /**
     * Send Telegram notification when a new user registers.
     *
     * @param  User  $user
     * @param  string  $source  Registration source: 'Website', 'Google', etc.
     */
    public function sendNewUserNotification(User $user, string $source = 'Website'): void
    {
        $token = config('services.telegram_notify.bot_token');
        $chatId = config('services.telegram_notify.chat_id');
        if (empty($token) || empty($chatId)) {
            return;
        }

        $message = __('New user registered') . "\n\n"
            . __('Name') . ': ' . ($user->name ?? '') . "\n"
            . __('Email') . ': ' . ($user->email ?? '') . "\n"
            . __('Registration source') . ': ' . $source . "\n"
            . __('Registered at') . ': ' . $user->created_at->format('Y-m-d H:i');

        $apiUrl = 'https://api.telegram.org/bot' . $token . '/sendMessage';
        try {
            Http::timeout(10)->asForm()->post($apiUrl, [
                'chat_id' => $chatId,
                'text' => $message,
                'disable_web_page_preview' => true,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram new user notification failed: ' . $e->getMessage());
        }
    }
}
