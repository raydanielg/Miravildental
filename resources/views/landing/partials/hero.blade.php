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

            <div class="flex flex-nowrap items-center justify-start gap-3 md:gap-4 mb-12 overflow-x-auto pb-2">
                <a href="{{ route('landing.booking') }}" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-white to-primary-50 text-primary-800 px-4 md:px-8 py-3.5 md:py-4 rounded-sm font-bold text-sm md:text-base shadow-xl shadow-primary-900/20 hover:shadow-2xl hover:shadow-primary-900/30 hover:-translate-y-1 transition-all duration-300 flex-shrink-0">
                    <span class="w-8 h-8 md:w-9 md:h-9 bg-primary-600 text-white rounded-full flex items-center justify-center group-hover:bg-primary-700 transition-colors">
                        <i class="fa-regular fa-calendar-check text-sm"></i>
                    </span>
                    <span class="whitespace-nowrap">Book Appointment</span>
                </a>
                <a href="tel:+255789483550" class="group inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm border-2 border-white/50 text-white px-4 md:px-8 py-3.5 md:py-4 rounded-sm font-semibold text-sm md:text-base hover:bg-white/20 hover:border-white/80 hover:-translate-y-1 transition-all duration-300 flex-shrink-0">
                    <span class="w-8 h-8 md:w-9 md:h-9 bg-white/20 text-white rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                        <i class="fa-solid fa-phone text-sm"></i>
                    </span>
                    <span class="whitespace-nowrap">+255 789 483 550</span>
                </a>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="flex -space-x-3 avatar-group">
                    <img data-avatar="0" src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=100&h=100&q=80" class="w-12 h-12 md:w-14 md:h-14 rounded-full border-2 border-white object-cover shadow-lg hover:scale-110 hover:z-10 transition-all duration-300" alt="Doctor">
                    <img data-avatar="1" src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=100&h=100&q=80" class="w-12 h-12 md:w-14 md:h-14 rounded-full border-2 border-white object-cover shadow-lg hover:scale-110 hover:z-10 transition-all duration-300" alt="Doctor">
                    <img data-avatar="2" src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=100&h=100&q=80" class="w-12 h-12 md:w-14 md:h-14 rounded-full border-2 border-white object-cover shadow-lg hover:scale-110 hover:z-10 transition-all duration-300" alt="Doctor">
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

@push('scripts')
<script>
    (function() {
        const avatarSets = [
            [
                'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=100&h=100&q=80',
                'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=100&h=100&q=80',
                'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=100&h=100&q=80'
            ],
            [
                'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=100&h=100&q=80',
                'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=100&h=100&q=80',
                'https://images.unsplash.com/photo-1614608682850-e0d6d316b166?auto=format&fit=crop&w=100&h=100&q=80'
            ],
            [
                'https://images.unsplash.com/photo-1651008376811-b90baee60c1f?auto=format&fit=crop&w=100&h=100&q=80',
                'https://images.unsplash.com/photo-1582750433449-648ed127bb57?auto=format&fit=crop&w=100&h=100&q=80',
                'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=100&h=100&q=80'
            ]
        ];

        const avatars = document.querySelectorAll('[data-avatar]');
        if (avatars.length === 0) return;

        let currentSet = 0;

        setInterval(() => {
            currentSet = (currentSet + 1) % avatarSets.length;
            avatars.forEach((img, index) => {
                img.style.opacity = '0.5';
                img.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    img.src = avatarSets[currentSet][index];
                    img.style.opacity = '1';
                    img.style.transform = 'scale(1)';
                }, 250);
            });
        }, 4000);
    })();
</script>
@endpush
