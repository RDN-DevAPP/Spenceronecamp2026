<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RevealChampionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public bool $isRevealed;
    public ?array $top3Putra;
    public ?array $top3Putri;
    public string $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(bool $isRevealed = true, ?array $top3Putra = null, ?array $top3Putri = null)
    {
        $this->isRevealed = $isRevealed;
        $this->top3Putra = $top3Putra;
        $this->top3Putri = $top3Putri;
        $this->timestamp = now()->toIso8601String();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('public-live-screen'),
            new Channel('presence-quiz-arena'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'RevealChampionEvent';
    }
}
