<?php

namespace App\Http\Controllers\Frontend\Module;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\GoogleMapApi;
use App\Models\Page;
use App\Models\PageDetail;
use App\Models\Pricing;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\Review;
use App\Models\Tax;
use App\Models\User;
use App\Models\Wishlist;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ServiceController extends Controller
{
    public function services(Request $request)
    {
        $seoData = Page::with('seoable')
            ->where('home_name', 'stays')
            ->select(['id','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])
            ->first();

        $data['pageSeo'] = [
            'page_title' => optional($seoData->seoable)->page_title ?? '',
            'meta_title' => optional($seoData->seoable)->meta_title ?? '',
            'meta_keywords' => implode(',', optional($seoData->seoable)->meta_keywords ?? []),
            'meta_description' => optional($seoData->seoable)->meta_description ?? '',
            'og_description' => optional($seoData->seoable)->og_description ?? '',
            'meta_robots' => optional($seoData->seoable)->meta_robots ?? '',
            'meta_image' => $seoData
                ? getFile($seoData->seoable->meta_image_driver, $seoData->seoable->meta_image)
                : null,
            'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
            'breadcrumb_image' => $seoData->breadcrumb_status
                ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                : null,
        ];

        $data['categories'] = PropertyCategory::where('status', 1)->latest()->get();

        $priceRange = Pricing::selectRaw('MIN(nightly_rate) as min_price, MAX(nightly_rate) as max_price')->first();
        $data['min_price'] = $priceRange->min_price;
        $data['max_price'] = $priceRange->max_price;
        $data['amenities'] = Amenity::where('status', 1)->latest()->get();

        $data['googleMapApiKey'] = basicControl()->google_map_app_key;
        $data['googleMapId'] = basicControl()->google_map_id;

        $destinations = Destination::with(['countryTake'])->where('status', 1)
            ->orderByDesc('id')
            ->get();

        $data['homeDestinations'] = $destinations
            ->where('show_on_home', 1)
            ->sortBy('sort_order')
            ->values()->map(function ($destination) {
                $destination->thumbUrl = getFile($destination->thumb_driver, $destination->thumb);
                return $destination;
            });


        return view(template().'frontend.services.list', $data);
    }

    public function details($slug){
        try {
            $data['property'] = Property::with(['host.hostReview','review.guest','futureBookings:uid,property_id,check_out_date,check_in_date,status','seoable'])->withCount('review')->withAvg('review', 'avg_rating')->where('slug', $slug)->where('status', 1)->firstOr(function () {
                throw new \Exception('This Property is not available now');
            });
            $seoData = Page::with('seoable')
                ->where('home_name', 'stays')
                ->select(['id','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])
                ->first();

            $data['pageSeo'] = [
                'page_title' => 'Property Details',
                'meta_title' => $data['property']->seoable->meta_title ?? optional($seoData->seoable)->meta_title ?? '',
                'meta_keywords' => implode(',', $data['property']->seoable->meta_keywords ?? optional($seoData->seoable)->meta_keywords ?? []),
                'meta_description' => $data['property']->seoable->meta_description ?? optional($seoData->seoable)->meta_description ?? '',
                'og_description' => $data['property']->seoable->og_description ?? optional($seoData->seoable)->og_description ?? '',
                'meta_robots' => $data['property']->seoable->meta_robots ?? optional($seoData->seoable)->meta_robots ?? '',
                'meta_image' => $data['property']->seoable
                    ? getFile($data['property']->seoable?->meta_image_driver, $data['property']->seoable?->meta_image)
                    : ($seoData?->seoable
                        ? getFile($seoData->seoable?->meta_image_driver, $seoData->seoable?->meta_image)
                        : null),
                'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
                'breadcrumb_image' => $seoData->breadcrumb_status
                    ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                    : null,
            ];

            $amenityIds = array_merge(
                $data['property']->allAmenity->amenities['amenity'] ?? [],
                $data['property']->allAmenity->amenities['favourites'] ?? [],
                $data['property']->allAmenity->amenities['safety_item'] ?? []
            );

            $data['property']->amenities = Amenity::select(['id','title','icon'])->get();

            foreach ($data['property']->amenities as $amenity) {
                if (in_array($amenity->id, $amenityIds)) {
                    $amenity->isInThisProperty = 1;
                }else{
                    $amenity->isInThisProperty = 0;
                }
            }
            $data['property']->amenities = $data['property']->amenities->sortByDesc('isInThisProperty');

            $data['taxInfos'] = Tax::where('host_id',$data['property']->host_id)->where('status',1)->get();

            $totals = [
                'cleanliness' => 0,
                'accuracy' => 0,
                'checkin' => 0,
                'communication' => 0,
                'location' => 0,
                'value' => 0,
            ];
            $count = 0;

            foreach ($data['property']->review as $review) {
                $rating = $review->rating;

                if (is_object($rating)) {
                    $rating = (array) $rating;
                }

                if (is_array($rating)) {
                    $totals['cleanliness'] += $rating['cleanliness'] ?? 0;
                    $totals['accuracy'] += $rating['accuracy'] ?? 0;
                    $totals['checkin'] += $rating['checkin'] ?? 0;
                    $totals['communication'] += $rating['communication'] ?? 0;
                    $totals['location'] += $rating['location'] ?? 0;
                    $totals['value'] += $rating['value'] ?? 0;
                    $count++;
                }
            }

            $averages = [];

            if ($count > 0) {
                foreach ($totals as $key => $total) {
                    $averages[$key] = round($total / $count, 2);
                }
            }

            $data['average_ratings'] = $averages;
            $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

            $totalReviews = $data['property']->review->count();

            if ($totalReviews > 0) {
                foreach ($data['property']->review as $review) {
                    $avg = $review->avg_rating;

                    if (!$avg && is_array($review->rating)) {
                        $values = array_map('floatval', $review->rating);
                        $avg = count($values) ? array_sum($values) / count($values) : null;
                    }

                    if ($avg !== null) {
                        $rounded = (int) round($avg);
                        $rounded = max(1, min(5, $rounded));
                        $ratingDistribution[$rounded]++;
                    }
                }

                foreach ($ratingDistribution as $star => $cnt) {
                    $ratingDistribution[$star] = round(($cnt / $totalReviews) * 100);
                }
            }

            $data['ratingDistribution'] = $ratingDistribution;

            $data['host_review_count'] = $data['property']->host?->hostReview?->count() ?? 0;

            $bookedDates = collect();

            foreach ($data['property']->futureBookings as $booking) {
                $checkIn = \Carbon\Carbon::parse($booking->check_in_date);
                $checkOut = \Carbon\Carbon::parse($booking->check_out_date);

                $period = new \DatePeriod(
                    $checkIn,
                    new \DateInterval('P1D'),
                    $checkOut
                );

                foreach ($period as $date) {
                    $bookedDates->push($date->format('Y-m-d'));
                }
            }

            $is_wishlisted = false;

            if (auth()->check()) {
                $is_wishlisted = Wishlist::where('user_id', auth()->id())->where('property_id', $data['property']->id)->exists();
            }

            $data['bookedDates'] = $bookedDates->unique()->sort()->values();

            $data['hostOtherProperty'] = Property::select(['id','host_id','title','slug','address','latitude','longitude'])->with(['photos', 'pricing'])
                ->where('host_id', $data['property']->host_id)
                ->where('status', 1)
                ->where('id', '!=', $data['property']->id)
                ->get()
                ->map(function ($property) {
                    $property->image_url = getFile(
                        $property->photos->images['thumb']['driver'],
                        $property->photos->images['thumb']['path']
                    );
                    $property->price = userCurrencyPosition($property->pricing->nightly_rate ?? 0);
                    $property->address = $property->address;
                    $property->title = $property->title;
                    $property->details_url = route('service.details', $property->slug);

                    return $property;
                });

            foreach ($data['property']->photos->images['images'] as $key => $photo) {
                $data['formatedPmages'][$key] = getFile($photo['driver'], $photo['path']);
            }

            $data['googleMapApiKey'] = basicControl()->google_map_app_key;
            $data['googleMapId'] = basicControl()->google_map_id;

            return view(template().'frontend.services.details', $data, compact('is_wishlisted'));
        }catch (\Exception $exception){
            return  back()->with('error', $exception->getMessage());
        }
    }

    public function serviceImages($slug)
    {
        try {
            $data['property'] = Property::where('slug', $slug)->where('status', 1)->firstOr(function () {
                throw new \Exception('This Property is not available now');
            });

            return view(template().'frontend.services.images', $data);

        }catch (\Exception $exception){
            return  back()->with('error', $exception->getMessage());
        }
    }

    public function serviceHosts($username)
    {
        try {
            $data['host'] = User::with(['vendorInfo','hostReview.guest','activeProperties.photos','activeProperties.pricing'])->withCount(['hostReview'])->where('username', $username)->firstOr(function (){
                throw new \Exception('This Host is not available now');
            });

            return view(template().'frontend.services.host.profile', $data);
        }catch (\Exception $exception){
            return  back()->with('error', $exception->getMessage());
        }
    }
    public function loadMoreReviews(Request $request)
    {
        $hostId = $request->host_id;
        $page = $request->page ?? 1;
        $limit = basicControl()->paginate;

        $reviews = Review::with('guest')
            ->where('host_id', $hostId)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $html = view(template().'frontend.services.host.single_reviews', compact('reviews'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $reviews->count() === $limit
        ]);
    }
    public function loadMoreProperties(Request $request)
    {
        $hostId = $request->host_id;
        $page = $request->page ?? 1;
        $limit = 6;
        $sortBy = $request->sort_by ?? 'newest';
        $userLat = $request->user_lat;
        $userLng = $request->user_lng;

        $properties = Property::with(['pricing', 'photos'])
            ->where('host_id', $hostId)
            ->where('status', 1)
            ->when($userLat && $userLng, function ($query) use ($userLat, $userLng) {
                $query->selectRaw("
                properties.*,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                    [$userLat, $userLng, $userLat]
                );
            });

        switch ($sortBy) {
            case 'oldest':
                $properties->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $properties->join('pricings', 'pricings.property_id', '=', 'properties.id')
                    ->orderBy('pricings.nightly_rate', 'asc')
                    ->select('properties.*');
                break;
            case 'price_desc':
                $properties->join('pricings', 'pricings.property_id', '=', 'properties.id')
                    ->orderBy('pricings.nightly_rate', 'desc')
                    ->select('properties.*');
                break;
            default:
                $properties->orderBy('created_at', 'desc');
                break;
        }

        $properties = $properties
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $html = view(template().'frontend.services.host.property_cards', compact('properties'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $properties->count() === $limit,
        ]);
    }
}
