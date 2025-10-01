<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Song;
use App\Models\Devocional;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $nextSchedules = Schedule::with(['group', 'songs'])
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(6)
            ->get();

        $topSongs = Song::withCount('schedules as execution_count')
            ->orderBy('execution_count', 'desc')
            ->take(5)
            ->get();

        $nextUserSchedule = Schedule::whereHas('group.users', function($query) {
            $query->where('users.id', auth()->id());
        })
        ->where('date', '>=', now())
        ->orderBy('date', 'asc')
        ->first();

        $userScheduleIds = Schedule::whereHas('group.users', function($query) {
            $query->where('users.id', auth()->id());
        })
        ->where('date', '>=', now())
        ->pluck('id')
        ->toArray();

        // Buscar os 3 devocionais mais recentes publicados
        $recentDevocionais = Devocional::published()
            ->with('user')
            ->orderBy('devotional_date', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('nextSchedules', 'topSongs', 'nextUserSchedule', 'userScheduleIds', 'recentDevocionais'));
    }
}
