@extends('landing.layout')

@section('title', 'Terms of Service | Miravil Specialised Dental Centre - Mwanza, Tanzania')
@section('meta_description', 'Read the terms of service of Miravil Specialised Dental Centre. Learn about our booking, payment, and service policies.')
@section('meta_keywords', 'Miravil Dental terms of service, dental clinic terms, appointment policy Tanzania')
@section('og_image', asset('images.png'))

@section('content')
<section class="relative py-16 md:py-24 bg-gradient-to-b from-slate-50 to-white overflow-hidden">
    {{-- Decorative background elements --}}
    <div class="absolute top-20 right-10 w-72 h-72 bg-secondary-100/50 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-primary-100/50 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- A4 Paper Card --}}
        <div class="bg-white shadow-2xl rounded-none md:rounded-sm border border-slate-200 p-8 md:p-16 section-reveal" style="min-height: 800px;">
            {{-- Header badge --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-6">
                    <i class="fa-solid fa-file-contract"></i>
                    Legal
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">Terms of Service</h1>
                <p class="text-slate-500">Last updated: July 2026</p>
                <div class="w-24 h-1 bg-gradient-to-r from-secondary-500 to-primary-500 mx-auto mt-6"></div>
            </div>

            {{-- Content --}}
            <div class="prose prose-slate max-w-none space-y-8">
                <div class="section-reveal" style="transition-delay: 100ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-sm flex items-center justify-center text-sm">1</span>
                        Acceptance of Terms
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        By accessing or using the Miravil Specialised Dental Centre website and services, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, please do not use our website or services.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 150ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-sm flex items-center justify-center text-sm">2</span>
                        Appointment Booking
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Online appointment requests are subject to confirmation by our reception team. We reserve the right to reschedule or cancel appointments due to unforeseen circumstances. Patients are encouraged to arrive on time for their scheduled appointments.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 200ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-sm flex items-center justify-center text-sm">3</span>
                        Medical Disclaimer
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Information provided on this website is for general informational purposes only and does not constitute medical advice. Always consult with our qualified dental professionals for diagnosis and treatment recommendations.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 250ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-sm flex items-center justify-center text-sm">4</span>
                        Payment and Fees
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Service fees are displayed on our website where applicable and may be subject to change. Payment is expected at the time of service unless prior arrangements have been made. We accept multiple payment methods at our clinic.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 300ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-sm flex items-center justify-center text-sm">5</span>
                        Limitation of Liability
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Miravil Specialised Dental Centre shall not be liable for any indirect, incidental, or consequential damages arising from the use of our website or services. We strive to provide accurate information but make no warranties of any kind.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 350ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-sm flex items-center justify-center text-sm">6</span>
                        Changes to Terms
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        We may update these Terms of Service from time to time. Continued use of our website after changes constitutes acceptance of the revised terms. For questions, contact us at <a href="mailto:info@miravildental.co.tz" class="text-secondary-600 hover:underline">info@miravildental.co.tz</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
