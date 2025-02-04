<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeViewController extends Controller
{
    public function index()
    {
        return view('homeView');
    }
}
