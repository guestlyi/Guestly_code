<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;

class PreloaderController extends Controller
{
    public function preloaderConfig()
    {
        $basicControl = BasicControl::first();
        return view('admin.control_panel.preloader', compact('basicControl'));
    }

    public function preloaderConfigUpdate(Request $request)
    {
        $validated = $request->validate([
            'preloader_text' => 'required|string|max:255',
            'is_preloader' => 'required|boolean',
        ]);

        $basicControl = BasicControl::firstOrCreate([]);
        $basicControl->preloader_text = $validated['preloader_text'];
        $basicControl->preloader_status = $validated['is_preloader'];
        $basicControl->save();

        return back()->with('success', 'Preloader Setting Updated Successfully');
    }
}
