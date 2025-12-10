<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Destination;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeStylesController extends Controller
{
    public function home()
    {
        $data['basicControl'] = BasicControl::firstOrCreate();
        return view('admin.home_style.home', $data);
    }

    public function selectHome(Request $request)
    {

        $selectedHome = $request->input('val');

        $configure = BasicControl::firstOrCreate();

        if (!in_array($selectedHome, collect(config('themes')[$configure->theme]['home_version'])->keys()->toArray())) {
            return response()->json(['error' => "Invalid Request"], 422);
        }
        $configure->home_style = $selectedHome;
        $configure->save();

        $homePage = collect(config('themes')[$configure->theme]['home_version'])->keys();

        $homePagesByTheme = Page::select('id', 'name', 'slug', 'home_name', 'template_name', 'status')->whereIn('home_name', $homePage)
            ->where('template_name', $configure->theme)
            ->get();

        $activeHomePage = $homePagesByTheme->firstWhere('home_name', $selectedHome);

        if ($activeHomePage) {
            $activeHomePage->update(['slug' => '/']);
        }

        foreach ($homePagesByTheme as $homePage) {
            if ($homePage->home_name !== $selectedHome) {
                $homePage->update([
                    'slug' => $homePage->home_name,
                ]);
            }
        }

        Artisan::call('optimize:clear');
        $message = ' "' . $activeHomePage->name . '" Home style selected.';
        return response()->json(['message' => $message], 200);
    }

    public function homeContent()
    {
        $destinations = Destination::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        $data['destinations'] = $destinations;
        $data['currentHomeDestinations'] = $destinations->where('show_on_home', 1)->values();

        return view('admin.home_style.home_content', $data);
    }

    public function setHomeSection(Request $request, $id = null)
    {
        $data = $request->validate([
            'destination'       => 'nullable|integer|exists:destinations,id',
            'show_on_home'      => 'required|boolean',
            'home_section_type' => 'nullable|integer',
            'sort_order_data'   => 'nullable|string',
        ]);

        try {
            $destinationId = $id ?? $data['destination'];

            if (!$destinationId) {
                return back()->with('error', 'Destination ID is missing.');
            }

            $destination = Destination::where('status', 1)
                ->where('id', $destinationId)
                ->firstOrFail();

            $destination->update([
                'show_on_home'      => $data['show_on_home'],
                'home_section_type' => $data['home_section_type'] ?? 0,
            ]);

            if (!empty($data['sort_order_data'])) {
                $sortOrderArray = json_decode($data['sort_order_data'], true);

                if (is_array($sortOrderArray)) {
                    foreach ($sortOrderArray as $item) {
                        Destination::where('id', $item['id'])
                            ->update(['sort_order' => $item['sort_order']]);
                    }
                }
            }

            return back()->with('success', 'Home section updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating home section: ' . $e->getMessage());
        }
    }
}
