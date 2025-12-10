<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Package;
use App\Models\Page;
use App\Models\Product;
use App\Models\Property;
use App\Models\Seo;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    use Notify, Upload;

    public function index(Request $request)
    {
        switch ($request->type) {
            case 'Page':
                $seoableClass = Page::class;
                break;
            case 'Blog':
                $seoableClass = Blog::class;
                break;
            case 'property':
                $seoableClass = Property::class;
                break;
            default:
                throw new \Exception('Invalid seoable type');
        }

        $data['seo'] = Seo::with('seoable')
            ->where('seoable_id', $request->id)
            ->where('seoable_type', $seoableClass)
            ->first() ?? new Seo();

        $data['title'] = $request->type;
        $data['id'] = $request->id;

        return view("admin.seo", $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'seoable_id' => 'required|integer',
            'seoable_type' => 'required|string',
            'page_title' => 'required|string|min:3|max:100',
            'meta_title' => 'nullable|string|min:3|max:191',
            'meta_keywords' => 'nullable|array',
            'meta_keywords.*' => 'nullable|string|min:1|max:255',
            'meta_description' => 'nullable|string|min:1|max:500',
            'og_description' => 'nullable|string|min:1|max:500',
            'meta_robots' => 'nullable|array',
            'meta_robots.*' => 'nullable|string|min:1|max:255',
            'meta_image' => 'nullable|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            switch ($request->seoable_type) {
                case 'Page':
                    $seoableClass = Page::class;
                    break;
                case 'Blog':
                    $seoableClass = Blog::class;
                    break;
                case 'properties':
                    $seoableClass = Property::class;
                    break;
                default:
                    throw new \Exception('Invalid seoable type');
            }

            $seo = Seo::where('seoable_type', $seoableClass)
                ->where('seoable_id', $request->seoable_id)
                ->first();

            $metaImage = $seo?->meta_image;
            $metaImageDriver = $seo?->meta_image_driver;

            if ($request->hasFile('meta_image')) {
                $metaImageData = $this->fileUpload(
                    $request->meta_image,
                    config('filelocation.seo.path'),
                    null,
                    null,
                    'webp',
                    60,
                    $metaImage,
                    $metaImageDriver
                );

                throw_if(empty($metaImageData['path']), 'Image path not found');

                $metaImage = $metaImageData['path'];
                $metaImageDriver = $metaImageData['driver'];
            }

            $metaRobots = $request->meta_robots ? implode(',', $request->meta_robots) : null;

            $seo = Seo::updateOrCreate(
                [
                    'seoable_id'   => $request->seoable_id,
                    'seoable_type' => $seoableClass,
                ],
                [
                    'page_title'       => html_entity_decode($request->page_title),
                    'meta_title'       => html_entity_decode($request->meta_title),
                    'meta_keywords'    => $request->meta_keywords
                        ? array_map(fn($kw) => html_entity_decode($kw), $request->meta_keywords)
                        : null,
                    'meta_description' => html_entity_decode($request->meta_description),
                    'og_description'   => html_entity_decode($request->og_description),
                    'meta_robots'      => $metaRobots ? html_entity_decode($metaRobots) : null,
                    'meta_image'       => $metaImage,
                    'meta_image_driver'=> $metaImageDriver,
                ]
            );

            throw_if(!$seo, 'Something went wrong while saving data.');

            return back()->with('success', $request->seoable_type.' SEO has been saved.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
