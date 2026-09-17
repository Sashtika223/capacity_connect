<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the About Us page.
     */
    public function index(): View
    {
        return view('about.index');
    }
}
