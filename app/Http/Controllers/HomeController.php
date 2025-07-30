<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestation;
use App\Models\Image;
class HomeController extends Controller
{
    public function index()
    {
        $services = Prestation::all();

        // Récupérer les images qui ont une position définie, triées par position
        $homeImages = Image::whereNotNull('home_position')
            ->orderBy('home_position')
            ->get();

        return view('welcome', compact('services', 'homeImages'));
    }
}
