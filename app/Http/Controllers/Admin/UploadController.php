<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Support\Str;

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
}
