<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QrQuestionClaimed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $questionId;
    public string $codeLabel;
    public int $reguId;
    public string $reguName;
    public int $points;
    public int $newRound1Score;
    public string $claimedAnswer;
    public string $claimedAt;

    public function __construct(
        int $questionId,
        string $codeLabel,
        int $reguId,
        string $reguName,
        int $points,
        int $newRound1Score,
        string $claimedAnswer,
        string $claimedAt
    ) {
        $this->questionId = $questionId;
        $this->codeLabel = $codeLabel;
        $this->reguId = $reguId;
        $this->reguName = $reguName;
        $this->points = $points;
        $this->newRound1Score = $newRound1Score;
        $this->claimedAnswer = $claimedAnswer;
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
        return 'QrQuestionClaimed';
    }
}
