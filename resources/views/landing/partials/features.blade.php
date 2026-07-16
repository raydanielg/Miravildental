<section class="relative py-24 md:py-32 mesh-bg overflow-hidden">
    {{-- Dots pattern background --}}
    <div class="absolute inset-0 opacity-[0.15]" style="background-image: radial-gradient(#22c55e 1.5px, transparent 1.5px), radial-gradient(#a855f7 1.5px, transparent 1.5px); background-size: 30px 30px; background-position: 0 0, 15px 15px;"></div>

    {{-- Connecting lines decoration --}}
    <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-20" xmlns="http://www.w3.org/2000/svg">
        <line x1="15%" y1="30%" x2="35%" y2="45%" stroke="#22c55e" stroke-width="1" stroke-dasharray="6 4" class="feature-line"/>
        <line x1="35%" y1="45%" x2="55%" y2="35%" stroke="#a855f7" stroke-width="1" stroke-dasharray="6 4" class="feature-line"/>
        <line x1="55%" y1="35%" x2="75%" y2="50%" stroke="#22c55e" stroke-width="1" stroke-dasharray="6 4" class="feature-line"/>
        <line x1="25%" y1="70%" x2="45%" y2="60%" stroke="#a855f7" stroke-width="1" stroke-dasharray="6 4" class="feature-line"/>
        <line x1="45%" y1="60%" x2="65%" y2="72%" stroke="#22c55e" stroke-width="1" stroke-dasharray="6 4" class="feature-line"/>
        <line x1="65%" y1="72%" x2="85%" y2="65%" stroke="#a855f7" stroke-width="1" stroke-dasharray="6 4" class="feature-line"/>
    </svg>

    {{-- Floating orbs --}}
    <div class="floating-orb w-72 h-72 bg-primary-300 top-10 left-10" style="animation-duration: 12s;"></div>
    <div class="floating-orb w-96 h-96 bg-secondary-300 top-1/2 right-0" style="animation-duration: 18s; animation-delay: 2s;"></div>
    <div class="floating-orb w-64 h-64 bg-primary-300 bottom-10 left-1/3" style="animation-duration: 15s; animation-delay: 4s;"></div>
    <div class="floating-orb w-80 h-80 bg-secondary-300 bottom-0 right-1/4" style="animation-duration: 14s; animation-delay: 1s;"></div>

    {{-- Floating teeth --}}
    <i class="fa-solid fa-tooth floating-tooth text-primary-300 text-5xl top-[15%] left-[8%]" style="animation-duration: 10s;"></i>
    <i class="fa-solid fa-tooth floating-tooth text-secondary-300 text-4xl top-[20%] right-[12%]" style="animation-duration: 14s; animation-delay: 1s;"></i>
    <i class="fa-solid fa-tooth floating-tooth text-primary-300 text-6xl bottom-[20%] left-[5%]" style="animation-duration: 12s; animation-delay: 2s;"></i>
    <i class="fa-solid fa-tooth floating-tooth text-secondary-300 text-5xl bottom-[15%] right-[8%]" style="animation-duration: 16s; animation-delay: 3s;"></i>
    <i class="fa-solid fa-tooth floating-tooth text-primary-300 text-3xl top-[60%] left-[2%]" style="animation-duration: 11s; animation-delay: 4s;"></i>
    <i class="fa-solid fa-tooth floating-tooth text-secondary-300 text-4xl top-[55%] right-[3%]" style="animation-duration: 13s; animation-delay: 5s;"></i>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20 section-reveal">
            <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-5">
                <i class="fa-solid fa-star"></i>
                Why Choose Us
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mt-3 leading-tight">
                Why Choose<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600">Miravil Dental</span>
            </h2>
            <p class="text-slate-600 mt-5 text-lg">Breakthrough in Comprehensive, Flexible Dental Care Models</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
            {{-- Feature 1 --}}
            <div class="feature-card group bg-white rounded-sm shadow-lg border border-slate-100 p-4 md:p-8 relative overflow-hidden section-reveal" style="transition-delay: 0ms;">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-primary-500/10 to-primary-700/10 rounded-sm flex items-center justify-center shadow-md shadow-primary-500/15 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <img src="{{ asset('implanticon.png') }}" alt="Implant Service" class="w-8 h-8 md:w-12 md:h-12 object-contain">
                        </div>
                        <span class="text-3xl md:text-5xl font-black text-slate-100 group-hover:text-primary-100 transition-colors duration-300">01</span>
                    </div>
                    <h3 class="text-base md:text-xl font-bold text-slate-900 mb-2 md:mb-3 group-hover:text-primary-600 transition-colors">Implant Service</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Restore missing teeth with durable, natural-looking dental implants designed for long-term function and confidence.</p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- Feature 2 --}}
            <div class="feature-card group bg-white rounded-sm shadow-lg border border-slate-100 p-4 md:p-8 relative overflow-hidden section-reveal" style="transition-delay: 100ms;">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-secondary-500/10 to-secondary-700/10 rounded-sm flex items-center justify-center shadow-md shadow-secondary-500/15 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <img src="{{ asset('teethwhitening.png') }}" alt="Teeth Whitening" class="w-8 h-8 md:w-12 md:h-12 object-contain">
                        </div>
                        <span class="text-3xl md:text-5xl font-black text-slate-100 group-hover:text-secondary-100 transition-colors duration-300">02</span>
                    </div>
                    <h3 class="text-base md:text-xl font-bold text-slate-900 mb-2 md:mb-3 group-hover:text-secondary-600 transition-colors">Teeth Whitening</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">A bright smile boosts confidence. Our safe whitening treatments give you a healthier, more radiant look.</p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-secondary-500 to-primary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- Feature 3 --}}
            <div class="feature-card group bg-white rounded-sm shadow-lg border border-slate-100 p-4 md:p-8 relative overflow-hidden section-reveal" style="transition-delay: 200ms;">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-primary-500/10 to-primary-700/10 rounded-sm flex items-center justify-center shadow-md shadow-primary-500/15 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <img src="{{ asset('cosmeticdentisry.png') }}" alt="Cosmetic Dentistry" class="w-8 h-8 md:w-12 md:h-12 object-contain">
                        </div>
                        <span class="text-3xl md:text-5xl font-black text-slate-100 group-hover:text-primary-100 transition-colors duration-300">03</span>
                    </div>
                    <h3 class="text-base md:text-xl font-bold text-slate-900 mb-2 md:mb-3 group-hover:text-primary-600 transition-colors">Cosmetic Dentistry</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">From veneers to bonding, we enhance your smile using modern cosmetic techniques tailored to your face.</p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- Feature 4 --}}
            <div class="feature-card group bg-white rounded-sm shadow-lg border border-slate-100 p-4 md:p-8 relative overflow-hidden section-reveal" style="transition-delay: 0ms;">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-secondary-500/10 to-secondary-700/10 rounded-sm flex items-center justify-center shadow-md shadow-secondary-500/15 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <img src="{{ asset('expertdoctor.png') }}" alt="Expert Doctors" class="w-8 h-8 md:w-12 md:h-12 object-contain">
                        </div>
                        <span class="text-3xl md:text-5xl font-black text-slate-100 group-hover:text-secondary-100 transition-colors duration-300">04</span>
                    </div>
                    <h3 class="text-base md:text-xl font-bold text-slate-900 mb-2 md:mb-3 group-hover:text-secondary-600 transition-colors">Expert Doctors</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Our team of 12+ professional dentists brings years of experience and compassionate care to every patient.</p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-secondary-500 to-primary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- Feature 5 --}}
            <div class="feature-card group bg-white rounded-sm shadow-lg border border-slate-100 p-4 md:p-8 relative overflow-hidden section-reveal" style="transition-delay: 100ms;">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-primary-500/10 to-primary-700/10 rounded-sm flex items-center justify-center shadow-md shadow-primary-500/15 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <img src="{{ asset('modernequipment.png') }}" alt="Modern Equipment" class="w-8 h-8 md:w-12 md:h-12 object-contain">
                        </div>
                        <span class="text-3xl md:text-5xl font-black text-slate-100 group-hover:text-primary-100 transition-colors duration-300">05</span>
                    </div>
                    <h3 class="text-base md:text-xl font-bold text-slate-900 mb-2 md:mb-3 group-hover:text-primary-600 transition-colors">Modern Equipment</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">High-end dental technology and a luxurious, comfortable environment for the best treatment experience.</p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- Feature 6 --}}
            <div class="feature-card group bg-white rounded-sm shadow-lg border border-slate-100 p-4 md:p-8 relative overflow-hidden section-reveal" style="transition-delay: 200ms;">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-secondary-500/10 to-secondary-700/10 rounded-sm flex items-center justify-center shadow-md shadow-secondary-500/15 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <img src="{{ asset('oethodontic.png') }}" alt="Orthodontic Service" class="w-8 h-8 md:w-12 md:h-12 object-contain">
                        </div>
                        <span class="text-3xl md:text-5xl font-black text-slate-100 group-hover:text-secondary-100 transition-colors duration-300">06</span>
                    </div>
                    <h3 class="text-base md:text-xl font-bold text-slate-900 mb-2 md:mb-3 group-hover:text-secondary-600 transition-colors">Orthodontic Service</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Straighten misaligned teeth with braces and aligners for a healthier bite and a beautifully balanced smile.</p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-secondary-500 to-primary-500 group-hover:w-full transition-all duration-500"></div>
            </div>
        </div>
    </div>
</section>
