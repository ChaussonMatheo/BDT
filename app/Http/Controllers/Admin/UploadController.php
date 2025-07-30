<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Support\Str;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::withCount('images')->latest()->get();
        return view('admin.uploads.index', compact('uploads'));
    }

    public function create()
    {
        return view('admin.uploads.create');
    }

    public function store()
    {
        $upload = Upload::create([
            'uuid' => Str::uuid(),
        ]);

        return redirect()->route('admin.uploads.index')->with('success', 'Lien de dépôt créé.');
    }

    public function show(Upload $upload)
    {
        $upload->load('images');
        return view('admin.uploads.show', compact('upload'));
    }

    public function destroy(Upload $upload)
    {
        $upload->delete();
        return redirect()->route('admin.uploads.index')->with('success', 'Lien supprimé.');
    }
    public function showimage()
    {
        $images = Image::all();
        return view('admin.uploads.imageindex', compact('images'));
    }
    public function destroyImage(Image $image)
    {
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
    public function setHomePosition(Request $request, Image $image)
    {
        $request->validate([
            'home_position' => 'required|integer|min:1|max:5',
        ]);

        $image->home_position = $request->home_position;
        $image->save();

        return redirect()->back()->with('success', 'Position enregistrée.');
    }

}
