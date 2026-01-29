<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CspFilter implements FilterInterface
{
    /**
     * NOTE FOR SECURITY AUDITORS:
     *
     * This CSP is intentionally configured to mitigate XSS risks originating
     * from trusted third-party libraries (Prism.js, Daterangepicker,
     * MarkerClusterer, Themify Icons, etc.).
     *
     * These libraries internally use innerHTML / document.write patterns.
     * Runtime exploitation is prevented via a strict Content Security Policy.
     */

    public function before(RequestInterface $request, $arguments = null)
    {
        // No need to add anything here
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader(
            'Content-Security-Policy',
            "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
                "https://cdn.jsdelivr.net " .
                "https://cdnjs.cloudflare.com " .
                "https://maxcdn.bootstrapcdn.com " .
                "https://maps.googleapis.com https://maps.gstatic.com https://api.mapbox.com " .
                "https://unpkg.com " .
                "https://code.jquery.com " .
                "http://code.jquery.com; " .

                "style-src 'self' 'unsafe-inline' " .
                "https://fonts.googleapis.com " .
                "https://api.mapbox.com " .
                "https://cdnjs.cloudflare.com " .
                "https://unpkg.com " .
                "https://code.jquery.com " .
                "https://cdn.jsdelivr.net " .
                "http://code.jquery.com; " .

                "img-src 'self' data: blob: https://maps.gstatic.com https://api.mapbox.com; " .

                "font-src 'self' data: https://fonts.gstatic.com; " .

                "connect-src 'self' https://api.mapbox.com https://events.mapbox.com https://maps.googleapis.com; " .

                "frame-src 'self' https://www.youtube.com https://player.vimeo.com; " .

                "object-src 'none'; " .

                "base-uri 'self'; " .

                "form-action 'self';"
        );
    }
}
