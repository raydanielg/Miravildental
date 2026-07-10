<section id="about" class="relative py-24 md:py-32 mesh-bg overflow-hidden">
    {{-- Floating orbs --}}
    <div class="floating-orb w-80 h-80 bg-primary-200 -top-20 -left-20" style="animation-duration: 16s;"></div>
    <div class="floating-orb w-72 h-72 bg-secondary-200 bottom-0 right-0" style="animation-duration: 14s; animation-delay: 3s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center max-w-3xl mx-auto mb-16 section-reveal">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-5">
                <i class="fa-solid fa-hospital"></i>
                About Our Clinic
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                Miravil Specialised<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600">Dental Centre</span>
            </h2>
            <p class="text-slate-600 mt-5 text-lg">Experienced in Dental Services with Modern Facilities</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Image slideshow --}}
            <div class="relative section-reveal" style="transition-delay: 100ms;">
                <div class="absolute -top-6 -left-6 w-full h-full border-2 border-primary-200 rounded-sm -z-10"></div>
                <div class="absolute -bottom-6 -right-6 w-full h-full border-2 border-secondary-200 rounded-sm -z-10"></div>

                <div class="relative rounded-sm shadow-2xl overflow-hidden h-[450px] md:h-[520px]" id="about-slideshow">
                    <div class="about-slide absolute inset-0 bg-cover bg-center transition-all duration-1000 ease-in-out opacity-100 scale-100" style="background-image: url('{{ asset('serious-expert-expressing-support-colleague (1).jpg') }}');"></div>
                    <div class="about-slide absolute inset-0 bg-cover bg-center transition-all duration-1000 ease-in-out opacity-0 scale-110" style="background-image: url('{{ asset('1411.jpg') }}');"></div>
                    <div class="about-slide absolute inset-0 bg-cover bg-center transition-all duration-1000 ease-in-out opacity-0 scale-110" style="background-image: url('{{ asset('7678.jpg') }}');"></div>
                    <div class="about-slide absolute inset-0 bg-cover bg-center transition-all duration-1000 ease-in-out opacity-0 scale-110" style="background-image: url('{{ asset('watoto.png') }}');"></div>

                    {{-- Overlay gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>

                    {{-- Mini indicators --}}
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        <button class="about-dot w-6 h-1.5 rounded-sm bg-white/50 transition-all duration-300" data-index="0"></button>
                        <button class="about-dot w-6 h-1.5 rounded-sm bg-white/50 transition-all duration-300" data-index="1"></button>
                        <button class="about-dot w-6 h-1.5 rounded-sm bg-white/50 transition-all duration-300" data-index="2"></button>
                        <button class="about-dot w-6 h-1.5 rounded-sm bg-white/50 transition-all duration-300" data-index="3"></button>
                    </div>
                </div>

                {{-- Floating stats cards --}}
                <div class="absolute -bottom-8 right-2 md:-right-4 lg:right-8 bg-white rounded-sm shadow-xl p-4 md:p-5 z-20 max-w-[160px] md:max-w-[200px] section-reveal" style="transition-delay: 300ms;">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-sm flex items-center justify-center text-white text-xl shadow-lg">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900">12+</p>
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Pro Doctors</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -top-4 right-2 md:-right-4 lg:right-8 bg-white rounded-sm shadow-xl p-3 md:p-4 z-20 section-reveal" style="transition-delay: 400ms;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-secondary-500 to-secondary-700 rounded-sm flex items-center justify-center text-white shadow-lg">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-slate-900">85K+</p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">Happy Patients</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="space-y-6 section-reveal" style="transition-delay: 200ms;">
                <h3 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight">
                    A Modern Clinic Dedicated to Your
                    <span class="text-secondary-600">Healthy Smile</span>
                </h3>

                <p class="text-slate-600 text-lg leading-relaxed">
                    Miravil Specialised Dental Centre is a modern dental clinic, specialized in advanced diagnostics and treatment of dental and oral disorders.
                </p>

                <p class="text-slate-600 leading-relaxed">
                    We offer comprehensive services from all fields of dentistry. In addition to high-end dental equipment, all services are provided in a comfortable, luxurious environment. New patients are welcomed with a complimentary oral health consultation.
                </p>

                <div class="grid grid-cols-2 gap-3 md:gap-4 pt-2">
                    <div class="flex items-start gap-3 p-3 bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-8 h-8 bg-primary-100 text-primary-700 rounded-sm flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                        <span class="text-slate-700 font-medium text-sm">Advanced Diagnostics</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-8 h-8 bg-primary-100 text-primary-700 rounded-sm flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                        <span class="text-slate-700 font-medium text-sm">Luxurious Environment</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-700 rounded-sm flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                        <span class="text-slate-700 font-medium text-sm">Complimentary Consultation</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-700 rounded-sm flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                        <span class="text-slate-700 font-medium text-sm">Expert Dental Team</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-8 h-8 bg-primary-100 text-primary-700 rounded-sm flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                        <span class="text-slate-700 font-medium text-sm">Digital X-Rays</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-700 rounded-sm flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                        <span class="text-slate-700 font-medium text-sm">Same-Day Treatments</span>
                    </div>
                </div>

                <a href="#services" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white px-8 py-3.5 rounded-sm font-bold uppercase tracking-wide text-sm hover:from-primary-700 hover:to-secondary-700 hover:shadow-lg hover:-translate-y-0.5 transition-all mt-4">
                    Explore Our Services
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
