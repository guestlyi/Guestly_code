<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MapsController extends Controller
{
    public function map()
    {
        $basicControl = basicControl();
        return view('admin.control_panel.map_config', compact('basicControl'));
    }
    public function mapConfigUpdate(Request $request)
    {
        $basicControl = basicControl();
        $basicControl->google_map_app_key = $request->google_map_app_key;
        $basicControl->google_map_id = $request->google_map_id;
        $basicControl->save();

        Artisan::call('optimize:clear');
        return back()->with('success', 'Map Credentials Updated Successfully.');
    }
}
