<x-layouts.app>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Interfaces</h1>
                <p class="text-sm text-gray-600 mt-1">Daftar interface pada router MikroTik</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="loadInterfaceData()" id="refresh-btn"
                    class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">
                    <i class="fa-solid fa-rotate mr-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="relative">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 border-4 border-cyan-200 rounded-full animate-ping"></div>
                    </div>
                    <div class="relative w-12 h-12 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <div class="mt-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Memuat Data Interface</h3>
                    <p class="text-sm text-gray-500">Mengambil data dari router MikroTik...</p>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div id="error-state" class="hidden">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl p-8 max-w-md w-full shadow-lg border border-red-100">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Gagal Memuat Data</h3>
                        <p class="text-sm text-gray-600 mb-6" id="error-text">Terjadi kesalahan saat memuat data interface</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button onclick="loadInterfaceData()"
                                class="px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors font-medium shadow-md hover:shadow-lg">
                                <i class="fa-solid fa-arrow-rotate-right mr-2"></i>Coba Lagi
                            </button>
                            <a href="{{ route('mikrotik.index') }}"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center">
                                <i class="fa-solid fa-server mr-2"></i>Kelola Router
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content State -->
        <div id="content-state" class="hidden">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Interfaces</p>
                            <p class="text-3xl font-bold" id="stat-total">0</p>
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
                            <p class="text-3xl font-bold" id="stat-running">0</p>
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
                            <p class="text-3xl font-bold" id="stat-down">0</p>
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
                            <p class="text-3xl font-bold" id="stat-rate">0%</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-2xl"></i>
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
                        <tbody id="interface-tbody" class="bg-white divide-y divide-gray-200">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helper function untuk mendapatkan icon dan warna berdasarkan tipe interface
        function getInterfaceTypeStyle(type) {
            const typeLower = (type || '').toLowerCase();
            let icon = 'fa-ethernet';
            let bgColor = 'bg-blue-100';
            let iconColor = 'text-blue-600';

            if (typeLower.includes('ether')) {
                icon = 'fa-ethernet';
                bgColor = 'bg-blue-100';
                iconColor = 'text-blue-600';
            } else if (typeLower.includes('wlan') || typeLower.includes('wireless')) {
                icon = 'fa-wifi';
                bgColor = 'bg-purple-100';
                iconColor = 'text-purple-600';
            } else if (typeLower.includes('bridge')) {
                icon = 'fa-network-wired';
                bgColor = 'bg-cyan-100';
                iconColor = 'text-cyan-600';
            } else if (typeLower.includes('vlan')) {
                icon = 'fa-tags';
                bgColor = 'bg-green-100';
                iconColor = 'text-green-600';
            } else if (typeLower.includes('ppp')) {
                icon = 'fa-plug';
                bgColor = 'bg-orange-100';
                iconColor = 'text-orange-600';
            }

            return { icon, bgColor, iconColor };
        }

        // Format number
        function formatNumber(num) {
            if (num === null || num === undefined) return '0';
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Load interface data
        function loadInterfaceData() {
            const loadingState = document.getElementById('loading-state');
            const contentState = document.getElementById('content-state');
            const errorState = document.getElementById('error-state');

            // Show loading, hide content and error
            loadingState.classList.remove('hidden');
            contentState.classList.add('hidden');
            errorState.classList.add('hidden');

            fetch('{{ route('interface.data') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const interfaces = data.data || [];
                    
                    // Calculate statistics
                    let totalInterface = interfaces.length;
                    let runningInterface = 0;
                    let downInterface = 0;

                    interfaces.forEach(iface => {
                        if ((iface.running || 'false') === 'true') {
                            runningInterface++;
                        } else {
                            downInterface++;
                        }
                    });

                    const activeRate = totalInterface > 0 ? Math.round((runningInterface / totalInterface) * 100) : 0;

                    // Update statistics
                    document.getElementById('stat-total').textContent = formatNumber(totalInterface);
                    document.getElementById('stat-running').textContent = formatNumber(runningInterface);
                    document.getElementById('stat-down').textContent = formatNumber(downInterface);
                    document.getElementById('stat-rate').textContent = activeRate + '%';

                    // Update table
                    const tbody = document.getElementById('interface-tbody');
                    tbody.innerHTML = '';

                    if (interfaces.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data interface</p>
                                        <p class="text-gray-400 text-sm mt-1">Pastikan router MikroTik terhubung dengan benar</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    } else {
                        interfaces.forEach(item => {
                            const isRunning = (item.running || 'false') === 'true';
                            const typeStyle = getInterfaceTypeStyle(item.type);
                            
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-gray-50 transition-colors interface-row';
                            
                            const statusBadge = isRunning
                                ? '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><span class="mr-2 h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>Running</span>'
                                : '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><span class="mr-2 h-2 w-2 rounded-full bg-red-500"></span>Down</span>';

                            const infoHtml = `
                                ${item.speed ? `<span class="text-xs text-gray-600"><i class="fa-solid fa-tachometer-alt mr-1"></i>${item.speed}</span>` : ''}
                                ${item['link-downs'] ? `<span class="text-xs text-gray-600"><i class="fa-solid fa-arrow-down mr-1"></i>${item['link-downs']} downs</span>` : ''}
                            `;

                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 ${typeStyle.bgColor} rounded-lg flex items-center justify-center mr-3">
                                            <i class="fa-solid ${typeStyle.icon} ${typeStyle.iconColor}"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">${item.name || '-'}</div>
                                            ${item['default-name'] && item['default-name'] !== item.name ? `<div class="text-xs text-gray-500">${item['default-name']}</div>` : ''}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        ${item.type || '-'}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">${item['mac-address'] || '-'}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${item.mtu || '-'}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${statusBadge}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        ${infoHtml.trim() || '<span class="text-xs text-gray-400">-</span>'}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/interface/${encodeURIComponent(item.name || '')}/traffic"
                                        class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium shadow-sm hover:shadow-md">
                                        <i class="fa-solid fa-chart-line mr-2"></i>
                                        Traffic
                                    </a>
                                </td>
                            `;
                            
                            tbody.appendChild(row);
                        });
                    }

                    // Hide loading, show content
                    loadingState.classList.add('hidden');
                    contentState.classList.remove('hidden');
                } else {
                    throw new Error(data.message || 'Gagal memuat data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingState.classList.add('hidden');
                errorState.classList.remove('hidden');
                const errorText = document.getElementById('error-text');
                if (errorText) {
                    errorText.textContent = error.message || 'Terjadi kesalahan saat memuat data interface. Pastikan router dapat diakses dan kredensial benar.';
                }
            });
        }

        // Search functionality
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
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
            }
        }

        // Load data when page is ready
        document.addEventListener('DOMContentLoaded', function() {
            loadInterfaceData();
            setupSearch();
        });
    </script>
</x-layouts.app>
