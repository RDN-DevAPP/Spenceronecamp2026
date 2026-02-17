<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display the schedule page.
     */
    public function __invoke()
    {
        return view('jadwal');
    }
}
