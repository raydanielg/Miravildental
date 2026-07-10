<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function xml(): Response
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $lastMod = Carbon::now()->toDateString();

        $urls = [
            [
                'loc' => route('landing'),
                'lastmod' => $lastMod,
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('landing.about'),
                'lastmod' => $lastMod,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('landing.services'),
                'lastmod' => $lastMod,
                'changefreq' => 'monthly',
                'priority' => '0.9',
            ],
            [
                'loc' => route('landing.booking'),
                'lastmod' => $lastMod,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('landing.contact'),
                'lastmod' => $lastMod,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('landing.privacy'),
                'lastmod' => $lastMod,
                'changefreq' => 'yearly',
                'priority' => '0.5',
            ],
            [
                'loc' => route('landing.terms'),
                'lastmod' => $lastMod,
                'changefreq' => 'yearly',
                'priority' => '0.5',
            ],
        ];

        foreach ($services as $service) {
            $urls[] = [
                'loc' => route('landing.services') . '#service-' . $service->id,
                'lastmod' => $service->updated_at?->toDateString() ?? $lastMod,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        return response()->view('sitemap.xml', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function html()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        $pages = [
            ['title' => 'Home', 'route' => route('landing'), 'priority' => 'High'],
            ['title' => 'About Us', 'route' => route('landing.about'), 'priority' => 'High'],
            ['title' => 'Our Services', 'route' => route('landing.services'), 'priority' => 'High'],
            ['title' => 'Book Now', 'route' => route('landing.booking'), 'priority' => 'High'],
            ['title' => 'Contact Us', 'route' => route('landing.contact'), 'priority' => 'Medium'],
            ['title' => 'Privacy Policy', 'route' => route('landing.privacy'), 'priority' => 'Low'],
            ['title' => 'Terms of Service', 'route' => route('landing.terms'), 'priority' => 'Low'],
        ];

        return view('sitemap.html', compact('pages', 'services'));
    }

    public function rss(): Response
    {
        $services = Service::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $feed = [
            'title' => 'Miravil Specialised Dental Centre - Latest Services',
            'link' => route('landing'),
            'description' => 'Stay updated with the latest dental services and care tips from Miravil Specialised Dental Centre in Mwanza, Tanzania.',
            'lastBuildDate' => Carbon::now()->toRfc2822String(),
            'items' => [],
        ];

        foreach ($services as $service) {
            $feed['items'][] = [
                'title' => $service->name,
                'link' => route('landing.services') . '#service-' . $service->id,
                'description' => $service->description ?? 'Dental service at Miravil Specialised Dental Centre',
                'pubDate' => $service->created_at?->toRfc2822String() ?? Carbon::now()->toRfc2822String(),
                'guid' => $service->id,
            ];
        }

        return response()->view('sitemap.rss', compact('feed'))
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }
}
