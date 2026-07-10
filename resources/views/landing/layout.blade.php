<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Miravil Specialised Dental Centre'))</title>
    <meta name="description" content="@yield('meta_description', 'Miravil Specialised Dental Centre - Advanced diagnostics and treatment of dental and oral disorders in Mwanza, Tanzania. Book your appointment online.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Miravil Dental, dental clinic Mwanza, dentist Tanzania, teeth whitening, root canal, orthodontics, dental care')">
    <meta name="author" content="Miravil Specialised Dental Centre">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ URL::current() }}">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('logo.png') }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', config('app.name', 'Miravil Specialised Dental Centre'))">
    <meta property="og:description" content="@yield('meta_description', 'Miravil Specialised Dental Centre - Advanced diagnostics and treatment of dental and oral disorders in Mwanza, Tanzania.')">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:site_name" content="Miravil Specialised Dental Centre">
    <meta property="og:image" content="@yield('og_image', asset('images.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Miravil Specialised Dental Centre">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name', 'Miravil Specialised Dental Centre'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Miravil Specialised Dental Centre - Advanced diagnostics and treatment of dental and oral disorders in Mwanza, Tanzania.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images.png'))">
    <meta name="twitter:image:alt" content="Miravil Specialised Dental Centre">

    {{-- Sitemap & RSS --}}
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap.xml') }}">
    <link rel="alternate" type="application/rss+xml" title="Miravil Dental RSS Feed" href="{{ route('rss.feed') }}">

    {{-- Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Dentist",
        "name": "Miravil Specialised Dental Centre",
        "description": "Modern dental clinic in Mwanza, Tanzania offering advanced diagnostics and treatment of dental and oral disorders.",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('logo.png') }}",
        "image": "{{ asset('logo.png') }}",
        "telephone": "+255-753-188-852",
        "email": "info@miravildental.co.tz",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "P.O BOX 2323, Buswelu",
            "addressLocality": "Mwanza",
            "addressCountry": "TZ"
        },
        "openingHours": "Mo-Su 08:30-17:00",
        "sameAs": []
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' },
                        secondary: { 50: '#faf5ff', 100: '#f3e8ff', 200: '#e9d5ff', 300: '#d8b4fe', 400: '#c084fc', 500: '#a855f7', 600: '#9333ea', 700: '#7e22ce', 800: '#6b21a8', 900: '#581c87' },
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .gradient-hero { background: linear-gradient(135deg, #14532d 0%, #16a34a 50%, #9333ea 100%); }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15); }

        /* Footer reveal animation */
        .footer-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .footer-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Footer link dot animation */
        .footer-link:hover .link-dot { opacity: 1; }
        .footer-link { transition: transform 0.2s ease, color 0.2s ease; }
        .footer-link:hover { transform: translateX(4px); }

        /* Footer bottom link underline */
        .footer-bottom-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #22c55e, #a855f7);
            transition: width 0.3s ease;
        }
        .footer-bottom-link:hover::after { width: 100%; }

        /* Section reveal on scroll */
        .section-reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .section-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Contact card hover lift */
        .contact-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.12);
        }

        /* Feature card hover lift */
        .feature-card {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
        }

        /* Service card hover lift */
        .service-card {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s ease;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.12);
        }

        /* Floating teeth background animation */
        .floating-tooth {
            position: absolute;
            opacity: 0.25;
            animation-name: floatTooth;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
        }
        @keyframes floatTooth {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(8deg); }
            50% { transform: translateY(10px) rotate(-5deg); }
            75% { transform: translateY(-15px) rotate(5deg); }
        }

        /* Hero slideshow Ken Burns zoom/pan effect */
        .hero-slide {
            animation: kenBurns 8s ease-out forwards;
        }
        @keyframes kenBurns {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.15) translate(-2%, -2%); }
        }

        /* Animated gradient mesh for sections */
        .mesh-bg {
            background: radial-gradient(at 0% 0%, rgba(34,197,94,0.15) 0px, transparent 50%),
                        radial-gradient(at 100% 0%, rgba(168,85,247,0.15) 0px, transparent 50%),
                        radial-gradient(at 100% 100%, rgba(34,197,94,0.15) 0px, transparent 50%),
                        radial-gradient(at 0% 100%, rgba(168,85,247,0.15) 0px, transparent 50%);
            background-size: 200% 200%;
            animation: meshMove 15s ease infinite;
        }
        @keyframes meshMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Floating orbs */
        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.4;
            animation-name: orbFloat;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
        }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* Animated connecting lines in features section */
        .feature-line {
            animation: dashFlow 20s linear infinite;
        }
        @keyframes dashFlow {
            to { stroke-dashoffset: -200; }
        }

        /* Services marquee rows */
        .marquee-row-left {
            animation: marqueeLeft 35s linear infinite;
        }
        .marquee-row-right {
            animation: marqueeRight 40s linear infinite;
        }
        .marquee-row-left:hover,
        .marquee-row-right:hover {
            animation-play-state: paused;
        }
        @keyframes marqueeLeft {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes marqueeRight {
            0% { transform: translateX(-50%); }
            100% { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans text-slate-700 antialiased">

    @include('landing.partials.header')

    <main>
        @yield('content')
    </main>

    @include('landing.partials.footer')

    <script>
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                nav.classList.add('shadow-md');
                nav.classList.replace('bg-white/90', 'bg-white/95');
            } else {
                nav.classList.remove('shadow-md');
            }
        });

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Animate elements when they scroll into view
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.footer-reveal, .section-reveal').forEach(el => revealObserver.observe(el));

        // Hero background slideshow
        (function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            if (!slides.length) return;

            let current = 0;
            const total = slides.length;
            const interval = 5000;
            let timer;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('opacity-100', i === index);
                    slide.classList.toggle('opacity-0', i !== index);
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-white', i === index);
                    dot.classList.toggle('bg-white/40', i !== index);
                    dot.classList.toggle('w-12', i === index);
                    dot.classList.toggle('w-8', i !== index);
                });
                current = index;
            }

            function nextSlide() {
                showSlide((current + 1) % total);
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    showSlide(i);
                    resetTimer();
                });
            });

            function resetTimer() {
                clearInterval(timer);
                timer = setInterval(nextSlide, interval);
            }

            showSlide(0);
            timer = setInterval(nextSlide, interval);
        })();

        // About section image slideshow
        (function() {
            const slides = document.querySelectorAll('.about-slide');
            const dots = document.querySelectorAll('.about-dot');
            if (!slides.length) return;

            let current = 0;
            const total = slides.length;
            const interval = 4500;
            let timer;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    const active = i === index;
                    slide.classList.toggle('opacity-100', active);
                    slide.classList.toggle('opacity-0', !active);
                    slide.classList.toggle('scale-100', active);
                    slide.classList.toggle('scale-110', !active);
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-white', i === index);
                    dot.classList.toggle('w-8', i === index);
                    dot.classList.toggle('bg-white/50', i !== index);
                    dot.classList.toggle('w-6', i !== index);
                });
                current = index;
            }

            function nextSlide() {
                showSlide((current + 1) % total);
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    showSlide(i);
                    resetTimer();
                });
            });

            function resetTimer() {
                clearInterval(timer);
                timer = setInterval(nextSlide, interval);
            }

            showSlide(0);
            timer = setInterval(nextSlide, interval);
        })();

        // Booking sidebar
        const bookingSidebar = document.getElementById('booking-sidebar');
        const bookingOverlay = document.getElementById('booking-overlay');
        const bookingServiceId = document.getElementById('booking-service-id');
        const selectedServiceName = document.getElementById('selected-service-name');
        const serviceBookingForm = document.getElementById('service-booking-form');

        if (bookingSidebar) {
            window.openBookingSidebar = function(serviceId, serviceName) {
                bookingServiceId.value = serviceId;
                selectedServiceName.textContent = serviceName;
                bookingSidebar.classList.remove('translate-x-full');
                bookingOverlay.classList.remove('hidden');
                setTimeout(() => bookingOverlay.classList.remove('opacity-0'), 10);
                document.body.style.overflow = 'hidden';
            };

            window.closeBookingSidebar = function() {
                bookingSidebar.classList.add('translate-x-full');
                bookingOverlay.classList.add('opacity-0');
                setTimeout(() => bookingOverlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            };

            // AJAX booking form submission with SweetAlert
            if (serviceBookingForm) {
                serviceBookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = document.getElementById('service-booking-submit');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Processing...';

                    const formData = new FormData(serviceBookingForm);

                    fetch(serviceBookingForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json().catch(() => ({ success: false, message: 'Network error' })))
                    .then(data => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Booking Received!',
                                text: data.message || 'We will review your appointment and confirm soon.',
                                confirmButtonColor: '#16a34a',
                                confirmButtonText: 'Great!'
                            });
                            serviceBookingForm.reset();
                            closeBookingSidebar();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Booking Failed',
                                text: data.message || 'Please check your details and try again.',
                                confirmButtonColor: '#9333ea'
                            });
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonColor: '#9333ea'
                        });
                    });
                });
            }
        }

        // Newsletter subscription form - AJAX with SweetAlert toast
        const newsletterForm = document.getElementById('newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('newsletter-submit');
                const btnText = document.getElementById('newsletter-btn-text');
                const messageEl = document.getElementById('newsletter-message');
                const originalText = btnText.innerHTML;

                submitBtn.disabled = true;
                btnText.innerHTML = 'Subscribing...';
                messageEl.classList.add('hidden');

                const formData = new FormData(newsletterForm);

                fetch(newsletterForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json().catch(() => ({ success: false, message: 'Network error' })))
                .then(data => {
                    submitBtn.disabled = false;
                    btnText.innerHTML = originalText;

                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            background: '#f0fdf4',
                            color: '#14532d',
                            iconColor: '#16a34a'
                        });
                        newsletterForm.reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Subscription Failed',
                            text: data.message || 'Please check your email and try again.',
                            confirmButtonColor: '#9333ea'
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    btnText.innerHTML = originalText;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonColor: '#9333ea'
                    });
                });
            });
        }

        // Appointment section form - AJAX with SweetAlert toast
        const appointmentForm = document.getElementById('appointment-form');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('appointment-submit');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Booking...';

                const formData = new FormData(appointmentForm);

                fetch(appointmentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json().catch(() => ({ success: false, message: 'Network error' })))
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            background: '#f0fdf4',
                            color: '#14532d',
                            iconColor: '#16a34a'
                        });
                        appointmentForm.reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Failed',
                            text: data.message || 'Please check your details and try again.',
                            confirmButtonColor: '#9333ea'
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonColor: '#9333ea'
                    });
                });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
