<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoinRoyaleUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $state;
    public ?string $announcement;

    public function __construct(array $state, ?string $announcement = null)
    {
        $this->state = $state;
        $this->announcement = $announcement;
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
        return 'CoinRoyaleUpdated';
    }
}
