<header>
    {{-- Top header --}}
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white py-2.5 border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 sm:gap-6">
                    <a href="tel:+255753188852" class="flex items-center gap-2 text-slate-300 hover:text-white transition">
                        <i class="fa-solid fa-phone text-primary-400"></i>
                        <span>+255 753 188 852</span>
                    </a>
                    <a href="mailto:info@miravildental.co.tz" class="flex items-center gap-2 text-slate-300 hover:text-white transition">
                        <i class="fa-solid fa-envelope text-secondary-400"></i>
                        <span>info@miravildental.co.tz</span>
                    </a>
                    <span class="hidden md:flex items-center gap-2 text-slate-300">
                        <i class="fa-regular fa-clock text-primary-400"></i>
                        <span>Mon - Sun: 08:30 AM - 05:00 PM</span>
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline text-slate-400">Follow us:</span>
                    <div class="flex items-center gap-2">
                        <a href="#" class="w-7 h-7 rounded-sm bg-slate-700/50 hover:bg-primary-600 flex items-center justify-center text-slate-300 hover:text-white transition">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <a href="#" class="w-7 h-7 rounded-sm bg-slate-700/50 hover:bg-secondary-600 flex items-center justify-center text-slate-300 hover:text-white transition">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                        <a href="#" class="w-7 h-7 rounded-sm bg-slate-700/50 hover:bg-primary-600 flex items-center justify-center text-slate-300 hover:text-white transition">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav id="main-nav" class="sticky top-0 z-50 bg-white border-b border-slate-100 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="{{ route('landing') }}" class="flex items-center group">
                    <div class="relative overflow-hidden border-2 border-slate-100 bg-white p-0.5 shadow-sm group-hover:border-primary-500 transition">
                        <img src="{{ asset('logo.png') }}" alt="Miravil Dental" class="h-11 w-auto object-contain">
                    </div>
                </a>

                {{-- Desktop menu --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('landing') }}" class="px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm transition {{ request()->routeIs('landing') ? 'text-primary-600 bg-slate-50' : '' }}">Home</a>
                    <a href="{{ route('landing.about') }}" class="px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm transition {{ request()->routeIs('landing.about') ? 'text-primary-600 bg-slate-50' : '' }}">About Us</a>
                    <a href="{{ route('landing.services') }}" class="px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm transition {{ request()->routeIs('landing.services') ? 'text-primary-600 bg-slate-50' : '' }}">Services</a>
                    <a href="{{ route('landing.booking') }}" class="px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm transition {{ request()->routeIs('landing.booking') ? 'text-primary-600 bg-slate-50' : '' }}">Book Now</a>
                    <a href="{{ route('landing.contact') }}" class="px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm transition {{ request()->routeIs('landing.contact') ? 'text-primary-600 bg-slate-50' : '' }}">Contact</a>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hidden md:inline-flex text-sm font-semibold text-slate-600 hover:text-primary-600 transition">
                        Login
                    </a>
                    <a href="{{ route('landing.booking') }}" class="hidden md:inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white px-5 py-2.5 rounded-sm font-bold text-sm uppercase tracking-wide hover:from-primary-700 hover:to-primary-800 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <i class="fa-regular fa-calendar-check"></i>
                        Book Now
                    </a>
                    <button id="mobile-menu-btn" class="lg:hidden p-2 text-slate-700 hover:text-primary-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white">
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('landing') }}" class="block px-3 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm {{ request()->routeIs('landing') ? 'text-primary-600 bg-slate-50' : '' }}">Home</a>
                <a href="{{ route('landing.about') }}" class="block px-3 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm {{ request()->routeIs('landing.about') ? 'text-primary-600 bg-slate-50' : '' }}">About Us</a>
                <a href="{{ route('landing.services') }}" class="block px-3 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm {{ request()->routeIs('landing.services') ? 'text-primary-600 bg-slate-50' : '' }}">Services</a>
                <a href="{{ route('landing.booking') }}" class="block px-3 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm {{ request()->routeIs('landing.booking') ? 'text-primary-600 bg-slate-50' : '' }}">Book Now</a>
                <a href="{{ route('landing.contact') }}" class="block px-3 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-sm {{ request()->routeIs('landing.contact') ? 'text-primary-600 bg-slate-50' : '' }}">Contact</a>
                <div class="pt-3 border-t border-slate-100 mt-3">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-semibold uppercase tracking-wide text-slate-700 hover:text-primary-600">Login</a>
                    <a href="{{ route('landing.booking') }}" class="block mt-2 text-center px-3 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold text-sm uppercase tracking-wide rounded-sm hover:from-primary-700 hover:to-primary-800 transition">
                        Book Appointment
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
