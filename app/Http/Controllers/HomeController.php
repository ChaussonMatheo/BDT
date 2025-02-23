<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestation;

class HomeController extends Controller
{
    public function index()
    {
        $services =  Prestation::all();
        return view('welcome', compact('services'));
    }
}
