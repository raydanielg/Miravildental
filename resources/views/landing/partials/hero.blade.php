<section id="home" class="relative min-h-[90vh] flex items-center text-white overflow-hidden">
    {{-- Background slideshow --}}
    <div class="absolute inset-0 z-0">
        <div id="hero-slideshow" class="relative w-full h-full">
            <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out opacity-100" style="background-image: url('{{ asset('1411.jpg') }}');"></div>
            <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out opacity-0" style="background-image: url('{{ asset('7678.jpg') }}');"></div>
            <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out opacity-0" style="background-image: url('{{ asset('images.png') }}');"></div>
            <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out opacity-0" style="background-image: url('{{ asset('watoto.png') }}');"></div>
            <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out opacity-0" style="background-image: url('{{ asset('serious-expert-expressing-support-colleague (1).jpg') }}');"></div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-800/85 to-secondary-900/75"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-slate-900/30"></div>
    </div>

    {{-- Floating teeth --}}
    <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden opacity-20">
        <i class="fa-solid fa-tooth floating-tooth text-white" style="font-size: 3rem; top: 15%; left: 10%; animation-duration: 12s;"></i>
        <i class="fa-solid fa-tooth floating-tooth text-white" style="font-size: 2rem; top: 70%; left: 80%; animation-duration: 16s; animation-delay: 2s;"></i>
        <i class="fa-solid fa-tooth floating-tooth text-white" style="font-size: 4rem; top: 40%; left: 90%; animation-duration: 14s; animation-delay: 4s;"></i>
    </div>

    <div class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="max-w-3xl section-reveal">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur px-4 py-2 rounded-sm border border-white/20 mb-6">
                <span class="w-2 h-2 bg-secondary-300 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium uppercase tracking-wide">Trusted Dental Care in Tanzania</span>
            </div>

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6">
                Looking For a<br>
                <span class="text-secondary-300">Dental Specialist?</span><br>
                We Are Here For You
            </h1>

            <p class="text-lg md:text-xl text-white/90 max-w-2xl mb-8">
                Experience breakthrough comprehensive and flexible dental care models at Miravil Specialised Dental Centre.
            </p>

            <div class="flex flex-wrap items-center gap-4 mb-12">
                <a href="{{ route('landing.booking') }}" class="inline-flex items-center justify-center gap-2 bg-white text-primary-800 px-6 md:px-8 py-4 rounded-sm font-bold hover:bg-primary-50 transition shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all">
                    <i class="fa-regular fa-calendar-check"></i>
                    Book Appointment
                </a>
                <a href="tel:+255789483550" class="inline-flex items-center justify-center gap-2 border-2 border-white/40 text-white px-6 md:px-8 py-4 rounded-sm font-semibold hover:bg-white/10 transition">
                    <i class="fa-solid fa-phone"></i>
                    <span class="whitespace-nowrap">+255 789 483 550</span>
                </a>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="flex -space-x-3">
                    <img src="https://ui-avatars.com/api/?name=Doctor+A&bg=16a34a&color=fff" class="w-12 h-12 rounded-full border-2 border-white" alt="Doctor">
                    <img src="https://ui-avatars.com/api/?name=Doctor+B&bg=9333ea&color=fff" class="w-12 h-12 rounded-full border-2 border-white" alt="Doctor">
                    <img src="https://ui-avatars.com/api/?name=Doctor+C&bg=22c55e&color=fff" class="w-12 h-12 rounded-full border-2 border-white" alt="Doctor">
                </div>
                <div>
                    <div class="flex items-center gap-1 text-yellow-300 text-sm mb-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span class="text-white ml-1 font-bold">4.9</span>
                    </div>
                    <p class="text-white/80 text-sm">Trusted by 85,000+ patients</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide indicators --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <button class="hero-dot w-8 h-1.5 rounded-sm bg-white/40 transition-all duration-300" data-index="0"></button>
        <button class="hero-dot w-8 h-1.5 rounded-sm bg-white/40 transition-all duration-300" data-index="1"></button>
        <button class="hero-dot w-8 h-1.5 rounded-sm bg-white/40 transition-all duration-300" data-index="2"></button>
        <button class="hero-dot w-8 h-1.5 rounded-sm bg-white/40 transition-all duration-300" data-index="3"></button>
        <button class="hero-dot w-8 h-1.5 rounded-sm bg-white/40 transition-all duration-300" data-index="4"></button>
    </div>
</section>
