<?php

namespace app\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TrailingSlashMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $uri  = $request->getUri();
        $path = $uri->getPath();
        if ($path != '/' && str_ends_with($path, '/')) {
            $uri = $uri->withPath(substr($path, 0, -1));
            if ($request->getMethod() == 'GET') {
                return $response->withRedirect((string)$uri, 301);
            } else {
                return $next($request->withUri($uri), $response);
            }
        }
        return $next($request, $response);
    }
}
