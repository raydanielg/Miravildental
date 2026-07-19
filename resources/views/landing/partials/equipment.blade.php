<section id="equipment" class="py-20 md:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 section-reveal">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-5">
                <i class="fa-solid fa-microscope"></i>
                Modern Facilities
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                Advanced <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600">Dental Technology</span>
            </h2>
            <p class="text-slate-600 mt-5 text-lg">
                Tunatumia vifaa vya kisasa na teknolojia ya hali ya juu kuhakikisha matibabu bora na salama kwa wagonjwa wetu.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            {{-- Dental Laser --}}
            <div class="group relative rounded-sm overflow-hidden shadow-lg border border-slate-100 hover:shadow-2xl transition-all duration-500 section-reveal" style="transition-delay: 100ms;">
                <div class="relative h-64 md:h-72 overflow-hidden">
                    <img src="{{ asset('Dental leaser.jpeg') }}" alt="Dental Laser" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 via-primary-900/20 to-transparent"></div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-5 md:p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-sm flex items-center justify-center text-white">
                            <i class="fa-solid fa-wave-square"></i>
                        </span>
                        <h3 class="text-lg md:text-xl font-bold text-white">Dental Laser</h3>
                    </div>
                    <p class="text-white/80 text-sm">Teknolojia ya laser kwa matibabu ya sauti, yabisi na upasuaji wa tishu bila maumivu.</p>
                </div>
                <div class="absolute top-0 left-0 w-0 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- International Scanner --}}
            <div class="group relative rounded-sm overflow-hidden shadow-lg border border-slate-100 hover:shadow-2xl transition-all duration-500 section-reveal" style="transition-delay: 200ms;">
                <div class="relative h-64 md:h-72 overflow-hidden">
                    <img src="{{ asset('International scanner.jpeg') }}" alt="International Scanner" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-secondary-900/80 via-secondary-900/20 to-transparent"></div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-5 md:p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-sm flex items-center justify-center text-white">
                            <i class="fa-solid fa-satellite-dish"></i>
                        </span>
                        <h3 class="text-lg md:text-xl font-bold text-white">International Scanner</h3>
                    </div>
                    <p class="text-white/80 text-sm">Kichunguzi cha kimataifa kinachotoa picha za tatu za kinywa kwa usahihi wa hali ya juu.</p>
                </div>
                <div class="absolute top-0 left-0 w-0 h-1 bg-gradient-to-r from-secondary-500 to-primary-500 group-hover:w-full transition-all duration-500"></div>
            </div>

            {{-- Modern Implant Set --}}
            <div class="group relative rounded-sm overflow-hidden shadow-lg border border-slate-100 hover:shadow-2xl transition-all duration-500 section-reveal" style="transition-delay: 300ms;">
                <div class="relative h-64 md:h-72 overflow-hidden">
                    <img src="{{ asset('Modern implant set.jpeg') }}" alt="Modern Implant Set" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 via-primary-900/20 to-transparent"></div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-5 md:p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-sm flex items-center justify-center text-white">
                            <i class="fa-solid fa-tooth"></i>
                        </span>
                        <h3 class="text-lg md:text-xl font-bold text-white">Modern Implant Set</h3>
                    </div>
                    <p class="text-white/80 text-sm">Seti ya kisasa ya implants ya kurejesha meno kwa udumu na muonekano wa asili.</p>
                </div>
                <div class="absolute top-0 left-0 w-0 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 group-hover:w-full transition-all duration-500"></div>
            </div>
        </div>
    </div>
</section>
