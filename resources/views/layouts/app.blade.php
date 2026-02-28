<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ACTS Church CMS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f3ff',
                            100: '#dde3f7',
                            200: '#b3bfe8',
                            300: '#8a9bd9',
                            400: '#6177ca',
                            500: '#3a55a4',
                            600: '#1e3a7b',
                            700: '#152c61',
                            800: '#0e1f47',
                            900: '#0a1630',
                        },
                        gold: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#d4a017',
                            600: '#b8860b',
                            700: '#92640a',
                            800: '#6d4c0a',
                            900: '#4a330a',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(212, 160, 23, 0.2), rgba(212, 160, 23, 0.1));
            border-left: 3px solid #d4a017;
            color: #fbbf24;
        }
        .sidebar-link:hover {
            background: linear-gradient(135deg, rgba(212, 160, 23, 0.15), rgba(212, 160, 23, 0.05));
            color: #fbbf24;
        }
        .gradient-navy {
            background: linear-gradient(135deg, #1e3a7b 0%, #0a1630 100%);
        }
        .gradient-navy-gold {
            background: linear-gradient(135deg, #1e3a7b 0%, #0e1f47 60%, #4a330a 100%);
        }
        .gradient-gold {
            background: linear-gradient(135deg, #d4a017 0%, #b8860b 100%);
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        [x-cloak] { display: none !important; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true, mobileSidebar: false }">
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="gradient-navy fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 scrollbar-thin overflow-y-auto"
               :class="sidebarOpen ? 'w-64' : 'w-20'"
               x-cloak>

            {{-- Logo --}}
            <div class="flex items-center justify-center h-16 border-b border-navy-500/30 px-4">
                <div class="flex items-center space-x-3" x-show="sidebarOpen">
                    <div class="w-9 h-9 rounded-lg gradient-gold flex items-center justify-center">
                        <i class="fas fa-church text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-sm tracking-wide">ACTS CMS</h1>
                        <p class="text-gold-400 text-[10px] tracking-wider uppercase">Church Management</p>
                    </div>
                </div>
                <div x-show="!sidebarOpen" class="w-9 h-9 rounded-lg gradient-gold flex items-center justify-center">
                    <i class="fas fa-church text-white text-sm"></i>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4 px-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('members.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Members</span>
                </a>

                <a href="{{ route('visitors.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('visitors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-plus w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Visitors</span>
                </a>

                <a href="{{ route('attendance.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Attendance</span>
                </a>

                <a href="{{ route('finance.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                    <i class="fas fa-wallet w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Finance</span>
                </a>

                <a href="{{ route('sms.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('sms.*') ? 'active' : '' }}">
                    <i class="fas fa-comment-sms w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Bulk SMS</span>
                </a>

                <a href="{{ route('equipment.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="fas fa-tools w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Equipment</span>
                </a>

                <a href="{{ route('reports.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Reports</span>
                </a>

                <a href="{{ route('settings.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Settings</span>
                </a>

                <a href="{{ route('clusters.followups') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm text-gray-300 transition-all {{ request()->routeIs('clusters.*') ? 'active' : '' }}">
                    <i class="fas fa-people-arrows w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen">Cluster Follow-up</span>
                </a>
            </nav>

            {{-- User Info --}}
            <div class="border-t border-navy-500/30 p-3">
                <div class="flex items-center px-3 py-2" x-show="sidebarOpen">
                    <div class="w-8 h-8 rounded-full gradient-gold flex items-center justify-center">
                        <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-gray-400 text-xs truncate">{{ ucfirst(auth()->user()->role ?? 'admin') }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'">

            {{-- Top Navbar --}}
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-navy-600 focus:outline-none">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-navy-800">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-xs text-gray-500">@yield('page-description', '')</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ now()->format('M d, Y') }}
                        </span>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-navy-600 focus:outline-none">
                                <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                                </div>
                                <span class="hidden md:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user-circle mr-2"></i> Profile
                                </a>
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center mb-1">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="text-sm font-medium">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Page Content --}}
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
