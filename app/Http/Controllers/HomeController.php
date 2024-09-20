<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $currentDateTime = Carbon::now();

        $announcements = Announcement::where('is_active', true)
            ->where('start_date', '<=', $currentDateTime)
            ->where('end_date', '>=', $currentDateTime)
            ->get();

        return view('homepage.index', compact('announcements'));
    }
}
