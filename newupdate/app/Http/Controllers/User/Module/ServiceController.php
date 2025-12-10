<?php

namespace App\Http\Controllers\User\Module;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use Notify, Upload;
    public function list(Request $request)
    {
        $data['properties'] = Property::with(['photos'])
            ->where('host_id', auth()->id())
            ->when($request->search, fn($q, $search) =>
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                )
            ->latest()
            ->paginate(basicControl()->paginate);

        return view(template() . 'vendor.properties.list', $data);
    }

    public function incompleted(Request $request)
    {
        $incompleted = Property::select(['id','host_id','title','status'])->with(['photos:id,property_id,images'])->where('host_id', auth()->id())->whereIn('status',[0,6])->latest()->get();
        foreach ($incompleted as $key => $value) {
            $value->thumb = $value->photos?->images ? getFile($value->photos->images['thumb']['driver'], $value->photos->images['thumb']['path']) : asset(template(true).'img/no_image.png') ;;
            $value->url = route('user.listing.about.your.place', ['property_id' => $value->id]);
        }

        return response()->json($incompleted);
    }

    public function delete($id)
    {
        try {
            $property = Property::with([
                'photos',
                'features',
                'pricing',
                'availability',
                'allAmenity',
                'activites'
            ])->where('host_id', auth()->id())->findOrFail($id);

            if ($property->photos) {
                $thumb = $property->photos->images['thumb'] ?? null;
                $images = $property->photos->images['images'] ?? [];

                if ($thumb) {
                    $this->fileDelete($thumb['driver'], $thumb['path']);
                }

                foreach ($images as $image) {
                    $this->fileDelete($image['driver'], $image['path']);
                }

                $property->photos->delete();
            }

            if ($property->features) {
                $property->features()->delete();
            }

            if ($property->pricing) {
                $property->pricing()->delete();
            }

            if ($property->availability) {
                $property->availability()->delete();
            }

            if ($property->allAmenity) {
                $property->allAmenity()->delete();
            }

            if ($property->activites) {
                $property->activites()->delete();
            }

            $property->delete();

            return back()->with('success', __('Property has been deleted'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
