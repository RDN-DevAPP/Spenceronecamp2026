<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionUnlocked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sessionId;
    public int $reguId;
    public string $unlockedBy;
    public string $message;

    /**
     * Create a new event instance.
     */
    public function __construct(int $sessionId, int $reguId, string $unlockedBy = 'juri', string $message = 'Ujian Anda telah dibuka kembali.')
    {
        $this->sessionId = $sessionId;
        $this->reguId = $reguId;
        $this->unlockedBy = $unlockedBy;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("quiz-session.{$this->sessionId}"),
            new PrivateChannel('juri-monitor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'SessionUnlocked';
    }
}
