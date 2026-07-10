@extends('landing.layout')

@section('title', 'Privacy Policy - Miravil Specialised Dental Centre')

@section('content')
<section class="relative py-16 md:py-24 bg-gradient-to-b from-slate-50 to-white overflow-hidden">
    {{-- Decorative background elements --}}
    <div class="absolute top-20 left-10 w-72 h-72 bg-primary-100/50 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-secondary-100/50 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- A4 Paper Card --}}
        <div class="bg-white shadow-2xl rounded-none md:rounded-sm border border-slate-200 p-8 md:p-16 section-reveal" style="min-height: 800px;">
            {{-- Header badge --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-sm font-semibold text-sm uppercase tracking-wider mb-6">
                    <i class="fa-solid fa-shield-halved"></i>
                    Legal
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">Privacy Policy</h1>
                <p class="text-slate-500">Last updated: July 2026</p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-6"></div>
            </div>

            {{-- Content --}}
            <div class="prose prose-slate max-w-none space-y-8">
                <div class="section-reveal" style="transition-delay: 100ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-sm flex items-center justify-center text-sm">1</span>
                        Introduction
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Miravil Specialised Dental Centre ("we", "our", "us") respects your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, store, and safeguard your data when you use our website and services.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 150ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-sm flex items-center justify-center text-sm">2</span>
                        Information We Collect
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        We may collect personal details such as your name, phone number, email address, appointment history, and dental health information. This data is collected when you book appointments, contact us, or register as a patient.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 200ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-sm flex items-center justify-center text-sm">3</span>
                        How We Use Your Information
                    </h2>
                    <ul class="space-y-2 text-slate-600">
                        <li class="flex items-start gap-2"><i class="fa-solid fa-check text-primary-600 mt-1 text-sm"></i><span>To schedule and manage your dental appointments.</span></li>
                        <li class="flex items-start gap-2"><i class="fa-solid fa-check text-primary-600 mt-1 text-sm"></i><span>To communicate appointment reminders and confirmations.</span></li>
                        <li class="flex items-start gap-2"><i class="fa-solid fa-check text-primary-600 mt-1 text-sm"></i><span>To maintain accurate medical and dental records.</span></li>
                        <li class="flex items-start gap-2"><i class="fa-solid fa-check text-primary-600 mt-1 text-sm"></i><span>To improve our services and patient experience.</span></li>
                    </ul>
                </div>

                <div class="section-reveal" style="transition-delay: 250ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-sm flex items-center justify-center text-sm">4</span>
                        Data Security
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        We implement appropriate technical and organizational measures to protect your data against unauthorized access, alteration, disclosure, or destruction. Access to patient records is restricted to authorized staff only.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 300ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-sm flex items-center justify-center text-sm">5</span>
                        Your Rights
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        You have the right to access, correct, or request deletion of your personal information. To exercise these rights, please contact us using the details provided on our website.
                    </p>
                </div>

                <div class="section-reveal" style="transition-delay: 350ms;">
                    <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-sm flex items-center justify-center text-sm">6</span>
                        Contact Us
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        If you have any questions about this Privacy Policy, please contact us at <a href="mailto:info@miravildental.co.tz" class="text-primary-600 hover:underline">info@miravildental.co.tz</a> or visit our clinic in Mwanza.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
