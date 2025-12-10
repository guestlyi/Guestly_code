<?php

namespace App\Http\Middleware;

use App\Models\Blog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class visitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $urlSegments = explode('/', $request->url());
        $slug = end($urlSegments);
        $ipAddress = $request->ip();
        $key = "bouncing_time_{$slug}_{$ipAddress}";

        if (!Cache::has($key)) {
            $bouncingTime = now();

            $blog = Blog::where('slug', $slug)->first();
            $blog->total_view += 1;
            $blog->save();

            Cache::put($key, $bouncingTime, now()->addMinutes(30));
        }

        return $next($request);
    }
}
