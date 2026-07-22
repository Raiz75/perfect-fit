<?php

namespace App\Http\Controllers;

class FrontendController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function ministries()
    {
        return view('ministries');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
}
