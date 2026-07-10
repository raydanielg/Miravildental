@extends('landing.layout')

@section('title', 'Sitemap - Miravil Specialised Dental Centre')
@section('meta_description', 'Browse all pages and services available on Miravil Specialised Dental Centre website.')
@section('og_image', asset('images.png'))

@section('content')
<section class="relative py-20 md:py-28 bg-slate-50 overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-500"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 section-reveal">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-5">
                <i class="fa-solid fa-sitemap"></i>
                Sitemap
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                Miravil Dental <span class="text-secondary-600">Sitemap</span>
            </h1>
            <p class="text-slate-600 mt-4 text-lg max-w-2xl mx-auto">
                Find every page and dental service on our website quickly and easily.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            {{-- Pages --}}
            <div class="bg-white rounded-sm shadow-xl border border-slate-100 p-8 section-reveal" style="transition-delay: 100ms;">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 text-white rounded-sm flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-lines"></i>
                    </span>
                    Main Pages
                </h2>
                <ul class="space-y-3">
                    @foreach ($pages as $page)
                    <li>
                        <a href="{{ $page['route'] }}" class="group flex items-center justify-between p-3 rounded-sm bg-slate-50 hover:bg-primary-50 border border-slate-100 hover:border-primary-200 transition-all">
                            <span class="font-semibold text-slate-700 group-hover:text-primary-700 transition-colors">{{ $page['title'] }}</span>
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-sm {{ $page['priority'] === 'High' ? 'bg-primary-100 text-primary-700' : ($page['priority'] === 'Medium' ? 'bg-secondary-100 text-secondary-700' : 'bg-slate-100 text-slate-600') }}">{{ $page['priority'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Services --}}
            <div class="bg-white rounded-sm shadow-xl border border-slate-100 p-8 section-reveal" style="transition-delay: 200ms;">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-gradient-to-br from-secondary-500 to-secondary-700 text-white rounded-sm flex items-center justify-center text-lg">
                        <i class="fa-solid fa-tooth"></i>
                    </span>
                    Dental Services
                </h2>
                <ul class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                    @foreach ($services as $service)
                    <li>
                        <a href="{{ route('landing.services') }}#service-{{ $service->id }}" class="group flex items-center gap-3 p-3 rounded-sm bg-slate-50 hover:bg-secondary-50 border border-slate-100 hover:border-secondary-200 transition-all">
                            <span class="w-8 h-8 rounded-sm bg-secondary-100 text-secondary-700 flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-tooth"></i>
                            </span>
                            <span class="font-semibold text-slate-700 group-hover:text-secondary-700 transition-colors">{{ $service->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white px-8 py-3.5 rounded-sm font-bold uppercase tracking-wide text-sm hover:from-primary-700 hover:to-secondary-700 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Home
            </a>
        </div>
    </div>
</section>
@endsection
