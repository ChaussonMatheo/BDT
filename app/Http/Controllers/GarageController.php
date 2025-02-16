<?php


namespace App\Http\Controllers;

use App\Models\Garage;
use Illuminate\Http\Request;

class GarageController extends Controller
{
    public function index()
    {
        $garages = Garage::all();
        return view('garages.index', compact('garages'));
    }

    public function create()
    {
        $users = \App\Models\User::all(); // Récupérer tous les utilisateurs
        return view('garages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Garage::create($request->all());
        return redirect()->route('garages.index')->with('success', 'Garage ajouté avec succès.');
    }

    public function edit(Garage $garage)
    {
        $users = \App\Models\User::all(); // Récupérer tous les utilisateurs
        return view('garages.edit', compact('garage', 'users'));
    }

    public function update(Request $request, Garage $garage)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
        ]);

        $garage->update($request->all());
        return redirect()->route('garages.index')->with('success', 'Garage mis à jour.');
    }

    public function destroy(Garage $garage)
    {
        $garage->delete();
        return redirect()->route('garages.index')->with('success', 'Garage supprimé.');
    }
}
