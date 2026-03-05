<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! $request->isMethodCacheable() || $response->getStatusCode() !== 200) {
            return $response;
        }

        $path = $request->path();
        $isStaticAsset = preg_match('/\.(?:css|js|png|jpg|jpeg|gif|svg|webp|ico|woff2?|ttf)$/i', $path) === 1;

        if ($isStaticAsset) {
            $response->headers->set('Cache-Control', 'public, max-age=604800, immutable');
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if (str_contains(strtolower($contentType), 'text/html')) {
            if ($request->user()) {
                $response->headers->set('Cache-Control', 'private, no-store');
            } else {
                $response->headers->set('Cache-Control', 'public, max-age=120, stale-while-revalidate=60');
            }
        }

        return $response;
    }
}
