<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)->get();
        return view('homepage.index', compact('announcements'));
    }
}
