<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'LiveBandwidth') }}</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-cyan-600 to-cyan-700 text-white flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 border-b border-cyan-500">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-network-wired text-2xl"></i>
                    <div>
                        <h1 class="text-lg font-bold">LiveBandwidth</h1>
                        <p class="text-xs text-cyan-100">MikroTik Monitor</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.*') ? 'bg-cyan-500' : 'hover:bg-cyan-500/50' }} transition-colors">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('mikrotik.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mikrotik.*') ? 'bg-cyan-500' : 'hover:bg-cyan-500/50' }} transition-colors">
                    <i class="fa-solid fa-server w-5"></i>
                    <span>Routers</span>
                </a>
                <a href="{{ route('pppoe.secret') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-cyan-500/50 transition-colors">
                    <i class="fa-solid fa-network-wired w-5"></i>
                    <span>PPPoE</span>
                </a>
                <a href="{{ route('interface.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-cyan-500/50 transition-colors">
                    <i class="fa-solid fa-ethernet w-5"></i>
                    <span>Interfaces</span>
                </a>
                <a href="{{ route('report-up.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-cyan-500/50 transition-colors">
                    <i class="fa-solid fa-gauge-high w-5"></i>
                    <span>Bandwidth</span>
                </a>
                <a href="{{ route('search.report') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-cyan-500/50 transition-colors">
                    <i class="fa-solid fa-list-ul w-5"></i>
                    <span>Traffic Logs</span>
                </a>
                <a href="{{ route('user.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-cyan-500/50 transition-colors">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>MikroTik Users</span>
                </a>
                <a href="{{ route('users.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('users.*') ? 'bg-cyan-500' : 'hover:bg-cyan-500/50' }} transition-colors">
                    <i class="fa-solid fa-user-gear w-5"></i>
                    <span>Users</span>
                </a>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-cyan-500">
                <div
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-cyan-500/50 transition-colors cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-cyan-100 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                    <i class="fa-solid fa-gear"></i>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-800">LiveBandwidth</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Selamat Datang, <span class="font-semibold">Marthin Alfreinsco Salakory!</span> -
                                Dashboard Monitoring Bandwidth MikroTik
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Terakhir login</p>
                                <p class="text-sm font-medium text-gray-700">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    {{ now()->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-cyan-600 flex items-center justify-center">
                                <i class="fa-solid fa-user text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>
</body>

</html>
