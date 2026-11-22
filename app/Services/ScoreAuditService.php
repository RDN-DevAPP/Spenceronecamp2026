<?php

namespace App\Services;

use App\Models\Score;
use App\Models\ScoreAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreAuditService
{
    /**
     * Log a score mutation or status change into score_audit_logs.
     */
    public static function log(
        Score $score,
        string $action,
        ?float $oldScore = null,
        ?float $newScore = null,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        ?string $reason = null,
        ?Request $request = null
    ): ScoreAuditLog {
        $user = Auth::user();
        $ip = $request ? $request->ip() : request()->ip();
        $userAgent = $request ? $request->userAgent() : request()->userAgent();

        return ScoreAuditLog::create([
            'score_id' => $score->id,
            'user_id' => $user ? $user->id : $score->juri_id,
            'regu_profile_id' => $score->regu_profile_id,
            'mata_lomba_id' => $score->mata_lomba_id,
            'action' => $action,
            'old_score' => $oldScore,
            'new_score' => $newScore ?? $score->nilai,
            'old_status' => $oldStatus,
            'new_status' => $newStatus ?? $score->status,
            'reason' => $reason,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}
