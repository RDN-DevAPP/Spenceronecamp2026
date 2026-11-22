<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamViolationLogged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sessionId;
    public int $reguId;
    public string $reguName;
    public string $violationType;
    public string $violationLabel;
    public int $round;
    public int $violationCount;
    public bool $isFrozen;
    public string $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(
        int $sessionId,
        int $reguId,
        string $reguName,
        string $violationType,
        string $violationLabel,
        int $round,
        int $violationCount,
        bool $isFrozen,
        ?string $timestamp = null
    ) {
        $this->sessionId = $sessionId;
        $this->reguId = $reguId;
        $this->reguName = $reguName;
        $this->violationType = $violationType;
        $this->violationLabel = $violationLabel;
        $this->round = $round;
        $this->violationCount = $violationCount;
        $this->isFrozen = $isFrozen;
        $this->timestamp = $timestamp ?? now()->toIso8601String();
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
            new PrivateChannel("quiz-session.{$this->sessionId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ExamViolationLogged';
    }
}
