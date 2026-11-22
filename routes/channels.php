<?php

use App\Models\User;
use App\Models\CerdasCermatSession;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports.
|
*/

// Private channel for specific quiz session: regu participant, or juri, or admin
Broadcast::channel('quiz-session.{sessionId}', function (User $user, $sessionId) {
    if (in_array($user->role, ['juri', 'admin'])) {
        return true;
    }

    $session = CerdasCermatSession::find($sessionId);
    if (!$session) {
        return false;
    }

    return $user->reguProfile && $user->reguProfile->id === $session->regu_id;
});

// Presence channel for live attendance in quiz arena
Broadcast::channel('quiz-arena', function (User $user) {
    if (!$user) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
        'role' => $user->role,
        'regu_name' => $user->reguProfile ? $user->reguProfile->nama_regu : null,
        'regu_id' => $user->reguProfile ? $user->reguProfile->id : null,
    ];
});

// Private channel for juri & admin live monitor
Broadcast::channel('juri-monitor', function (User $user) {
    return in_array($user->role, ['juri', 'admin']);
});
