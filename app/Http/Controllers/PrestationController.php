<?php
namespace App\Http\Controllers;

use App\Models\Prestation;
use Illuminate\Http\Request;

class PrestationController extends Controller
{
    public function index()
    {
        $prestations = Prestation::all();
        return view('prestations.index', compact('prestations'));
    }

    public function create()
    {
        return view('prestations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required|string|max:255',
            'description' => 'required|string',
            'tarif_petite_voiture' => 'required|numeric',
            'tarif_berline' => 'required|numeric',
            'tarif_suv_4x4' => 'required|numeric',
            'duree_estimee' => 'required|string',
        ]);

        Prestation::create($request->all());
        return redirect()->route('prestations.index')->with('success', 'Prestation ajoutée avec succès.');
    }

    public function edit(Prestation $prestation)
    {
        return view('prestations.edit', compact('prestation'));
    }

    public function update(Request $request, Prestation $prestation)
    {
        $request->validate([
            'service' => 'required|string|max:255',
            'description' => 'required|string',
            'tarif_petite_voiture' => 'required|numeric',
            'tarif_berline' => 'required|numeric',
            'tarif_suv_4x4' => 'required|numeric',
            'duree_estimee' => 'required|string',
        ]);

        $prestation->update($request->all());
        return redirect()->route('prestations.index')->with('success', 'Prestation mise à jour.');
    }

    public function destroy(Prestation $prestation)
    {
        $prestation->delete();
        return redirect()->route('prestations.index')->with('success', 'Prestation supprimée.');
    }
}
