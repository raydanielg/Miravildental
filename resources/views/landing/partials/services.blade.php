<section id="services" class="relative py-24 md:py-32 mesh-bg overflow-hidden">
    {{-- Floating orbs --}}
    <div class="floating-orb w-96 h-96 bg-primary-200 -top-32 -right-32" style="animation-duration: 18s;"></div>
    <div class="floating-orb w-80 h-80 bg-secondary-200 bottom-0 left-0" style="animation-duration: 15s; animation-delay: 2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 section-reveal">
            <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-5">
                <i class="fa-solid fa-tooth"></i>
                Our Services
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                Popular Dental<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600">Services</span>
            </h2>
            <p class="text-slate-600 mt-5 text-lg">Benefit For Physical, Mental and Virtual Care</p>
        </div>

        @php
            $row1 = $services->chunk(ceil($services->count() / 2))->first() ?? collect();
            $row2 = $services->chunk(ceil($services->count() / 2))->last() ?? collect();
        @endphp

        {{-- Row 1 - scrolls left --}}
        <div class="relative mb-6 overflow-hidden section-reveal">
            <div class="marquee-row-left flex gap-6 w-max">
                @foreach ([$row1, $row1] as $chunk)
                    @foreach ($chunk as $service)
                        <div class="service-card group w-[280px] sm:w-[320px] bg-white rounded-sm shadow-lg border border-slate-100 overflow-hidden flex-shrink-0">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $service->image ? asset($service->image) : asset('logo.png') }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <h3 class="text-lg font-bold text-white mb-1 drop-shadow-md">{{ $service->name }}</h3>
                                </div>
                            </div>
                            <div class="p-5">
                                <p class="text-slate-600 text-sm mb-4 line-clamp-2 min-h-[42px]">{{ $service->description }}</p>
                                <button type="button" onclick="openBookingSidebar({{ $service->id }}, '{{ addslashes($service->name) }}')" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-2.5 rounded-sm font-bold text-sm uppercase tracking-wide hover:from-primary-700 hover:to-primary-800 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                    Book Now
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- Row 2 - scrolls right --}}
        <div class="relative overflow-hidden section-reveal" style="transition-delay: 150ms;">
            <div class="marquee-row-right flex gap-6 w-max">
                @foreach ([$row2, $row2] as $chunk)
                    @foreach ($chunk as $service)
                        <div class="service-card group w-[280px] sm:w-[320px] bg-white rounded-sm shadow-lg border border-slate-100 overflow-hidden flex-shrink-0">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $service->image ? asset($service->image) : asset('logo.png') }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <h3 class="text-lg font-bold text-white mb-1 drop-shadow-md">{{ $service->name }}</h3>
                                </div>
                            </div>
                            <div class="p-5">
                                <p class="text-slate-600 text-sm mb-4 line-clamp-2 min-h-[42px]">{{ $service->description }}</p>
                                <button type="button" onclick="openBookingSidebar({{ $service->id }}, '{{ addslashes($service->name) }}')" class="w-full bg-gradient-to-r from-secondary-600 to-secondary-700 text-white py-2.5 rounded-sm font-bold text-sm uppercase tracking-wide hover:from-secondary-700 hover:to-secondary-800 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                    Book Now
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Booking Sidebar --}}
<div id="booking-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[480px] bg-white shadow-2xl transform translate-x-full transition-transform duration-500 z-[60] overflow-y-auto">
    <div class="sticky top-0 bg-gradient-to-r from-primary-600 to-secondary-600 text-white px-6 py-5 flex items-center justify-between z-10">
        <div>
            <h3 class="text-xl font-bold">Book Appointment</h3>
            <div class="mt-2 inline-flex items-center gap-2 bg-white/20 backdrop-blur px-3 py-1.5 rounded-sm">
                <i class="fa-solid fa-tooth text-secondary-300"></i>
                <span class="text-sm font-semibold" id="selected-service-name">Select a service</span>
            </div>
        </div>
        <button type="button" onclick="closeBookingSidebar()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-sm flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <div class="p-6 space-y-5">
        <form id="service-booking-form" action="{{ route('landing.appointment.book') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="service_id" id="booking-service-id">

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Your Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="John Doe">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="+255 789 483 550">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-3 rounded-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="john@example.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Appointment Date</label>
                <input type="date" name="appointment_date" required class="w-full px-4 py-3 rounded-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Message / Notes</label>
                <textarea name="message" rows="3" class="w-full px-4 py-3 rounded-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="Tell us about your dental concern..."></textarea>
            </div>

            <div class="bg-primary-50 rounded-sm p-4 border border-primary-100">
                <div class="flex items-center gap-2 text-primary-700 text-sm">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Your booking will be reviewed by our reception team.</span>
                </div>
            </div>

            <button type="submit" id="service-booking-submit" class="w-full bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-4 rounded-sm font-bold text-lg uppercase tracking-wide hover:from-primary-700 hover:to-secondary-700 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fa-regular fa-calendar-check"></i>
                Confirm Booking
            </button>
        </form>
    </div>
</div>

{{-- Sidebar overlay --}}
<div id="booking-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[55] hidden opacity-0 transition-opacity duration-300" onclick="closeBookingSidebar()"></div>
