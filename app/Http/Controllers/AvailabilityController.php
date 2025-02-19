<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::all();
        return view('availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        return view('availabilities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_closed' => 'nullable|boolean',
        ]);

        Availability::create($request->all());

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité ajoutée.');
    }

    public function edit(Availability $availability)
    {
        return view('availabilities.edit', compact('availability'));
    }

    public function update(Request $request, Availability $availability)
    {
        $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_closed' => 'nullable|boolean',
        ]);

        $availability->update($request->all());

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité mise à jour.');
    }

    public function destroy(Availability $availability)
    {
        $availability->delete();

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité supprimée.');
    }
}
