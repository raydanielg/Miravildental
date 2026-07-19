<section id="insurance" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12 section-reveal">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-5">
                <i class="fa-solid fa-shield-heart"></i>
                Insurance Partners
            </div>
            <h2 class="text-2xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                We Accept <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600">Major Insurance</span> Providers
            </h2>
            <p class="text-slate-600 mt-4 text-base md:text-lg">
                Tunapokea pia wagonjwa kutoka kwa bima mbalimbali. Ziara yako ya meno inaweza kufunikwa na bima yako.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-8 max-w-5xl mx-auto">
            {{-- NHIF --}}
            <div class="bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 p-8 flex flex-col items-center justify-center group hover:-translate-y-1 section-reveal" style="transition-delay: 100ms;">
                <div class="h-24 flex items-center justify-center mb-4">
                    <img src="{{ asset('bg_nhif_logo_black.png') }}" alt="NHIF" class="max-h-20 max-w-[180px] object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">NHIF</h3>
                <p class="text-xs text-slate-500 mt-1 text-center">National Health Insurance Fund</p>
            </div>

            {{-- Britam --}}
            <div class="bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 p-8 flex flex-col items-center justify-center group hover:-translate-y-1 section-reveal" style="transition-delay: 200ms;">
                <div class="h-24 flex items-center justify-center mb-4">
                    <img src="{{ asset('britam.png') }}" alt="Britam" class="max-h-20 max-w-[180px] object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Britam</h3>
                <p class="text-xs text-slate-500 mt-1 text-center">British-American Insurance</p>
            </div>

            {{-- Jubilee --}}
            <div class="bg-white rounded-sm border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 p-8 flex flex-col items-center justify-center group hover:-translate-y-1 section-reveal" style="transition-delay: 300ms;">
                <div class="h-24 flex items-center justify-center mb-4">
                    <img src="{{ asset('jubilee-group-logo.png') }}" alt="Jubilee" class="max-h-20 max-w-[180px] object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Jubilee</h3>
                <p class="text-xs text-slate-500 mt-1 text-center">Jubilee Insurance Group</p>
            </div>
        </div>

        <div class="text-center mt-10">
            <p class="text-sm text-slate-500">
                <i class="fa-solid fa-circle-info text-primary-500 mr-1"></i>
                Wasiliana nasi kwa maelezo zaidi kuhusu bima inayokubalika.
            </p>
        </div>
    </div>
</section>
