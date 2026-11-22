<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sessionId;
    public int $reguId;
    public string $reguName;
    public int $round;
    public ?int $scoreRound1;
    public ?int $scoreRound2;
    public ?int $scoreRound3;
    public int $totalScore;

    /**
     * Create a new event instance.
     */
    public function __construct(
        int $sessionId,
        int $reguId,
        string $reguName,
        int $round,
        ?int $scoreRound1 = null,
        ?int $scoreRound2 = null,
        ?int $scoreRound3 = null,
        int $totalScore = 0
    ) {
        $this->sessionId = $sessionId;
        $this->reguId = $reguId;
        $this->reguName = $reguName;
        $this->round = $round;
        $this->scoreRound1 = $scoreRound1;
        $this->scoreRound2 = $scoreRound2;
        $this->scoreRound3 = $scoreRound3;
        $this->totalScore = $totalScore;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('quiz-arena'),
            new PrivateChannel('juri-monitor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ScoreUpdated';
    }
}
