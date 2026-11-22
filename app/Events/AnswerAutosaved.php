<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerAutosaved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sessionId;
    public int $reguId;
    public string $reguName;
    public int $round;
    public int $questionId;
    public int $answeredCount;
    public int $totalQuestions;
    public string $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(
        int $sessionId,
        int $reguId,
        string $reguName,
        int $round,
        int $questionId,
        int $answeredCount,
        int $totalQuestions
    ) {
        $this->sessionId = $sessionId;
        $this->reguId = $reguId;
        $this->reguName = $reguName;
        $this->round = $round;
        $this->questionId = $questionId;
        $this->answeredCount = $answeredCount;
        $this->totalQuestions = $totalQuestions;
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
            new PrivateChannel('juri-monitor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'AnswerAutosaved';
    }
}
