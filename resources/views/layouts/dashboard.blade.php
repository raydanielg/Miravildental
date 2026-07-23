<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard - ' . config('app.name', 'Laravel'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: { 50:'#e6f5f1',100:'#b3e0d4',200:'#80cbc0',300:'#4db5a8',400:'#1a9f8e',500:'#024938',600:'#023d30',700:'#013028',800:'#01241f',900:'#001816' },
                        gold: { 50:'#fff5e0',100:'#ffe6b3',200:'#ffd680',300:'#ffc64d',400:'#ffb71a',500:'#f9ac00',600:'#d49700',700:'#b07c00',800:'#8c6100',900:'#684600' }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
        .animate-fade { animation: fadeIn 0.3s ease-out both; }
        .sidebar-link { transition: all 0.2s ease; }

        @media print {
            @page { size: A4; margin: 15mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            #dashboardSidebar, header, .no-print, nav, button, .pagination, .fixed.inset-0.z-50, #mobileOverlay, canvas { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .print-header { display: block !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #94a3b8; padding: 10px 12px; font-size: 13px; }
            th { background: #e2e8f0 !important; font-weight: 700; }
            tr:nth-child(even) td { background: #f8fafc !important; }
            table thead tr th:last-child, table tbody tr td:last-child { display: none !important; }
            a { text-decoration: none; color: #000; }
            .shadow-sm, .rounded-xl { box-shadow: none !important; border: none !important; }
        }
        .print-header { display: none; }
        .settings-bg {
            background-image: url('/flat-abstract-background-pattern-vector_822782-866.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .settings-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(248, 250, 252, 0.92);
            z-index: 0;
        }
        .settings-bg > * { position: relative; z-index: 1; }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); }
        .sidebar-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar-submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .sidebar-submenu.open { max-height: 500px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #01241f; }
        ::-webkit-scrollbar-thumb { background: #024938; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #f9ac00; }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(120%) scale(0.9); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to { opacity: 0; transform: translateX(120%) scale(0.9); }
        }
        .toast-item {
            opacity: 0;
            transform: translateX(120%) scale(0.9);
        }
        .toast-item.toast-show {
            animation: toastIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .toast-item.toast-hide {
            animation: toastOut 0.4s ease-in forwards;
        }
        .toast-item:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="font-['Nunito',sans-serif] antialiased bg-gray-50 text-slate-800">

    {{-- Mobile Overlay --}}
    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="dashboardSidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-emerald-900 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
        {{-- Brand --}}
        <div class="h-16 flex items-center px-6 border-b border-emerald-800/50 flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-extrabold text-sm">
                {{ strtoupper(substr(config('app.name', 'L'), 0, 1)) }}
            </div>
            <span class="ml-2 text-white font-bold text-sm tracking-wide uppercase">{{ config('app.name', 'Laravel') }}</span>
        </div>

        {{-- Menu --}}
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            @php $user = auth()->user(); @endphp

            {{-- Dashboard --}}
            <div class="sidebar-group">
                <a href="{{ route('dashboard') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>
            </div>

            {{-- Team Chat --}}
            @php
                $unreadChatCount = \App\Models\ChatConversation::whereHas('participants', fn ($q) => $q->where('user_id', auth()->id()))
                    ->get()
                    ->sum(fn ($c) => $c->unreadCountFor(auth()->user()));
            @endphp
            <div class="sidebar-group">
                <a href="{{ route('chat.index') }}" class="sidebar-link relative inline-flex items-center w-full gap-3 px-4 py-2.5 rounded-lg text-emerald-100 text-sm font-medium bg-emerald-500/20 border border-transparent hover:bg-emerald-500/30 focus:ring-4 focus:ring-emerald-500/30 shadow-sm {{ request()->routeIs('chat.*') ? 'active bg-emerald-500/30' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span>Messages</span>
                    <div id="chatUnreadBadge" class="{{ $unreadChatCount > 0 ? '' : 'hidden' }} absolute inline-flex items-center justify-center min-w-[1.5rem] h-6 text-xs font-bold text-white bg-red-500 border-2 border-[#024938] rounded-full -top-2 -end-2 px-1 animate-pulse">
                        {{ $unreadChatCount > 99 ? '99+' : $unreadChatCount }}
                    </div>
                </a>
            </div>

            {{-- Appointments --}}
            @if($user->isAdmin() || $user->isReception() || $user->isDoctor())
            <div class="sidebar-group">
                <a href="{{ route('appointments.index') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('appointments.index') || request()->routeIs('appointments.create') || request()->routeIs('appointments.edit') || request()->routeIs('appointments.show') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $user->isDoctor() ? 'My Schedule' : 'Appointments' }}</span>
                </a>
                @if($user->isAdmin() || $user->isReception())
                <a href="{{ route('appointments.online') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2 pl-10 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('appointments.online') ? 'active' : '' }}">
                    <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Online Bookings</span>
                </a>
                @endif
            </div>
            @endif

            {{-- Patients --}}
            @if($user->isAdmin() || $user->isReception() || $user->isDoctor())
            <div class="sidebar-group">
                <a href="{{ route('patients.index') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('patients*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Patients</span>
                </a>
            </div>
            @endif

            {{-- Clinical / Treatment Records --}}
            @if($user->isAdmin() || $user->isDoctor())
            <div class="sidebar-group">
                <a href="{{ route('clinical-records.index') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('clinical-records*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>{{ $user->isDoctor() ? 'Treatment Records' : 'Clinical Records' }}</span>
                </a>
            </div>
            @endif

            {{-- SMS Center --}}
            @if($user->isAdmin() || $user->isReception())
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-sms')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('sms*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span>SMS Center</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-sms" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-sms" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('sms*') ? 'open' : '' }}">
                    <a href="{{ route('sms.send') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('sms.send') ? 'text-white font-medium' : '' }}">Send SMS</a>
                    <a href="{{ route('sms.campaign') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('sms.campaign') ? 'text-white font-medium' : '' }}">SMS Campaign</a>
                    <a href="{{ route('sms.logs') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('sms.logs') ? 'text-white font-medium' : '' }}">SMS Logs</a>
                    @if($user->isAdmin())
                    <a href="{{ route('sms.templates') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('sms.templates') ? 'text-white font-medium' : '' }}">Templates & Automation</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Reports --}}
            @if($user->isAdmin() || $user->isDoctor())
            <div class="sidebar-group">
                <a href="{{ route('reports.index') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('reports*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Reports</span>
                </a>
            </div>
            @endif

            {{-- Staff & Roles --}}
            @if($user->isAdmin())
            <div class="sidebar-group">
                <a href="{{ route('staff.index') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('staff*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Staff & Roles</span>
                </a>
            </div>
            @endif

            {{-- Settings --}}
            @if($user->isAdmin())
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-settings')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('settings*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Settings</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-settings" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-settings" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('settings*') ? 'open' : '' }}">
                    <a href="{{ route('settings.clinic') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('settings.clinic') ? 'text-white font-medium' : '' }}">Clinic Profile</a>
                    <a href="{{ route('services.index') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('services*') ? 'text-white font-medium' : '' }}">Services & Prices</a>
                    <a href="{{ route('rooms.index') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('rooms*') ? 'text-white font-medium' : '' }}">Rooms / Chairs</a>
                    <a href="{{ route('settings.working-hours') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('settings.working-hours') ? 'text-white font-medium' : '' }}">Working Hours</a>
                    <a href="{{ route('settings.sms') }}" class="block py-1.5 text-xs text-emerald-200/70 hover:text-white transition-colors {{ request()->routeIs('settings.sms') ? 'text-white font-medium' : '' }}">SMS Configuration</a>
                </div>
            </div>
            @endif

            {{-- My Profile --}}
            @if($user->isDoctor() || $user->isReception())
            <div class="sidebar-group">
                <a href="{{ route('staff.profile') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-emerald-100 text-sm font-medium {{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>My Profile</span>
                </a>
            </div>
            @endif

        </div>

        {{-- Bottom User --}}
        <div class="p-4 border-t border-emerald-800/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-emerald-300/60">{{ ucfirst(Auth::user()->role ?? 'User') }}</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('dashboard-logout').submit();" class="text-emerald-300/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
                <form id="dashboard-logout" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                {{-- Global Search --}}
                <form method="GET" action="{{ route('patients.index') }}" class="hidden md:flex items-center bg-gray-50 rounded-lg px-3 py-1.5 border border-gray-100">
                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patients..." class="bg-transparent text-sm outline-none w-48 text-gray-600 placeholder-gray-400">
                </form>

                @php
                    $todayAppointmentCount = \App\Models\Appointment::today()->whereIn('status', [\App\Models\Appointment::STATUS_BOOKED, \App\Models\Appointment::STATUS_CONFIRMED])->count();
                    $onlineUsersCount = \App\Models\User::whereNotNull('last_seen_at')->where('last_seen_at', '>=', now()->subMinutes(2))->where('id', '!=', auth()->id())->count();
                    $onlineUsers = \App\Models\User::whereNotNull('last_seen_at')->where('last_seen_at', '>=', now()->subMinutes(2))->where('id', '!=', auth()->id())->orderBy('name')->limit(5)->get();
                    $headerUnreadCount = \App\Models\ChatConversation::whereHas('participants', fn ($q) => $q->where('user_id', auth()->id()))
                        ->get()
                        ->sum(fn ($c) => $c->unreadCountFor(auth()->user()));
                    $totalNotifications = $todayAppointmentCount + $headerUnreadCount + $onlineUsersCount;
                @endphp

                {{-- Online Activity Shortcut --}}
                <div class="relative">
                    @php
                        $activityCount = $onlineUsersCount + $todayAppointmentCount;
                    @endphp
                    <button onclick="document.getElementById('activityDropdown').classList.toggle('hidden')" class="relative w-10 h-10 rounded-full bg-gradient-to-br from-blue-50 to-white hover:from-blue-100 hover:to-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 {{ $activityCount > 0 ? 'text-blue-600' : 'text-blue-400' }} group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @if($activityCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full animate-ping"></span>
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center text-[8px] font-bold text-white">{{ $activityCount > 9 ? '9+' : $activityCount }}</span>
                        @endif
                    </button>
                    <div id="activityDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white/95 backdrop-blur-sm rounded-2xl border border-blue-100 shadow-2xl z-50 overflow-hidden ring-1 ring-black/5">
                        <div class="px-4 py-3 border-b border-blue-100 bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white text-[10px]">
                                    <i class="fa-solid fa-users"></i>
                                </span>
                                <p class="text-xs font-bold text-white">Today's Activity</p>
                            </div>
                            @if($activityCount > 0)
                                <span class="text-[10px] font-bold text-blue-700 bg-white px-2 py-0.5 rounded-full">{{ $activityCount }}</span>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @if($todayAppointmentCount > 0)
                                <a href="{{ route('appointments.index') }}" class="group block px-4 py-3 border-b border-gray-50 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 flex items-center justify-center text-xs shadow-sm group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-800 font-semibold group-hover:text-blue-700">Today's appointments</p>
                                            <p class="text-[10px] text-gray-500 truncate">{{ $todayAppointmentCount }} patient(s) scheduled</p>
                                        </div>
                                        <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] font-bold">{{ $todayAppointmentCount }}</span>
                                    </div>
                                </a>
                            @endif
                            @if($onlineUsersCount > 0)
                                <div class="px-4 py-2 border-b border-blue-100 bg-gradient-to-r from-blue-50/50 to-transparent">
                                    <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wide flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        Online now ({{ $onlineUsersCount }})
                                    </p>
                                </div>
                                @foreach($onlineUsers as $onlineUser)
                                    <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-50 hover:bg-blue-50/50 transition-colors">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="relative w-8 h-8 rounded-full bg-gradient-to-br from-slate-100 to-white text-slate-600 border border-slate-200 flex items-center justify-center text-[10px] font-bold shadow-sm">
                                                {{ strtoupper(substr($onlineUser->name, 0, 2)) }}
                                                <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                            </span>
                                            <p class="text-xs text-gray-800 font-medium truncate">{{ $onlineUser->name }}</p>
                                        </div>
                                        <span class="text-[10px] text-emerald-600 font-semibold whitespace-nowrap ml-2 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Online
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                            @if($activityCount === 0)
                                <div class="px-4 py-10 text-center">
                                    <span class="w-12 h-12 rounded-full bg-gray-100 text-gray-300 flex items-center justify-center mx-auto mb-2 text-lg">
                                        <i class="fa-solid fa-user-clock"></i>
                                    </span>
                                    <p class="text-xs text-gray-400">No activity today</p>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('chat.index') }}" class="block px-4 py-2.5 text-[10px] font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-center transition-colors">Open chat</a>
                    </div>
                </div>

                {{-- Notifications --}}
                <div class="relative">
                    <button id="headerNotificationBtn" onclick="document.getElementById('notificationDropdown').classList.toggle('hidden')" class="relative w-10 h-10 rounded-full bg-gradient-to-br from-emerald-50 to-white hover:from-emerald-100 hover:to-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 {{ $headerUnreadCount > 0 ? 'text-gold-500' : 'text-emerald-600' }} group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($headerUnreadCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-red-500 border-2 border-white rounded-full animate-ping"></span>
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-[8px] font-bold text-white">{{ $headerUnreadCount > 9 ? '9+' : $headerUnreadCount }}</span>
                        @endif
                    </button>
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white/95 backdrop-blur-sm rounded-2xl border border-emerald-100 shadow-2xl z-50 overflow-hidden ring-1 ring-black/5">
                        <div class="px-4 py-3 border-b border-emerald-100 bg-gradient-to-r from-emerald-600 to-emerald-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white text-[10px]">
                                    <i class="fa-solid fa-bell"></i>
                                </span>
                                <p class="text-xs font-bold text-white">Notifications</p>
                            </div>
                            @if($headerUnreadCount > 0)
                                <span class="text-[10px] font-bold text-emerald-700 bg-white px-2 py-0.5 rounded-full">{{ $headerUnreadCount }}</span>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @if($headerUnreadCount > 0)
                                <a href="{{ route('chat.index') }}" class="group block px-4 py-3 border-b border-gray-50 hover:bg-emerald-50 transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 flex items-center justify-center text-xs shadow-sm group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-message"></i>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-800 font-semibold group-hover:text-emerald-700">Unread messages</p>
                                            <p class="text-[10px] text-gray-500 truncate">{{ $headerUnreadCount }} new message{{ $headerUnreadCount > 1 ? 's' : '' }}</p>
                                        </div>
                                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">{{ $headerUnreadCount }}</span>
                                    </div>
                                </a>
                            @else
                                <div class="px-4 py-10 text-center">
                                    <span class="w-12 h-12 rounded-full bg-gray-100 text-gray-300 flex items-center justify-center mx-auto mb-2 text-lg">
                                        <i class="fa-regular fa-bell-slash"></i>
                                    </span>
                                    <p class="text-xs text-gray-400">No new notifications</p>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('chat.index') }}" class="block px-4 py-2.5 text-[10px] font-bold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-center transition-colors">Open chat</a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6 animate-fade relative {{ request()->routeIs('settings.*') ? 'settings-bg' : '' }}">
            @php
                $printSettings = \App\Models\ClinicSetting::current();
            @endphp
            <div class="print-header mb-6 text-center border-b-2 border-emerald-700 pb-4">
                <h1 class="text-2xl font-bold text-emerald-900">{{ $printSettings?->clinic_name ?? 'Miravil Specialised Dental Centre' }}</h1>
                <p class="text-sm text-gray-700 mt-1">{{ $printSettings?->address ?? '' }}</p>
                <div class="flex items-center justify-center gap-4 text-sm text-gray-700 mt-1">
                    @if($printSettings?->phone)<span>Simu: {{ $printSettings->phone }}</span>@endif
                    @if($printSettings?->email)<span>Email: {{ $printSettings->email }}</span>@endif
                </div>
                <p class="text-xs text-gray-500 mt-2">Tarehe: {{ now()->format('d M Y') }}</p>
            </div>
            @yield('content')
        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            const arrow = document.getElementById('arrow-' + id.replace('menu-', ''));
            menu.classList.toggle('open');
            if (arrow) arrow.classList.toggle('rotate-180');
        }

        // SweetAlert2 delete confirmations
        (function() {
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: this.dataset.confirm,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) this.submit();
                    });
                });
            });
        })();

        // SweetAlert2 side toasts
        (function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            @if(session('status'))
                Toast.fire({ icon: 'success', title: '{{ session('status') }}' });
            @endif
            @if(session('error'))
                Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
            @endif
            @if(session('warning'))
                Toast.fire({ icon: 'warning', title: '{{ session('warning') }}' });
            @endif
            @if(session('info'))
                Toast.fire({ icon: 'info', title: '{{ session('info') }}' });
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    Toast.fire({ icon: 'error', title: '{{ $error }}' });
                @endforeach
            @endif
        })();
    </script>
    <script>
        (function() {
            const badge = document.getElementById('chatUnreadBadge');
            const notifAudio = new Audio('{{ asset("notification_message-best-notification-1-286672.mp3") }}');
            let lastMaxId = 0;
            let lastUnreadCount = {{ $headerUnreadCount ?? 0 }};

            function showToast(msg) {
                const container = document.getElementById('toastContainer');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = 'toast-item pointer-events-auto bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden max-w-sm w-full cursor-pointer hover:shadow-3xl transition-all duration-300';
                toast.innerHTML = `
                    <div class="flex items-stretch">
                        <div class="w-1.5 bg-gradient-to-b from-emerald-400 to-emerald-600"></div>
                        <div class="flex items-start gap-3 p-3.5 flex-1 min-w-0">
                            <div class="relative shrink-0">
                                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 border border-emerald-200 flex items-center justify-center text-sm font-bold text-emerald-700 shadow-sm">
                                    ${msg.user_avatar}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-bold text-slate-800 truncate">${msg.user_name}</p>
                                    <span class="text-[10px] text-slate-400 whitespace-nowrap shrink-0">${msg.created_at}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 leading-relaxed">${msg.body}</p>
                            </div>
                        </div>
                    </div>
                `;

                toast.addEventListener('click', () => {
                    window.location.href = '{{ route("chat.index") }}';
                });

                container.appendChild(toast);

                requestAnimationFrame(() => {
                    toast.classList.add('toast-show');
                });

                setTimeout(() => {
                    toast.classList.remove('toast-show');
                    toast.classList.add('toast-hide');
                    setTimeout(() => toast.remove(), 400);
                }, 5000);
            }

            function updateBadge(count) {
                if (!badge) return;
                const hasUnread = count > 0;
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.toggle('hidden', !hasUnread);

                if (hasUnread && count !== lastUnreadCount) {
                    badge.classList.remove('animate-pulse');
                    void badge.offsetWidth;
                    badge.classList.add('animate-pulse', 'animate-bounce');
                    setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
                }
                lastUnreadCount = count;
            }

            let chatPollingActive = true;

            async function fetchLatestMessages() {
                if (!chatPollingActive) return;
                try {
                    const res = await fetch('{{ route("chat.latest-messages") }}?since_id=' + lastMaxId, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) {
                        if (res.status === 404) {
                            chatPollingActive = false;
                        }
                        return;
                    }
                    const data = await res.json();

                    if (data.max_id > lastMaxId) lastMaxId = data.max_id;

                    if (data.messages && data.messages.length > 0) {
                        notifAudio.currentTime = 0;
                        notifAudio.play().catch(() => {});
                        data.messages.forEach((msg, i) => {
                            setTimeout(() => showToast(msg), i * 600);
                        });
                    }

                    updateBadge(data.unread_count || 0);
                } catch (e) {
                    console.error('Latest messages fetch error', e);
                }
            }

            fetchLatestMessages();
            setInterval(fetchLatestMessages, 8000);
        })();
    </script>

    {{-- Toast Notification Container --}}
    <div id="toastContainer" class="fixed top-20 right-4 z-[200] flex flex-col gap-3 pointer-events-none">
        {{-- Toasts injected here --}}
    </div>

    @stack('scripts')
</body>
</html>
