<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('telegram:get-chat-id', function () {
    $token = config('services.telegram_notify.bot_token');
    if (!$token) {
        $this->error('TELEGRAM_BOT_TOKEN is not set in .env');
        $this->line('Add: TELEGRAM_BOT_TOKEN=your_bot_token');
        return 1;
    }
    $this->info('Fetching updates from Telegram... (send a message to your bot first)');
    $response = Http::timeout(15)->get('https://api.telegram.org/bot' . $token . '/getUpdates');
    $data = $response->json();
    if (!isset($data['ok']) || !$data['ok']) {
        $this->error('API error: ' . ($data['description'] ?? 'Unknown'));
        return 1;
    }
    $results = $data['result'] ?? [];
    if (empty($results)) {
        $this->warn('No messages found. Send a message to your bot in Telegram (Start or any text), then run this command again.');
        return 0;
    }
    $chats = [];
    foreach ($results as $r) {
        $chat = $r['message']['chat'] ?? $r['callback_query']['message']['chat'] ?? null;
        if ($chat && !in_array($chat['id'], $chats)) {
            $chats[$chat['id']] = $chat;
        }
    }
    $this->info('Your Telegram Chat ID(s):');
    foreach ($chats as $id => $chat) {
        $name = ($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? '');
        $this->line("  Chat ID: <fg=green>{$id}</> " . trim($name ? "({$name})" : ''));
    }
    $first = !empty($chats) ? array_keys($chats)[0] : null;
    $this->newLine();
    $this->line('Add to .env:');
    $this->line('  TELEGRAM_NOTIFY_ENABLED=true');
    $this->line('  TELEGRAM_BOT_TOKEN=' . $token);
    if ($first !== null) {
        $this->line('  TELEGRAM_CHAT_ID=' . $first);
    }
    return 0;
})->purpose('Get Telegram Chat ID for upgrade notifications');
