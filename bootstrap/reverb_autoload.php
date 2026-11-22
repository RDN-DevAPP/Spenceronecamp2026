<?php

spl_autoload_register(function ($class) {
    $base = dirname(__DIR__) . '/vendor/';
    $prefixes = [
        'React\\EventLoop\\' => $base . 'react/event-loop/src/',
        'React\\Socket\\' => $base . 'react/socket/src/',
        'React\\Stream\\' => $base . 'react/stream/src/',
        'React\\Promise\\' => $base . 'react/promise/src/',
        'React\\Promise\\Timer\\' => $base . 'react/promise-timer/src/',
        'React\\Dns\\' => $base . 'react/dns/src/',
        'React\\Cache\\' => $base . 'react/cache/src/',
        'Ratchet\\RFC6455\\' => $base . 'ratchet/rfc6455/src/',
        'Evenement\\' => $base . 'evenement/evenement/src/',
        'Clue\\React\\Redis\\' => $base . 'clue/redis-react/src/',
        'Clue\\Redis\\Protocol\\' => $base . 'clue/redis-protocol/src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
        }
    }
    return false;
}, true, true);
