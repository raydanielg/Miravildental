<section id="appointment" class="py-20 md:py-28 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            {{-- Left content --}}
            <div class="space-y-6">
                <span class="text-secondary-600 font-semibold text-sm uppercase tracking-wider">Book Visit</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight">
                    Make an Appointment<br>
                    <span class="text-secondary-600">to Doctor Visit</span>
                </h2>
                <p class="text-slate-600 text-lg">
                    Fill in the form and our team will get back to you shortly to confirm your appointment.
                </p>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-primary-600 rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Visit Us</p>
                            <p class="text-slate-600 text-sm">P.O BOX 2323 Buswelu Mwanza</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-primary-600 rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Email Us</p>
                            <p class="text-slate-600 text-sm">info@miravildental.co.tz</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-primary-600 rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Call Us</p>
                            <p class="text-slate-600 text-sm">+255 753 188 852 / +255 789 483 550</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Simple form card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 md:p-10">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-slate-900">Book Your Visit</h3>
                    <p class="text-slate-500 text-sm mt-1">We will confirm your appointment shortly</p>
                </div>

                <form id="appointment-form" action="{{ route('landing.appointment.book') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Your Full Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="+255 789 483 550">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Appointment Date</label>
                            <input type="date" name="appointment_date" required class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select Service</label>
                        <select name="service_id" required class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition bg-white">
                            <option value="">Choose a service</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Message</label>
                        <textarea name="message" rows="3" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition" placeholder="Tell us about your dental concern..."></textarea>
                    </div>

                    <button type="submit" id="appointment-submit" class="w-full bg-primary-600 text-white py-3.5 rounded-lg font-bold text-lg hover:bg-primary-700 transition shadow-md">
                        Book Appointment
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
