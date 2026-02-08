<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\DB;

// Ensure this is included
class NotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;

        // Save notification to the database
        DB::table('notifications')->insert([
            'id' => uniqid(),
            'type' => 'App\Events\NotificationEvent', // Example type
            'notifiable_type' => 'App\Models\Admin', // Change to the relevant model
            'notifiable_id' => $data['user_id'],
            'data' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('notification');
    }

    public function broadcastAs()
    {
        return 'notification.event';
    }

    public function broadcastWith(): array
    {
        return $this->data;
    }
}
