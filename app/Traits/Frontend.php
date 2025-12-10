<?php

namespace App\Traits;

use App\Models\Amenity;
use App\Models\Blog;
use App\Models\ContentDetails;
use App\Models\Destination;
use App\Models\Pricing;
use App\Models\Property;
use App\Models\PropertyAmenity;
use App\Models\PropertyCategory;
use App\Models\PropertyStyle;
use App\Models\PropertyType;

trait Frontend
{
    protected function getSectionsData($sections, $content, $selectedTheme)
    {
        if ($sections == null) {
            $data = ['support' => $content,];
            return view("themes.$selectedTheme.support", $data)->toHtml();
        }

        $contentData = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($sections) {
                $query->whereIn('name', $sections);
            })
            ->get();


        foreach ($sections as $section) {
            $singleContent = $contentData->where('content.name', $section)->where('content.type', 'single')->first() ?? [];
            $multipleContents = $contentData->where('content.name', $section)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
            });

            $data[$section] = [
                'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
                'multiple' => $multipleContents
            ];

            $data['banner_section_one'] = $this->getBannerSectionOneData($section, $singleContent, $multipleContents);
            $data['banner_section_two'] = $this->getBannerSectionTwoData($section, $singleContent, $multipleContents);
            $data['banner_section_three'] = $this->getBannerSectionThreeData($section, $singleContent, $multipleContents);
            $data['destination_search'] = $this->getDestinationSearchData($section, $singleContent, $multipleContents);
            $data['destination_search_four'] = $this->getDestinationSearchFourData($section, $singleContent, $multipleContents);
            $data['top_rated_destination_section'] = $this->getTopRatedDestinationData($section, $singleContent, $multipleContents);
            $data['category_section'] = $this->getCategorySectionData($section, $singleContent);
            $data['popular_amenities_seciton'] = $this->getPopularAmenitiesData($section, $singleContent);
            $data['private_two'] = $this->getPrivateTwoData($section, $singleContent);
            $data['popular_destination_section'] = $this->getPopularDestinationSectionData($section, $singleContent);
            $data['categories'] = $this->getCategoriesData($section, $singleContent, $multipleContents);
            $data['private'] = $this->getPrivateData($section, $singleContent);
            $data['blog'] = $this->getBlog($section, $singleContent);
            $data['popular'] = $this->getPopularData($section, $singleContent);
            $data['releted'] = $this->getReletedData($section, $singleContent, $multipleContents);
            $data['trending'] = $this->getTrendingData($section, $singleContent);
            $data['escape'] = $this->getEscapeData($section, $singleContent);
            $data['stays_section'] = $this->getStaysSecitonData($section, $singleContent);

            $replacement = view("themes.{$selectedTheme}.sections.{$section}", $data)->toHtml();

            $content = str_replace('<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[' . $section . ']]</div>', $replacement, $content);
            $content = str_replace('<span class="delete-block">×</span>', '', $content);
            $content = str_replace('<span class="up-block">↑</span>', '', $content);
            $content = str_replace('<span class="down-block">↓</span></div>', '', $content);
            $content = str_replace('<p><br></p>', '', $content);
        }

        return $content;
    }
    public function getBannerSectionOneData($section, $singleContent, $multipleContents)
    {
        if ($section == 'banner_section_one') {

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $destinations = Destination::withCount('property')
                ->where('status', 1)
                ->get();

            $homeDestinations = $destinations
                ->where('show_on_home', 1)
                ->sortBy('sort_order')
                ->values()->map(function ($destination) {
                    $destination->thumbUrl = getFile($destination->thumb_driver, $destination->thumb);
                    $destination->showName = $destination->title.', ' . $destination->countryTake->name;

                    return $destination;
                });

            return [
                'home_destinations' => $homeDestinations,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getBannerSectionTwoData($section, $singleContent, $multipleContents)
    {
        if ($section == 'banner_section_two') {

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $destinations = Destination::withCount('property')
                ->where('status', 1)
                ->get();

            $topDestination = $destinations
                ->sortByDesc('property_count')
                ->first();

            $homeDestinations = $destinations
                ->where('show_on_home', 1)
                ->sortBy('sort_order')
                ->values()->map(function ($destination) {
                    $destination->thumbUrl = getFile($destination->thumb_driver, $destination->thumb);
                    $destination->showName = $destination->title.', ' . $destination->countryTake->name;

                    return $destination;
                });

            return [
                'destination' => $topDestination,
                'home_destinations' => $homeDestinations,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getBannerSectionThreeData($section, $singleContent, $multipleContents)
    {
        if ($section == 'banner_section_three') {

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $destinations = Destination::where('status', 1)
                ->orderByDesc('id')
                ->get();

            $homeDestinations = $destinations
                ->where('show_on_home', 1)
                ->sortBy('sort_order')
                ->values()->map(function ($destination) {
                    $destination->thumbUrl = getFile($destination->thumb_driver, $destination->thumb);
                    return $destination;
                });

            return [
                'destinations' => $destinations,
                'home_destinations' => $homeDestinations,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getDestinationSearchData($section, $singleContent, $multipleContents)
    {
        if ($section == 'destination_search') {

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $destinations = Destination::with(['countryTake'])->where('status', 1)
                ->orderByDesc('id')
                ->get();

            $homeDestinations = $destinations
                ->where('show_on_home', 1)
                ->sortBy('sort_order')
                ->values()->map(function ($destination) {
                    $destination->thumbUrl = getFile($destination->thumb_driver, $destination->thumb);
                    return $destination;
                });

            return [
                'home_destinations' => $homeDestinations,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getDestinationSearchFourData($section, $singleContent, $multipleContents)
    {
        if ($section == 'destination_search_four') {

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $destinations = Destination::with(['countryTake'])->where('status', 1)
                ->orderByDesc('id')
                ->get();

            $homeDestinations = $destinations
                ->where('show_on_home', 1)
                ->sortBy('sort_order')
                ->values()->map(function ($destination) {
                    $destination->thumbUrl = getFile($destination->thumb_driver, $destination->thumb);
                    return $destination;
                });

            return [
                'home_destinations' => $homeDestinations,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getTopRatedDestinationData($section, $singleContent, $multipleContents)
    {
        if ($section == 'top_rated_destination_section') {

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $properties = Property::select(['id', 'status', 'slug', 'title', 'total_sell'])
                ->with(['photos'])
                ->where('status', 1)
                ->orderByDesc('total_sell')
                ->take(4)
                ->get();

            return [
                'properties' => $properties,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getCategorySectionData($section, $singleContent)
    {
        if ($section == 'category_section') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = PropertyStyle::with(['properties'])
                ->withCount('properties')
                ->where('status', 1)
                ->orderByDesc('properties_count')
                ->latest()
                ->take(4)
                ->get();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getPopularAmenitiesData($section, $singleContent)
    {
        if ($section == 'popular_amenities_seciton') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = Amenity::where('status', 1)
                ->get()
                ->map(function ($amenity) {
                    $amenity->propertyAmenity_count = PropertyAmenity::where(function ($q) use ($amenity) {
                        $q->whereJsonContains('amenities->amenity', (string) $amenity->id)
                            ->orWhereJsonContains('amenities->favourites', (string) $amenity->id)
                            ->orWhereJsonContains('amenities->safety_item', (string) $amenity->id);
                    })->count();

                    return $amenity;
                })
                ->sortByDesc('propertyAmenity_count')
                ->take(4)
                ->values();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getPrivateTwoData($section, $singleContent)
    {
        if ($section == 'private_two') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = PropertyStyle::where('status', 1)->latest()->take(5)->get();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getPopularDestinationSectionData($section, $singleContent)
    {
        if ($section == 'popular_destination_section') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $destinations = Destination::withCount(['property'])->orderByDesc('property_count')->latest()->take(3)->get();

            return [
                'single' => $single,
                'destinations' => $destinations
            ];
        }
    }
    public function getCategoriesData($section, $singleContent, $multipleContents)
    {
        $categoriesData = [];
        if ($section == 'categories') {
            $categoriesData = \Cache::get("categories_Data");
            if (!$categoriesData || $categoriesData->isEmpty()) {
                $categoriesData = PropertyCategory::where('status', 1)->latest()->get();
                \Cache::put('categories_Data', $categoriesData);
            }

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            $priceRange = Pricing::selectRaw('MIN(nightly_rate) as min_price, MAX(nightly_rate) as max_price')->first();
            $amenities = Amenity::where('status', 1)->latest()->get();

            return [
                'amenities' => $amenities,
                'max_price' => $priceRange->max_price,
                'min_price' => $priceRange->min_price,
                'categories' => $categoriesData,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getPrivateData($section, $singleContent)
    {
        if ($section == 'private') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = PropertyStyle::with(['properties'])
                ->withCount('properties')
                ->where('status', 1)
                ->orderByDesc('properties_count')
                ->latest()
                ->get();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getPopularData($section, $singleContent)
    {
        if ($section == 'popular') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = Destination::with(['countryTake:id,name','stateTake:id,name', 'cityTake:id,name'])->where('status', 1)->latest()->get();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getReletedData($section, $singleContent, $multipleContents)
    {
        $reletedData = [];
        if ($section == 'releted') {
            $reletedData = \Cache::get("releted_Data");
            if (!$reletedData || $reletedData->isEmpty()) {
                $reletedData = Blog::with('details')->where('status', 1)->latest()->take(3)->get();
                \Cache::put('releted_Data', $reletedData);
            }

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'releteds' => $reletedData,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getBlog($section, $singleContent)
    {
        if ($section == 'blog') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = Blog::with(['details', 'category'])->latest()->take(3)->get();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getTrendingData($section, $singleContent)
    {
        if ($section == 'trending') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $categories = PropertyCategory::withCount('properties')
                ->where('status', 1)
                ->orderBy('properties_count', 'desc')
                ->latest()
                ->get();

            return [
                'single' => $single,
                'categories' => $categories
            ];
        }
    }
    public function getEscapeData($section, $singleContent)
    {
        if ($section == 'escape') {
            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = PropertyStyle::where('status', 1)
                ->latest()
                ->take(4)
                ->get();

            return [
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getStaysSecitonData($section, $singleContent)
    {
        if ($section == 'stays_section') {
            $single = $singleContent
                ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media'))
                : [];

            $homePageDestinations = Destination::where('status', 1)
                ->where('show_on_home', 1)
                ->orderBy('sort_order', 'asc')
                ->get(['id', 'title', 'home_section_type']);

            $sectionLabels = [
                0 => 'Popular homes in',
                1 => 'Available next month in',
                2 => 'Stay in',
                3 => 'Homes in',
                4 => 'Place to stay in',
                5 => 'Checkout homes in',
            ];

            $groupedProperties = [];

            foreach ($homePageDestinations as $destination) {
                $label = $sectionLabels[$destination->home_section_type] ?? 'Homes in';
                $key = $label . ' ' . $destination->title;

                $query = Property::with(['host', 'allAmenity', 'destination','reviewSummary'])
                    ->where('status', 1)
                    ->where('destination_id', $destination->id);

                switch ($destination->home_section_type) {
                    case 0:
                        $query->orderByDesc('total_sell');
                        break;

                    case 1:
                        $nextMonthStart = now()->addMonth()->startOfMonth();
                        $nextMonthEnd = now()->addMonth()->endOfMonth();

                        $query->whereDoesntHave('bookings', function ($q) use ($nextMonthStart, $nextMonthEnd) {
                            $q->whereBetween('check_in_date', [$nextMonthStart, $nextMonthEnd])
                                ->orWhereBetween('check_out_date', [$nextMonthStart, $nextMonthEnd]);
                        });
                        break;

                    case 2:
                    case 3:
                    case 4:
                        $query->orderBy('id', 'asc');
                        break;

                    case 5:
                        $thisMonthStart = now()->startOfMonth();
                        $thisMonthEnd = now()->endOfMonth();

                        $query->whereHas('bookings', function ($q) use ($thisMonthStart, $thisMonthEnd) {
                            $q->whereBetween('check_out_date', [$thisMonthStart, $thisMonthEnd]);
                        });
                        break;

                    default:
                        $query->orderBy('id', 'asc');
                }

                $properties = $query->get();

                $groupedProperties[$key] = $properties->map(function ($property) {
                    $property->destination_slug = $property->destination->slug;
                    $property->is_wishlisted = auth()->check()
                        ? $property->wishlists()->where('user_id', auth()->id())->exists()
                        : 0;

                    if ($property->host) {
                        $property->host->fullname = $property->host->firstname . ' ' . $property->host->lastname;
                        $property->host->imagepath = getFile($property->host->image_driver, $property->host->image);

                        $created_at = $property->host->created_at;
                        if ($created_at) {
                            $diff = $created_at->diff(now());
                            $property->host->active_year = "{$diff->y} year {$diff->m} month {$diff->d} day";
                        }

                        if ($property->host->hostReview) {
                            $property->host->host_review_count = $property->host->hostReview->count();

                            foreach ($property->host->hostReview as $review) {
                                $guest = $review->guest;
                                if ($guest) {
                                    $guest->image_url = getFile($guest->image_driver, $guest->image);
                                }
                            }
                        } else {
                            $property->host->host_review_count = 0;
                        }
                    }

                    $amenityIds = array_merge(
                        $property->allAmenity->amenities['amenity'] ?? [],
                        $property->allAmenity->amenities['favourites'] ?? [],
                        $property->allAmenity->amenities['safety_item'] ?? []
                    );

                    $property->amenities = Amenity::select(['id', 'title', 'icon'])->whereIn('id', $amenityIds)->get();



                    return $property;
                });
            }

            return [
                'single' => $single,
                'multiple' => $groupedProperties
            ];
        }
    }
}
