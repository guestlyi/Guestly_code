<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogDetails;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Page;
use App\Models\PageDetail;
use App\Models\Seo;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blog(Request $request)
    {
        $search = $request->search;
        $tag = $request->tag;
        $seoData = Page::with(['seoable'])->where('name', 'blog')->select(['id','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])->first();

        $data['pageSeo'] = [
            'page_title' => $seoData->seoable?->page_title ?? '',
            'meta_title' => $seoData->seoable?->meta_title,
            'meta_keywords' => implode(',', $seoData->seoable?->meta_keywords ?? []),
            'meta_description' => $seoData->seoable?->meta_description,
            'og_description' => $seoData->seoable?->og_description,
            'meta_robots' => $seoData->seoable?->meta_robots,
            'meta_image' => $seoData->seoable
                ? getFile($seoData->seoable?->meta_image_driver, $seoData->seoable?->meta_image)
                : null,
            'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
            'breadcrumb_image' => $seoData->breadcrumb_status
                ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                : null,
        ];
        $data['contentDetails'] = ContentDetails::whereHas('content', function ($query) {
            $query->where('name', 'blog')->where('type', 'single');
        })->get();

        $data['blogs'] = Blog::with('category', 'details')
            ->orderBy('id', 'desc')
            ->where('status', 1)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('details', function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                });
            })
            ->when($tag, function ($query) use ($tag) {
                $query->whereJsonContains('meta_keywords', $tag);
            })
            ->get();
        return view(template().'frontend.blogs.list', $data);
    }

    public function blogDetails($slug)
    {
        try {
            $data['blogDetails'] = BlogDetails::with(['blog.seoable'])->whereHas('blog', function ($query) use ($slug) {
                $query->where('slug', $slug)->where('status', 1);
            })->with('blog')->firstOr(function () {
                throw new \Exception('This Blog is not available now');
            });

            $data['pageSeo'] = [
                'page_title' => $data['blogDetails']->blog->seoable?->page_title ?? '',
                'meta_title' => $data['blogDetails']->blog->seoable?->meta_title,
                'meta_keywords' => implode(',', $data['blogDetails']->blog->seoable?->meta_keywords ?? []),
                'meta_description' => $data['blogDetails']->blog->seoable?->meta_description,
                'og_description' => $data['blogDetails']->blog->seoable?->og_description,
                'meta_robots' => $data['blogDetails']->blog->seoable?->meta_robots,
                'meta_image' => $data['blogDetails']->blog->seoable
                    ? getFile($data['blogDetails']->blog->seoable?->meta_image_driver, $data['blogDetails']->blog->seoable?->meta_image)
                    : null,
                'breadcrumb_status' => $data['blogDetails']->blog->breadcrumb_status ?? null,
                'breadcrumb_image' => $data['blogDetails']->blog->breadcrumb_status
                    ? getFile($data['blogDetails']->blog->breadcrumb_image_driver, $data['blogDetails']->blog->breadcrumb_image)
                    : null,
            ];

            $blogs = Blog::with(['category', 'details'])
                ->orderByDesc('created_at')
                ->orderByDesc('total_view')
                ->where('status', 1)
                ->get();

            $data['tags'] = Seo::where('seoable_type', Blog::class)->pluck('meta_keywords')
                ->filter()
                ->flatMap(fn($keywords) => is_array($keywords) ? $keywords : explode(',', $keywords))
                ->map(fn($keyword) => trim($keyword))
                ->unique()
                ->values()
                ->toArray();

            $averageViews = $blogs->avg('total_view');
            $data['popular'] = $blogs->where('total_view', '>=', $averageViews)->take(3);
            $data['recent'] = $blogs->sortByDesc('created_at')->take(3);
            $data['trending'] = $blogs->where('total_view', '>=', $averageViews)
                ->sortByDesc('created_at')
                ->take(3);

            return view(template().'frontend.blogs.details', $data);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

    }
}
