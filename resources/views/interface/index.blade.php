<x-layouts.app>
    @php
        // Hitung statistik
        $totalInterface = count($interface ?? []);
        $runningInterface = 0;
        $downInterface = 0;
        $totalRx = 0;
        $totalTx = 0;

        if (is_array($interface)) {
            foreach ($interface as $if) {
                if (isset($if['running']) && $if['running'] == 'true') {
                    $runningInterface++;
                } else {
                    $downInterface++;
                }
            }
        }
    @endphp

    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Interfaces</h1>
                <p class="text-sm text-gray-600 mt-1">Daftar interface pada router MikroTik</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="location.reload()"
                    class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">
                    <i class="fa-solid fa-rotate mr-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Interfaces</p>
                        <p class="text-3xl font-bold">{{ number_format($totalInterface) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-ethernet text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Running</p>
                        <p class="text-3xl font-bold">{{ number_format($runningInterface) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium mb-1">Down</p>
                        <p class="text-3xl font-bold">{{ number_format($downInterface) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-circle-xmark text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow-md p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-sm font-medium mb-1">Active Rate</p>
                        <p class="text-3xl font-bold">{{ $totalInterface > 0 ? round(($runningInterface / $totalInterface) * 100) : 0 }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interface Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Interface</h2>
                <div class="flex items-center space-x-2">
                    <input type="text" id="searchInput" placeholder="Cari interface..."
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm w-64">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="interfaceTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fa-solid fa-tag mr-2"></i>Nama
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fa-solid fa-network-wired mr-2"></i>Tipe
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fa-solid fa-fingerprint mr-2"></i>MAC Address
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fa-solid fa-gauge mr-2"></i>MTU
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fa-solid fa-signal mr-2"></i>Status
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fa-solid fa-info-circle mr-2"></i>Info
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center justify-end">
                                <i class="fa-solid fa-chart-line mr-2"></i>Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($interface as $item)
                        @php
                            $isRunning = ($item['running'] ?? 'false') === 'true';
                            $interfaceType = strtolower($item['type'] ?? '');
                            $typeIcon = 'fa-ethernet';
                            $bgColor = 'bg-blue-100';
                            $iconColor = 'text-blue-600';

                            // Tentukan icon dan warna berdasarkan tipe
                            if (strpos($interfaceType, 'ether') !== false) {
                                $typeIcon = 'fa-ethernet';
                                $bgColor = 'bg-blue-100';
                                $iconColor = 'text-blue-600';
                            } elseif (strpos($interfaceType, 'wlan') !== false || strpos($interfaceType, 'wireless') !== false) {
                                $typeIcon = 'fa-wifi';
                                $bgColor = 'bg-purple-100';
                                $iconColor = 'text-purple-600';
                            } elseif (strpos($interfaceType, 'bridge') !== false) {
                                $typeIcon = 'fa-network-wired';
                                $bgColor = 'bg-cyan-100';
                                $iconColor = 'text-cyan-600';
                            } elseif (strpos($interfaceType, 'vlan') !== false) {
                                $typeIcon = 'fa-tags';
                                $bgColor = 'bg-green-100';
                                $iconColor = 'text-green-600';
                            } elseif (strpos($interfaceType, 'ppp') !== false) {
                                $typeIcon = 'fa-plug';
                                $bgColor = 'bg-orange-100';
                                $iconColor = 'text-orange-600';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors interface-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 {{ $bgColor }} rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-solid {{ $typeIcon }} {{ $iconColor }}"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $item['name'] ?? '-' }}</div>
                                        @if(isset($item['default-name']) && $item['default-name'] != $item['name'])
                                            <div class="text-xs text-gray-500">{{ $item['default-name'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $item['type'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-mono">{{ $item['mac-address'] ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item['mtu'] ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isRunning)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <span class="mr-2 h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                                        Running
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <span class="mr-2 h-2 w-2 rounded-full bg-red-500"></span>
                                        Down
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    @if(isset($item['speed']))
                                        <span class="text-xs text-gray-600">
                                            <i class="fa-solid fa-tachometer-alt mr-1"></i>{{ $item['speed'] }}
                                        </span>
                                    @endif
                                    @if(isset($item['link-downs']))
                                        <span class="text-xs text-gray-600">
                                            <i class="fa-solid fa-arrow-down mr-1"></i>{{ $item['link-downs'] }} downs
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('interface.traffic', $item['name'] ?? '') }}"
                                    class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium shadow-sm hover:shadow-md">
                                    <i class="fa-solid fa-chart-line mr-2"></i>
                                    Traffic
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Tidak ada data interface</p>
                                    <p class="text-gray-400 text-sm mt-1">Pastikan router MikroTik terhubung dengan benar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.interface-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-layouts.app>
