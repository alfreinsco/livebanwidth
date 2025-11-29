<x-layouts.app>
    <!-- Loading State - Modern Design -->
    <div id="loading-state" class="relative">
        <div class="flex flex-col items-center justify-center py-20">
            <div class="relative">
                <!-- Animated circles -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 border-4 border-cyan-200 rounded-full animate-ping"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 border-4 border-cyan-300 rounded-full animate-pulse"></div>
                </div>
                <div class="relative w-12 h-12 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin">
                </div>
            </div>
            <div class="mt-8 text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Memuat Data Dashboard</h3>
                <p class="text-sm text-gray-500">Mengambil data dari router MikroTik...</p>
            </div>
            <!-- Skeleton Loading -->
            <div class="mt-8 w-full max-w-7xl">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @for ($i = 0; $i < 8; $i++)
                        <div class="bg-white rounded-lg shadow-md p-6 animate-pulse">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="h-4 bg-gray-200 rounded w-24 mb-3"></div>
                                    <div class="h-8 bg-gray-200 rounded w-16 mb-2"></div>
                                    <div class="h-3 bg-gray-200 rounded w-32"></div>
                                </div>
                                <div class="w-16 h-16 bg-gray-200 rounded-lg"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Error State - Modern Design -->
    <div id="error-state" class="hidden">
        <div class="flex flex-col items-center justify-center py-20">
            <div
                class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl p-8 max-w-md w-full shadow-lg border border-red-100">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Gagal Memuat Data</h3>
                    <p class="text-sm text-gray-600 mb-6" id="error-text">Terjadi kesalahan saat memuat data dari router
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button onclick="loadDashboardData()"
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

    <!-- Dashboard Content -->
    <div id="dashboard-content" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Router Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Router</p>
                        <p class="text-lg font-bold text-gray-800 truncate" id="router-identity">-</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-600">
                                <i class="fa-solid fa-microchip mr-1"></i><span id="router-model">-</span>
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-server text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Interfaces -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Interfaces</p>
                        <p class="text-3xl font-bold text-gray-800" id="total-interface">0</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-green-600">
                                <i class="fa-solid fa-circle-check mr-1"></i><span id="interface-running">0</span>
                                Running
                            </span>
                            <span class="text-xs text-red-600">
                                <i class="fa-solid fa-circle-xmark mr-1"></i><span id="interface-down">0</span> Down
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-ethernet text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- CPU Usage -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">CPU Usage</p>
                        <p class="text-3xl font-bold text-gray-800" id="cpu-load">0%</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-600">
                                <i class="fa-solid fa-clock mr-1"></i><span id="uptime">-</span>
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-gauge-high text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Traffic Logs -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Traffic Logs</p>
                        <p class="text-3xl font-bold text-gray-800" id="total-reports">0</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-green-600">
                                <i class="fa-solid fa-check mr-1"></i><span id="reports-24h">0</span> (24h)
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-list-ul text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- PPPoE Secret -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">PPPoE Secret</p>
                        <p class="text-3xl font-bold text-gray-800" id="total-secret">0</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-green-600">
                                <i class="fa-solid fa-circle-check mr-1"></i><span id="secret-active">0</span> Active
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-network-wired text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Hotspot Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Hotspot Users</p>
                        <p class="text-3xl font-bold text-gray-800" id="total-hotspot-users">0</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-green-600">
                                <i class="fa-solid fa-circle-check mr-1"></i><span id="hotspot-active">0</span> Active
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-users text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- System Resources -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Free Memory</p>
                        <p class="text-lg font-bold text-gray-800" id="free-memory">-</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-600">
                                <i class="fa-solid fa-hard-drive mr-1"></i><span id="free-hdd">-</span> Free
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-memory text-pink-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Active Users</p>
                        <p class="text-3xl font-bold text-gray-800" id="total-user-active">0</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-600">
                                <i class="fa-solid fa-user-check mr-1"></i>Logged in
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user text-cyan-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Router Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Router</h3>
                <div class="space-y-3" id="router-info">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Identity</span>
                        <span class="text-sm font-medium text-gray-800" id="info-identity">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Model</span>
                        <span class="text-sm font-medium text-gray-800" id="info-model">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Board Name</span>
                        <span class="text-sm font-medium text-gray-800" id="info-boardname">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Version</span>
                        <span class="text-sm font-medium text-gray-800" id="info-version">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Uptime</span>
                        <span class="text-sm font-medium text-gray-800" id="info-uptime">-</span>
                    </div>
                </div>
            </div>

            <!-- System Resources -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">System Resources</h3>
                <div class="space-y-3" id="system-resources">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">CPU Load</span>
                        <span class="text-sm font-medium text-gray-800" id="info-cpu">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Free Memory</span>
                        <span class="text-sm font-medium text-gray-800" id="info-memory">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Free HDD Space</span>
                        <span class="text-sm font-medium text-gray-800" id="info-hdd">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Total Interfaces</span>
                        <span class="text-sm font-medium text-gray-800" id="info-total-interface">-</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Interfaces Running</span>
                        <span class="text-sm font-medium text-green-600" id="info-interface-running">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Traffic Monitor Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Traffic Monitor</h3>
                    <p class="text-sm text-gray-500 mt-1">Real-time traffic monitoring dari interface aktif</p>
                </div>
                <div class="flex items-center space-x-3">
                    <select id="traffic-interface-select"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">Pilih Interface...</option>
                    </select>
                    <button onclick="startTrafficMonitor()" id="start-monitor-btn"
                        class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">
                        <i class="fa-solid fa-play mr-2"></i>Start Monitor
                    </button>
                    <button onclick="stopTrafficMonitor()" id="stop-monitor-btn"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium hidden">
                        <i class="fa-solid fa-stop mr-2"></i>Stop Monitor
                    </button>
                </div>
            </div>

            <!-- Traffic Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">TX (Upload)</p>
                            <p class="text-2xl font-bold text-blue-600" id="traffic-tx">0 Mbps</p>
                            <p class="text-xs text-gray-500 mt-1" id="traffic-tx-bits">0 bps</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-arrow-up text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">RX (Download)</p>
                            <p class="text-2xl font-bold text-green-600" id="traffic-rx">0 Mbps</p>
                            <p class="text-xs text-gray-500 mt-1" id="traffic-rx-bits">0 bps</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-arrow-down text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Total Traffic</p>
                            <p class="text-2xl font-bold text-purple-600" id="traffic-total">0 Mbps</p>
                            <p class="text-xs text-gray-500 mt-1" id="traffic-interface-name">-</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Traffic Chart -->
            <div class="relative">
                <canvas id="traffic-chart" height="100"></canvas>
            </div>
        </div>

        <!-- Interface List -->
        <div id="interface-list-container" class="bg-white rounded-lg shadow-md p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Interface</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                MTU</th>
                        </tr>
                    </thead>
                    <tbody id="interface-tbody" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Helper functions
        function formatBytes(bytes, precision = 2) {
            if (!bytes || bytes == 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const base = Math.log(bytes) / Math.log(1024);
            return Math.round(Math.pow(1024, base - Math.floor(base)) * Math.pow(10, precision)) / Math.pow(10, precision) +
                ' ' + units[Math.floor(base)];
        }

        function formatBytesPerSecond(bits, precision = 2) {
            if (!bits || bits == 0) return '0 bps';
            if (bits < 1000) return bits.toFixed(0) + ' bps';
            if (bits < 1000000) return (bits / 1000).toFixed(precision) + ' Kbps';
            return (bits / 1000000).toFixed(precision) + ' Mbps';
        }

        function formatUptime(uptime) {
            if (!uptime) return 'N/A';
            const days = Math.floor(uptime / 86400);
            const hours = Math.floor((uptime % 86400) / 3600);
            const minutes = Math.floor((uptime % 3600) / 60);
            return days + 'd ' + hours + 'h ' + minutes + 'm';
        }

        function formatNumber(num) {
            if (num === null || num === undefined) return '0';
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function loadDashboardData() {
            const loadingState = document.getElementById('loading-state');
            const dashboardContent = document.getElementById('dashboard-content');
            const errorState = document.getElementById('error-state');

            // Show loading, hide content and error
            loadingState.classList.remove('hidden');
            dashboardContent.classList.add('hidden');
            errorState.classList.add('hidden');

            fetch('{{ route('dashboard.data') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const d = data.data;

                        // Update widgets
                        document.getElementById('router-identity').textContent = d.identity || 'N/A';
                        document.getElementById('router-model').textContent = d.model || 'N/A';
                        document.getElementById('total-interface').textContent = formatNumber(d.totalinterface);
                        document.getElementById('interface-running').textContent = formatNumber(d.interfacerunning);
                        document.getElementById('interface-down').textContent = formatNumber((d.totalinterface || 0) - (
                            d.interfacerunning || 0));
                        document.getElementById('cpu-load').textContent = (d.cpu || '0') + '%';
                        document.getElementById('uptime').textContent = formatUptime(d.uptime);
                        document.getElementById('total-reports').textContent = formatNumber(d.totalreports);
                        document.getElementById('reports-24h').textContent = formatNumber(d.reports24h);
                        document.getElementById('total-secret').textContent = formatNumber(d.totalsecret);
                        document.getElementById('secret-active').textContent = formatNumber(d.secretactive);
                        document.getElementById('total-hotspot-users').textContent = formatNumber(d.totalhotspotusers);
                        document.getElementById('hotspot-active').textContent = formatNumber(d.hotspotactive);
                        document.getElementById('free-memory').textContent = formatBytes(d.freememory);
                        document.getElementById('free-hdd').textContent = formatBytes(d.freehdd);
                        document.getElementById('total-user-active').textContent = formatNumber(d.totaluseractive);

                        // Update info sections
                        document.getElementById('info-identity').textContent = d.identity || 'N/A';
                        document.getElementById('info-model').textContent = d.model || 'N/A';
                        document.getElementById('info-boardname').textContent = d.boardname || 'N/A';
                        document.getElementById('info-version').textContent = d.version || 'N/A';
                        document.getElementById('info-uptime').textContent = formatUptime(d.uptime);
                        document.getElementById('info-cpu').textContent = (d.cpu || '0') + '%';
                        document.getElementById('info-memory').textContent = formatBytes(d.freememory);
                        document.getElementById('info-hdd').textContent = formatBytes(d.freehdd);
                        document.getElementById('info-total-interface').textContent = formatNumber(d.totalinterface);
                        document.getElementById('info-interface-running').textContent = formatNumber(d
                            .interfacerunning);

                        // Update interface list
                        if (d.interface && d.interface.length > 0) {
                            const tbody = document.getElementById('interface-tbody');
                            tbody.innerHTML = '';
                            d.interface.slice(0, 5).forEach(iface => {
                                const row = document.createElement('tr');
                                row.className = 'hover:bg-gray-50';
                                const status = (iface.running === 'true') ?
                                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Running</span>' :
                                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Down</span>';
                                row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${iface.name || 'N/A'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${iface.type || 'N/A'}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${status}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${iface.mtu || 'N/A'}</td>
                            `;
                                tbody.appendChild(row);
                            });
                            document.getElementById('interface-list-container').classList.remove('hidden');

                            // Populate interface select for traffic monitor
                            populateInterfaceSelect(d.interface);
                        }

                        // Hide loading, show content
                        loadingState.classList.add('hidden');
                        dashboardContent.classList.remove('hidden');
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
                        errorText.textContent = error.message ||
                            'Terjadi kesalahan saat memuat data dari router. Pastikan router dapat diakses dan kredensial benar.';
                    }
                });
        }

        // Traffic Monitor Variables
        let trafficChart = null;
        let trafficMonitorInterval = null;
        let trafficData = {
            labels: [],
            tx: [],
            rx: []
        };
        const maxDataPoints = 30;

        // Initialize Traffic Chart
        function initTrafficChart() {
            const ctx = document.getElementById('traffic-chart');
            if (!ctx) return;

            trafficChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trafficData.labels,
                    datasets: [{
                        label: 'TX (Upload)',
                        data: trafficData.tx,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'RX (Download)',
                        data: trafficData.rx,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Mbps'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Waktu'
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        // Start Traffic Monitor
        function startTrafficMonitor() {
            const interfaceSelect = document.getElementById('traffic-interface-select');
            const selectedInterface = interfaceSelect.value;

            if (!selectedInterface) {
                alert('Silakan pilih interface terlebih dahulu');
                return;
            }

            // Reset chart data
            trafficData = {
                labels: [],
                tx: [],
                rx: []
            };

            // Update buttons
            document.getElementById('start-monitor-btn').classList.add('hidden');
            document.getElementById('stop-monitor-btn').classList.remove('hidden');
            interfaceSelect.disabled = true;

            // Start monitoring
            trafficMonitorInterval = setInterval(() => {
                fetch(`/interface/${selectedInterface}/traffic/api`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const txMbps = data.data.tx_mbps || 0;
                            const rxMbps = data.data.rx_mbps || 0;
                            const txBits = data.data.tx || 0;
                            const rxBits = data.data.rx || 0;

                            // Update stats
                            document.getElementById('traffic-tx').textContent = txMbps.toFixed(2) + ' Mbps';
                            document.getElementById('traffic-rx').textContent = rxMbps.toFixed(2) + ' Mbps';
                            document.getElementById('traffic-total').textContent = (txMbps + rxMbps).toFixed(
                                2) + ' Mbps';
                            document.getElementById('traffic-tx-bits').textContent = formatBytesPerSecond(
                                txBits) + '/s';
                            document.getElementById('traffic-rx-bits').textContent = formatBytesPerSecond(
                                rxBits) + '/s';
                            document.getElementById('traffic-interface-name').textContent = selectedInterface;

                            // Add to chart data
                            const now = new Date().toLocaleTimeString('id-ID');
                            trafficData.labels.push(now);
                            trafficData.tx.push(txMbps);
                            trafficData.rx.push(rxMbps);

                            // Limit data points
                            if (trafficData.labels.length > maxDataPoints) {
                                trafficData.labels.shift();
                                trafficData.tx.shift();
                                trafficData.rx.shift();
                            }

                            // Update chart
                            if (trafficChart) {
                                trafficChart.data.labels = trafficData.labels;
                                trafficChart.data.datasets[0].data = trafficData.tx;
                                trafficChart.data.datasets[1].data = trafficData.rx;
                                trafficChart.update('none');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching traffic data:', error);
                    });
            }, 2000); // Update every 2 seconds
        }

        // Stop Traffic Monitor
        function stopTrafficMonitor() {
            if (trafficMonitorInterval) {
                clearInterval(trafficMonitorInterval);
                trafficMonitorInterval = null;
            }

            // Update buttons
            document.getElementById('start-monitor-btn').classList.remove('hidden');
            document.getElementById('stop-monitor-btn').classList.add('hidden');
            document.getElementById('traffic-interface-select').disabled = false;
        }

        // Populate Interface Select
        function populateInterfaceSelect(interfaces) {
            const select = document.getElementById('traffic-interface-select');
            if (!select) return;

            // Clear existing options except first
            select.innerHTML = '<option value="">Pilih Interface...</option>';

            if (interfaces && interfaces.length > 0) {
                interfaces.forEach(iface => {
                    if (iface.running === 'true') {
                        const option = document.createElement('option');
                        option.value = iface.name;
                        option.textContent = `${iface.name} (${iface.type || 'N/A'})`;
                        select.appendChild(option);
                    }
                });
            }
        }

        // Load data when page is ready
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            initTrafficChart();
        });
    </script>
</x-layouts.app>
