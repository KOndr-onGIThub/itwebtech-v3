<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function show(): View
    {
        return view('landing.pages.website-service');
    }
}
