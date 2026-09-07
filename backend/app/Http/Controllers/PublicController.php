<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display public landing page.
     */
    public function home()
    {
        return view('public.home');
    }

    /**
     * Display system about page.
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Display how it works page.
     */
    public function howItWorks()
    {
        return view('public.how-it-works');
    }

    /**
     * Display contact page.
     */
    public function contact()
    {
        return view('public.contact');
    }
}
