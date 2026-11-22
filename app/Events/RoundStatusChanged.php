<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $round;
    public string $status; // 'LOCKED', 'ACTIVE', 'FINISHED'
    public array $allStatuses;
    public ?string $endTime;
    public int $remainingSeconds;
    public ?string $message;

    public function __construct(
        int $round,
        string $status,
        ?string $endTime = null,
        int $remainingSeconds = 0,
        ?string $message = null,
        array $allStatuses = []
    ) {
        $this->round = $round;
        $this->status = $status;
        $this->endTime = $endTime;
        $this->remainingSeconds = $remainingSeconds;
        $this->message = $message;
        $this->allStatuses = $allStatuses;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('quiz-arena'),
            new PrivateChannel('juri-monitor'),
            new Channel('public-live-screen'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'RoundStatusChanged';
    }
}
