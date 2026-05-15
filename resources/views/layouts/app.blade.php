<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('', 'होमिनिस्टर रजिस्ट्रेशन | सौ. मालिका ताई निखिल साकळे') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 antialiased min-h-screen">
    <div x-data="{ mobileMenuOpen: false }">
        @php
            $pendingCount = \App\Models\Participant::where('status', 'pending')->count();
        @endphp
        <!-- Navigation -->
        <nav class="bg-white/80 border-b border-slate-200 sticky top-0 z-50 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Links -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-lg font-bold text-slate-900 tracking-tight">
                                LuckyDraw
                            </span>
                        </div>
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-semibold transition-all duration-200">
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('participants.index') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('participants.index') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-semibold transition-all duration-200">
                                {{ __('Participants') }}
                            </a>
                            <a href="{{ route('participants.pending') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('participants.pending') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-semibold transition-all duration-200">
                                {{ __('Pending') }}
                                @if($pendingCount > 0)
                                    <span
                                        class="ml-2 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('winning-gifts.index') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('winning-gifts.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-semibold transition-all duration-200">
                                {{ __('Winning Gifts') }}
                            </a>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('admin.users.index') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.users.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-semibold transition-all duration-200">
                                    {{ __('User Management') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- User Dropdown (Desktop) -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Language Switcher -->
                        <div class="mr-4 flex items-center bg-slate-100 rounded-lg p-1">
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="px-3 py-1.5 text-xs font-bold rounded-md transition-all {{ app()->getLocale() == 'en' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700' }}">EN</a>
                            <a href="{{ route('lang.switch', 'mr') }}"
                                class="px-3 py-1.5 text-xs font-bold rounded-md transition-all {{ app()->getLocale() == 'mr' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700' }}">MR</a>
                        </div>

                        <div class="ml-3 relative">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="flex items-center gap-2.5 text-sm font-medium text-slate-700 hover:text-slate-900 focus:outline-none transition-colors group">
                                    <div
                                        class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-all">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden md:block font-semibold">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-colors"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg shadow-slate-200/50 bg-white ring-1 ring-black ring-opacity-5 py-1 z-50">
                                    <div class="px-4 py-2 border-b border-slate-100">
                                        <p class="text-xs text-slate-500 font-medium">{{ __('Signed in as') }}</p>
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->email }}
                                        </p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ __('Settings') }}</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                                            {{ __('Sign out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-slate-500 hover:text-slate-900 p-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Off-canvas -->
        <div class="fixed inset-0 flex justify-end isolate" role="dialog" aria-modal="true" x-show="mobileMenuOpen"
            style="display: none; z-index: 2147483647;">
            <!-- Backdrop -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
                @click="mobileMenuOpen = false"></div>

            <!-- Slide-out menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="relative h-full w-4/5 max-w-[280px] !bg-white shadow-2xl overflow-y-auto flex flex-col"
                style="z-index: 2147483647; background-color: #ffffff !important;">

                <!-- Mobile Menu Header (User Info + Language + Close) -->
                <div class="px-4 py-4 border-b border-slate-100 bg-white">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg shadow-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-sm font-bold text-slate-900 truncate max-w-[140px]">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-xs font-medium text-slate-500 truncate max-w-[140px]">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </div>
                        <button @click="mobileMenuOpen = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors p-2 rounded-lg hover:bg-slate-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Language Switcher -->
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="text-center px-3 py-2 text-xs font-bold rounded-lg border {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                            English
                        </a>
                        <a href="{{ route('lang.switch', 'mr') }}"
                            class="text-center px-3 py-2 text-xs font-bold rounded-lg border {{ app()->getLocale() == 'mr' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                            मराठी
                        </a>
                    </div>
                </div>

                <!-- Mobile Links -->
                <div class="flex-1 px-4 py-6 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('participants.index') }}"
                        class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('participants.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        {{ __('Participants') }}
                    </a>
                    <a href="{{ route('participants.pending') }}"
                        class="flex items-center justify-between px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('participants.pending') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span>{{ __('Pending') }}</span>
                        @if($pendingCount > 0)
                            <span
                                class="bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('winning-gifts.index') }}"
                        class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('winning-gifts.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        {{ __('Winning Gifts') }}
                    </a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                            class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            {{ __('User Management') }}
                        </a>
                    @endif
                </div>

                <!-- Mobile Footer (Settings + Logout) -->
                <div class="border-t border-slate-200 p-4 bg-slate-50">
                    <a href="{{ route('profile.edit') }}"
                        class="block w-full text-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 mb-2">
                        {{ __('Settings') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-center px-4 py-2 bg-rose-50 border border-rose-100 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-100">
                            {{ __('Sign out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="mb-4 bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show"
                        class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>

</html>