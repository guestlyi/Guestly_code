<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function wishlists(Request $request){

        $data['wishlists'] = Wishlist::with(['property.photos','property.pricing'])
            ->where('user_id',auth()->id())
            ->when($request->name, function ($query) use ($request) {
                $query->whereHas('property', function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->datefilter, function ($q, $range) {
                $dates = explode(' - ', $range);
                if (count($dates) === 2) {
                    $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                    $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                    $q->whereBetween('created_at', [$start, $end]);
                }
            })
            ->paginate(basicControl()->paginate);
        return view(template()."user.wishlists.index", $data);
    }
}
