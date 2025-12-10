<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Affiliate;
use App\Models\Amenity;
use App\Models\BasicControl;
use App\Models\Chat;
use App\Models\City;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Destination;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageDetail;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\State;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\Frontend;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    use Frontend, Notify;

    public function page(Request $request, $slug = '/')
    {
        try {
            $selectedTheme = getTheme();
            $homeVersion = getHomeStyle();

            $connection = DB::connection()->getPdo();

            if ($request->has('theme')) {
                $themeName = $request->theme;
                if (!in_array($themeName, array_keys(config('themes')))) {
                    throw new \Exception("Invalid  Theme Request", 404);
                }

                $selectedTheme = $request->theme;

                if ($request->has('home_version')) {
                    $homeVersion = $request->home_version;
                    if (!in_array($homeVersion, array_keys(config('themes')[$themeName]['home_version']))) {
                        throw new \Exception("Invalid  Home Request", 404);
                    }
                }

                $page = Page::where('home_name', $homeVersion)->first();
                if ($page) {
                    $slug = $page->slug;
                }
            }
            $existingSlugs = collect([]);


            DB::table('pages')->select('slug')->get()
                ->map(function ($item) use ($existingSlugs) {
                    $existingSlugs->push($item->slug);
                });

            if (!in_array($slug, $existingSlugs->toArray())) {
                throw new \Exception("Page Not Found", 404);
            }

            $pageDetails = PageDetail::with('page')
                ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                    $query->where(function ($q) use ($slug, $selectedTheme) {
                        $q->where('slug', $slug)
                            ->where('template_name', $selectedTheme);
                    })->orWhere(function ($q) use ($slug, $selectedTheme) {
                        $q->where('home_name', $slug)
                            ->where('template_name', $selectedTheme);
                    });
                })
                ->first();

            if ($request->has('theme') && $request->has('home_version')) {
                $status = 1;
            } else {
                $status = $pageDetails->page->status;
            }

            $pageSeo = [
                'page_title' => optional(optional($pageDetails->page)->seoable)->page_title ?? '',
                'meta_title' => optional(optional($pageDetails->page)->seoable)->meta_title,
                'meta_keywords' => implode(',', optional(optional($pageDetails->page)->seoable)->meta_keywords ?? []),
                'meta_description' => optional(optional($pageDetails->page)->seoable)->meta_description,
                'og_description' => optional(optional($pageDetails->page)->seoable)->og_description,
                'meta_robots' => optional(optional($pageDetails->page)->seoable)->meta_robots,
                'meta_image' => optional(optional($pageDetails->page)->seoable)
                    ? getFile(optional(optional($pageDetails->page)->seoable)->meta_image_driver, optional(optional($pageDetails->page)->seoable)->meta_image)
                    : null,
                'breadcrumb_status' => $pageDetails?->page?->breadcrumb_status,
                'breadcrumb_image' => $pageDetails?->page?->breadcrumb_status
                    ? getFile($pageDetails->page->breadcrumb_image_driver, $pageDetails->page->breadcrumb_image)
                    : null,
            ];

            $sectionsData = $this->getSectionsData($pageDetails->sections, $pageDetails->content, $selectedTheme);
            return view("themes.{$selectedTheme}.page", compact('sectionsData', 'pageSeo'));

        } catch (\Exception $exception) {
            \Cache::forget('ConfigureSetting');
            if ($exception->getCode() == 404) {
                abort(404);
            }
            if ($exception->getCode() == 403) {
                abort(403);
            }
            if ($exception->getCode() == 401) {
                abort(401);
            }
            if ($exception->getCode() == 503) {
                return redirect()->route('maintenance');
            }
            if ($exception->getCode() == "42S02") {
                die($exception->getMessage());
            }
            if ($exception->getCode() == 1045) {
                die("Access denied. Please check your username and password.");
            }
            if ($exception->getCode() == 1044) {
                die("Access denied to the database. Ensure your user has the necessary permissions.");
            }
            if ($exception->getCode() == 1049) {
                die("Unknown database. Please verify the database name exists and is spelled correctly.");
            }
            if ($exception->getCode() == 2002) {
                die("Unable to connect to the MySQL server. Check the database host and ensure the server is running.");
            }
            return redirect()->route('instructionPage');
        }
    }

    public function contact(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:91',
            'message' => 'required|max:2000',
            'subject' => 'required|max:1000',
        ]);

        try {
            $requestData = $request->except('_token', '_method');

            $email_from = $requestData['email'];
            $message = $requestData['message'];
            $subject = $requestData['subject'];
            $from = $email_from;
            Mail::to(basicControl()->sender_email)->send(new SendMail($from, $subject, $message));

            return back()->with('success', 'Mail has been sent');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function becomeAnAffiliate()
    {
        $contentDetails = ContentDetails::with('content')
            ->whereIn('content_id', Content::where('name', 'affiliate_page')->pluck('id'))
            ->get();

        $singleContent = $contentDetails->filter(function ($item) {
            return $item->content->type === 'single';
        })->first();

        $multipleContents = $contentDetails->filter(function ($item) {
            return $item->content->type === 'multiple';
        })->values()->map(function ($item) {
            $descArray = is_string($item->description)
                ? json_decode($item->description, true)
                : (array)$item->description;

            return collect($descArray)->merge(['media' => $item->content->media]);
        });

        $basicControl = BasicControl::first();

        return view(template() . 'affiliate.frontend.introduction', compact('singleContent', 'multipleContents', 'basicControl'));
    }

    public function settingChange(Request $request)
    {
        $request->validate([
            'language' => 'required|exists:languages,short_name',
            'currency' => 'sometimes|exists:currencies,id',
        ]);

        $language = Language::where('short_name', $request->language)->firstOrFail();

        Artisan::call('cache:clear');
        session()->forget(['lang', 'rtl']);
        session()->put('lang', $language->short_name);
        session()->put('rtl', $language->rtl);

        $currency = Currency::where('status', 1)->find($request->currency);

        if ($currency) {
            session()->forget(['currency_code', 'currency_symbol', 'currency_rate']);
            session()->put('currency_code', $currency->code ?? basicControl()->base_currency);
            session()->put('currency_symbol', $currency->symbol ?? basicControl()->currency_symbol);
            session()->put('currency_rate', $currency->rate ?? 1);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Language & currency updated']);
        }

        return back()->with('success', 'Update Successfully');
    }

    public function fetchSearch(Request $request)
    {
        $search = $request->query('query');

        $destinations = Destination::with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhereHas('countryTake', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('stateTake', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('cityTake', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })
            ->where('status', 1)
            ->latest()
            ->take(10)
            ->get();

        $countries = Country::where('name', 'LIKE', "%{$search}%")->latest()->take(10)->get();
        $states = State::where('name', 'LIKE', "%{$search}%")->latest()->take(10)->get();
        $cities = City::where('name', 'LIKE', "%{$search}%")->latest()->take(10)->get();

        $destinationResults = $destinations->map(function ($destination) {
            return [
                'title' => $destination->title,
                'country' => $destination->countryTake?->name ?? '',
                'state' => $destination->countryTake?->name ?? '',
                'city' => $destination->cityTake?->name ?? '',
                'type' => $destination->cityTake?->name . ', ' . $destination->stateTake?->name . ', ' . $destination->countryTake?->name,
            ];
        });

        $countryResults = $countries->map(function ($country) {
            return [
                'title' => $country->name,
                'type' => 'country',
            ];
        });

        $stateResults = $states->map(function ($state) {
            return [
                'title' => $state->name,
                'type' => $state->country->name ?? '',
            ];
        });

        $cityResults = $cities->map(function ($city) {
            return [
                'title' => $city->name,
                'type' => $city->state->name . ', ' . $city->country->name,
            ];
        });

        $destinationResults = collect($destinationResults ?? []);
        $countryResults = collect($countryResults ?? []);
        $stateResults = collect($stateResults ?? []);
        $cityResults = collect($cityResults ?? []);

        $combinedResults = $destinationResults->merge($countryResults)->merge($stateResults)->merge($cityResults);

        return response()->json($combinedResults);
    }

    public function fetchCategory(Request $request)
    {
        $currentLatitude = $request->input('latitude');
        $currentLongitude = $request->input('longitude');

        $category = null;
        if ($request->filled('id')) {
            $category = PropertyCategory::find($request->id);
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }
        }

        $countData = $request->iteration * 12;

        $properties = Property::with([
            'host.hostReview',
            'host.vendorInfo',
            'host' => function ($query) {
                $query->with('hostReview.guest', 'language', 'vendorInfo', 'activeProperties.pricing', 'activeProperties.photos', 'activeProperties.reviewSummary')->withCount('hostReview');
            },
            'photos',
            'features',
            'bookings',
            'futureBookings',
            'reviewSummary'
        ])
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('status', 1)
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->whereHas('type', function ($q) use ($request) {
                    $q->whereRaw("REPLACE(LOWER(name), ' ', '-') LIKE ?", ['%' . strtolower($request->type) . '%']);
                });
            })
            ->when($request->filled('destination'), function ($query) use ($request) {
                $query->whereHas('destination', function ($q) use ($request) {
                    $q->where("slug", $request->destination);
                });
            })
            ->when($request->filled('style'), function ($query) use ($request) {
                $query->whereHas('style', function ($q) use ($request) {
                    $q->whereRaw("REPLACE(LOWER(name), ' ', '-') LIKE ?", ['%' . strtolower($request->style) . '%']);
                });
            })
            ->when($request->filled('min_price') || $request->filled('max_price'), function ($query) use ($request) {
                $query->whereHas('pricing', function ($q) use ($request) {
                    if ($request->filled('min_price') && $request->filled('max_price')) {
                        $q->whereBetween('nightly_rate', [$request->min_price, $request->max_price]);
                    } elseif ($request->filled('min_price')) {
                        $q->where('nightly_rate', '>=', $request->min_price);
                    } elseif ($request->filled('max_price')) {
                        $q->where('nightly_rate', '<=', $request->max_price);
                    }
                });
            })
            ->when($request->filled('room') || $request->filled('bed') || $request->filled('bathroom'), function ($query) use ($request) {
                $query->whereHas('features', function ($q) use ($request) {
                    if ($request->filled('room')) {
                        $q->where('bedrooms', '>=', $request->room);
                    }
                    if ($request->filled('bed')) {
                        $q->where('beds', '>=', $request->bed);
                    }
                    if ($request->filled('bathroom')) {
                        $q->where('bathrooms', '>=', $request->bathroom);
                    }
                });
            })
            ->when($request->filled('amenities'), function ($query) use ($request) {
                $ids = explode(',', $request->amenities);
                $query->whereHas('allAmenity', function ($q) use ($ids) {
                    $q->where(function ($q2) use ($ids) {
                        foreach ($ids as $id) {
                            $q2->orWhereJsonContains('amenities->amenity', $id)
                                ->orWhereJsonContains('amenities->favourites', $id)
                                ->orWhereJsonContains('amenities->safety_item', $id);
                        }
                    });
                });
            })
            ->when($request->filled('amenity'), function ($query) use ($request) {
                $id = $request->amenity;

                $query->whereHas('allAmenity', function ($q) use ($id) {
                    $q->where(function ($q2) use ($id) {
                        $q2->orWhereJsonContains('amenities->amenity', $id)
                            ->orWhereJsonContains('amenities->favourites', $id)
                            ->orWhereJsonContains('amenities->safety_item', $id);
                    });
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {

                $searchData = $request->search;
                $userLat = $request->latitude;
                $userLong = $request->longitude;
                if ($searchData === 'Nearby' && $userLat && $userLong) {
                    $query->selectRaw("
                            properties.*,
                            (6371 * acos(
                                cos(radians(?)) *
                                cos(radians(latitude)) *
                                cos(radians(longitude) - radians(?)) +
                                sin(radians(?)) *
                                sin(radians(latitude))
                            )) AS distance
                        ", [$userLat, $userLong, $userLat])
                        ->orderBy('distance', 'asc');
                } else {
                    $query->where(function ($q) use ($searchData) {
                        $q->whereHas('destination', function ($q2) use ($searchData) {
                            $q2->where('title', 'like', '%' . $searchData . '%');
                        })->orWhere('title', 'like', '%' . $searchData . '%');
                    });
                }
            })
            ->when(
                $request->filled('adult_count') || $request->filled('children_count') || $request->filled('pet_count'),
                function ($query) use ($request) {
                    $totalPerson = $request->adult_count + $request->children_count + $request->pet_count;
                    $query->whereHas('features', function ($q) use ($totalPerson) {
                        $q->where('max_guests', '>=', $totalPerson);
                    });
                }
            )
            ->when($request->filled('datefilter'), function ($query) use ($request) {
                try {
                    [$start, $end] = explode(' - ', $request->datefilter);
                    $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();

                    $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                        $q->where(function ($sub) use ($startDate, $endDate) {
                            $sub->whereBetween('check_in_date', [$startDate, $endDate])
                                ->orWhereBetween('check_out_date', [$startDate, $endDate])
                                ->orWhere(function ($q2) use ($startDate, $endDate) {
                                    $q2->where('check_in_date', '<=', $startDate)
                                        ->where('check_out_date', '>=', $endDate);
                                });
                        });
                    });
                } catch (\Exception $e) {
                }
            })
            ->when(isset($request->sort_by), function ($q) use ($request) {
                switch ($request->sort_by) {
                    case 'htl':
                        $q->join('pricings', 'pricings.property_id', '=', 'properties.id')
                            ->orderBy('pricings.nightly_rate', 'desc')
                            ->orderBy('properties.created_at', 'desc')
                            ->select('properties.*');
                        break;

                    case 'lth':
                        $q->join('pricings', 'pricings.property_id', '=', 'properties.id')
                            ->orderBy('pricings.nightly_rate', 'asc')
                            ->orderBy('properties.created_at', 'desc')
                            ->select('properties.*');
                        break;

                    case 'asc':
                        $q->orderBy('properties.created_at', 'asc');
                        break;

                    case 'desc':
                        $q->orderBy('properties.created_at', 'desc');
                        break;

                    case 'mps':
                        $q->orderBy('properties.total_sell', 'desc');
                        break;
                }
            })
            ->latest()
            ->paginate($countData);

        $totalProperties = $properties->total();

        foreach ($properties as $property) {
            if (auth()->check()) {
                $property->is_wishlisted = $property->wishlists()->where('user_id', auth()->id())->exists() ? 1 : 0;
            } else {
                $property->is_wishlisted = 0;
            }

            $property->thumb = getFile($property->photos->images['thumb']['driver'], $property->photos->images['thumb']['path']) ?? null;

            $property->price = userCurrencyPosition($property->pricing?->nightly_rate);
            $property->detailsRoute = route('service.details', $property->slug);

            $allImages = [];
            if (!empty($property->photos->images['images'])) {
                foreach ($property->photos->images['images'] as $image) {
                    $allImages[] = getFile($image['driver'], $image['path']);
                }
            }

            $property->imagepath = $allImages;

            if ($property->host) {
                $property->host->fullname = $property->host->firstname . ' ' . $property->host->lastname;
                $property->host->imagepath = getFile($property->host->image_driver, $property->host->image);

                $created_at = $property->host->created_at;
                $diff = $created_at?->diff(now());
                $property->host->active_year = "{$diff->y} year {$diff->m} month {$diff->d} day";
            }

            $amenityIds = array_merge(
                $property->allAmenity->amenities['amenity'] ?? [],
                $property->allAmenity->amenities['favourites'] ?? [],
                $property->allAmenity->amenities['safety_item'] ?? []
            );

            $property->amenities = Amenity::select(['id', 'title', 'icon'])->whereIn('id', $amenityIds)->get();
            foreach ($property->amenities as $amenity) {
                $amenity->url = route('services', ['amenity' => $amenity->id]);
            }

            if (!empty($currentLatitude) && !empty($currentLongitude) && $property->latitude && $property->longitude) {
                $property->distance = round(
                    haversineDistance(
                        $currentLatitude,
                        $currentLongitude,
                        $property->latitude,
                        $property->longitude
                    ),
                    2
                );
            } else {
                $property->distance = null;
            }

            $bookedDates = collect();
            foreach ($property->futureBookings as $booking) {
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

            $property->bookedDates = $bookedDates->unique()->sort()->values();

            if ($property->host && $property->host->hostReview) {
                foreach ($property->host->hostReview as $review) {
                    $guest = $review->guest;
                    $guest->image_url = getFile($guest->image_driver, $guest->image);
                }
            }
            if ($property->host && $property->host->activeProperties) {
                foreach ($property->host->activeProperties as $item) {
                    $item->thumbUrl = getFile($item->photos->images['thumb']['driver'], $item->photos->images['thumb']['path']);
                }
            }
        }
        return response()->json([
            'properties' => $properties->items(),
            'hasMoreData' => $properties->hasMorePages(),
            'totalProperties' => $totalProperties,
            'destination' => $destination ?? null,
        ]);
    }


    public function wishlist(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = auth()->user();
        $productId = $request->input('product_id');

        $product = Property::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('property_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'isFavorited' => false,
                'message' => 'Removed from wishlist'
            ]);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'property_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'isFavorited' => true,
                'message' => 'Added to wishlist'
            ]);
        }
    }


    public function fetchDestination(Request $request)
    {
        $query = Destination::select(['id', 'title', 'country', 'state', 'city', 'lat', 'long'])
            ->with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])
            ->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('countryTake', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('stateTake', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('cityTake', function ($q4) use ($search) {
                        $q4->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $destinations = $query->latest()->get();

        return response()->json($destinations);
    }

    public function fetchResponseRate(Request $request, $hostId)
    {
        $guestMessages = Chat::whereNull('chat_id')
            ->where('receiver_id', $hostId)
            ->orderBy('created_at')
            ->get();

        $responseTimes = [];
        $totalMessages = 0;
        $respondedInTime = 0;

        foreach ($guestMessages as $guestMsg) {
            $reply = Chat::where('chat_id', $guestMsg->id)
                ->where('sender_id', $hostId)
                ->orderBy('created_at')
                ->first();

            $totalMessages++;

            if ($reply) {
                $diffInMinutes = $guestMsg->created_at->diffInMinutes($reply->created_at);
                $responseTimes[] = $diffInMinutes;

                if ($diffInMinutes <= 1440) {
                    $respondedInTime++;
                }
            }
        }

        $average = count($responseTimes) ? array_sum($responseTimes) / count($responseTimes) : null;

        $responseTimeText = $average === null
            ? 'No data'
            : ($average <= 60
                ? 'Within an hour'
                : ($average <= 1440
                    ? 'Within a day'
                    : 'Over a day'));

        $responseRate = $totalMessages > 0
            ? round(($respondedInTime / $totalMessages) * 100)
            : 0;

        return response()->json([
            'rate' => $responseRate,
            'time' => $responseTimeText,
        ]);
    }

    public function fetchProperties(Request $request)
    {
        $categoryId = $request->input('category_id');

        if (!is_numeric($categoryId)) {
            return response()->json(['error' => 'Invalid category ID'], 400);
        }

        $properties = Property::where('category_id', $categoryId)
            ->select('id', 'title', 'slug', 'description', 'status')
            ->where('status', 1)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'description' => Str::limit(strip_tags($property->description), 50),
                    'slug' => $property->slug,
                    'detailsUrl' => route('service.details', $property->slug),
                ];
            });

        return response()->json([
            'properties' => $properties,
        ]);
    }


    public function subscribe(Request $request)
    {
        $request->validate([
            'contactEmail' => 'required | unique:subscribers,email',
        ]);

        try {
            Subscriber::insert([
                'email' => $request->contactEmail
            ]);

            return back()->with('success', 'Subscription Completed, Welcome!!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
