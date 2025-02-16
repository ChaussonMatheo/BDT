<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class PlanningController extends Controller
{
    public function index()
    {
        return view('planning');
    }
}
