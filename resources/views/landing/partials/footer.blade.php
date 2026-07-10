<footer class="relative bg-slate-900 text-slate-300 overflow-hidden">
    {{-- Top decorative wave --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-500"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-14">
            {{-- Brand column --}}
            <div class="space-y-6 footer-reveal" style="transition-delay: 0ms;">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="border-2 border-slate-700 bg-white p-0.5 shadow-lg group-hover:border-primary-500 transition-colors">
                        <img src="{{ asset('logo.png') }}" alt="Miravil Dental" class="h-12 w-auto object-contain">
                    </div>
                    <div class="leading-tight">
                        <span class="block text-xl font-extrabold text-white tracking-tight">Miravil</span>
                        <span class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400">Dental Centre</span>
                    </div>
                </a>
                <p class="text-sm leading-relaxed text-slate-400">
                    Miravil Specialised Dental Centre is a modern dental clinic, specialized in advanced diagnostics and treatment of dental and oral disorders.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="social-icon w-10 h-10 bg-slate-800 border border-slate-700 rounded-sm flex items-center justify-center text-slate-300 hover:bg-secondary-600 hover:border-secondary-600 hover:text-white hover:scale-110 hover:rotate-6 transition-all duration-300">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon w-10 h-10 bg-slate-800 border border-slate-700 rounded-sm flex items-center justify-center text-slate-300 hover:bg-secondary-600 hover:border-secondary-600 hover:text-white hover:scale-110 hover:rotate-6 transition-all duration-300">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon w-10 h-10 bg-slate-800 border border-slate-700 rounded-sm flex items-center justify-center text-slate-300 hover:bg-secondary-600 hover:border-secondary-600 hover:text-white hover:scale-110 hover:rotate-6 transition-all duration-300">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a href="#" class="social-icon w-10 h-10 bg-slate-800 border border-slate-700 rounded-sm flex items-center justify-center text-slate-300 hover:bg-secondary-600 hover:border-secondary-600 hover:text-white hover:scale-110 hover:rotate-6 transition-all duration-300">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="footer-reveal" style="animation-delay: 100ms;">
                <h4 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-secondary-500 rounded-sm"></span>
                    Quick Links
                </h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('landing') }}" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-secondary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Home</a></li>
                    <li><a href="{{ route('landing.about') }}" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-secondary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> About Us</a></li>
                    <li><a href="{{ route('landing.services') }}" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-secondary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Services</a></li>
                    <li><a href="{{ route('landing.booking') }}" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-secondary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Book Now</a></li>
                    <li><a href="{{ route('landing.contact') }}" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-secondary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Contact Us</a></li>
                </ul>
            </div>

            {{-- Services --}}
            <div class="footer-reveal" style="animation-delay: 200ms;">
                <h4 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-primary-500 rounded-sm"></span>
                    Our Services
                </h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#services" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-primary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Root Canal</a></li>
                    <li><a href="#services" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-primary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Dental Whitening</a></li>
                    <li><a href="#services" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-primary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Orthodontic Treatment</a></li>
                    <li><a href="#services" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-primary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Prosthodontics</a></li>
                    <li><a href="#services" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-primary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Sealants</a></li>
                    <li><a href="#services" class="footer-link inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"><span class="w-1.5 h-1.5 bg-primary-500 rounded-sm opacity-0 -ml-3.5 link-dot transition-opacity"></span> Fluoride Application</a></li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="footer-reveal" style="transition-delay: 300ms;">
                <h4 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-secondary-500 rounded-sm"></span>
                    Newsletter
                </h4>
                <p class="text-sm mb-4 text-slate-400">Subscribe to get our latest updates & dental care tips.</p>
                <form action="#" method="POST" class="space-y-3">
                    @csrf
                    <div class="relative group">
                        <input type="email" name="email" required placeholder="Your email address" class="newsletter-input w-full px-4 py-3 bg-slate-800 border border-slate-700 text-white placeholder-slate-500 outline-none transition-all duration-300 focus:border-secondary-500 focus:shadow-[0_0_0_3px_rgba(147,51,234,0.15)]">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-secondary-600 to-secondary-700 text-white py-3 rounded-sm font-bold text-sm uppercase tracking-wide hover:from-secondary-700 hover:to-secondary-800 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Subscribe
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-800">
                    <h5 class="text-white font-semibold text-sm mb-2 flex items-center gap-2">
                        <i class="fa-regular fa-clock text-primary-500"></i>
                        Opening Hours
                    </h5>
                    <p class="text-sm text-slate-400">Monday – Sunday, 08:30 am – 05:00 pm</p>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <p class="text-slate-500">&copy; 2024 Miravil Specialised Dental Centre. All rights reserved. Created by MAS Company Ltd.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('landing.privacy') }}" class="footer-bottom-link text-slate-500 hover:text-white transition-colors relative">Privacy Policy</a>
                <a href="{{ route('landing.terms') }}" class="footer-bottom-link text-slate-500 hover:text-white transition-colors relative">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
