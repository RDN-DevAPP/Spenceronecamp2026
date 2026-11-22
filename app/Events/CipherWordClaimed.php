<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CipherWordClaimed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $gridId;
    public int $wordId;
    public string $word;
    public int $points;
    public int $reguId;
    public string $reguName;
    public int $newRound2Score;
    public string $claimedAt;

    public function __construct(
        int $gridId,
        int $wordId,
        string $word,
        int $points,
        int $reguId,
        string $reguName,
        int $newRound2Score,
        string $claimedAt
    ) {
        $this->gridId = $gridId;
        $this->wordId = $wordId;
        $this->word = $word;
        $this->points = $points;
        $this->reguId = $reguId;
        $this->reguName = $reguName;
        $this->newRound2Score = $newRound2Score;
        $this->claimedAt = $claimedAt;
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
        return 'CipherWordClaimed';
    }
}
