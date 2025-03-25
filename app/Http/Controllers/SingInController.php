<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SingInController extends Controller
{
    public function showRegisterForm()
    {
        return view('singInView');
    }
}
