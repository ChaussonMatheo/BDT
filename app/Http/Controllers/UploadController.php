<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Upload;
class UploadController extends Controller
{
    // app/Http/Controllers/UploadController.php
    public function showForm($uuid)
    {
        $upload = Upload::where('uuid', $uuid)->firstOrFail();
        return view('upload.form', compact('upload'));
    }

    public function storeImages(Request $request, $uuid)
    {
        $upload = Upload::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'photos.*' => 'image|max:10240', // 10MB max
        ]);

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('uploads/photos', 'public');
            $upload->images()->create(['path' => $path]);
        }

        return back()->with('success', 'Photos envoyées avec succès.');
    }

}
