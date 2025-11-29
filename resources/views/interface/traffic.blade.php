<x-layouts.app>
    @php
        $interfaceDetail = $interface_detail ?? null;
        $interfaceName = $interface_name ?? 'Unknown';
    @endphp

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('interface.index') }}" class="text-cyan-600 hover:text-cyan-700 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800">Traffic Monitor</h1>
                </div>
                <p class="text-sm text-gray-600 ml-8">{{ $interfaceName }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="location.reload()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    <i class="fa-solid fa-rotate mr-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Interface Info Card -->
        @if ($interfaceDetail)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-cyan-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Type</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $interfaceDetail['type'] ?? 'N/A' }}</p>
                        </div>
                        <i class="fa-solid fa-network-wired text-cyan-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">MTU</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $interfaceDetail['mtu'] ?? 'N/A' }}</p>
                        </div>
                        <i class="fa-solid fa-gauge text-blue-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <p class="text-lg font-semibold text-gray-800">
                                @if (($interfaceDetail['running'] ?? 'false') === 'true')
                                    <span class="text-green-600">Running</span>
                                @else
                                    <span class="text-red-600">Down</span>
                                @endif
                            </p>
                        </div>
                        <i class="fa-solid fa-signal text-green-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">MAC Address</p>
                            <p class="text-sm font-mono text-gray-800">{{ $interfaceDetail['mac-address'] ?? 'N/A' }}
                            </p>
                        </div>
                        <i class="fa-solid fa-fingerprint text-purple-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Real-time Traffic Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Upload (TX) -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Upload (TX)</p>
                    <p class="text-4xl font-bold" id="tx-value">0</p>
                    <p class="text-green-100 text-sm mt-1" id="tx-mbps">0 Mbps</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-arrow-up text-3xl"></i>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white rounded-full h-2 transition-all duration-300" id="tx-bar" style="width: 0%">
                </div>
            </div>
        </div>

        <!-- Download (RX) -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Download (RX)</p>
                    <p class="text-4xl font-bold" id="rx-value">0</p>
                    <p class="text-blue-100 text-sm mt-1" id="rx-mbps">0 Mbps</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-arrow-down text-3xl"></i>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white rounded-full h-2 transition-all duration-300" id="rx-bar" style="width: 0%">
                </div>
            </div>
        </div>
    </div>

    <!-- Traffic Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Traffic Chart (Real-time)</h2>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Update setiap 3 detik</span>
                <span class="h-2 w-2 bg-green-500 rounded-full animate-pulse" id="status-indicator"></span>
            </div>
        </div>
        <canvas id="trafficChart" height="100"></canvas>
    </div>

    <!-- Traffic History Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Traffic History</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Upload (TX)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Download (RX)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total</th>
                    </tr>
                </thead>
                <tbody id="traffic-history" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const interfaceName = '{{ $interfaceName }}';
        const apiUrl = '{{ route('interface.traffic.api', $interfaceName) }}';
        const saveUrl = '{{ route('interface.traffic.save', $interfaceName) }}';

        // Format bytes
        function formatBytes(bytes) {
            if (bytes === 0) return '0 bps';
            const k = 1000;
            const sizes = ['bps', 'Kbps', 'Mbps', 'Gbps'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
        }

        // Format number dengan koma
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num));
        }

        // Chart setup
        const ctx = document.getElementById('trafficChart').getContext('2d');
        const maxDataPoints = 30;
        let txData = [];
        let rxData = [];
        let labels = [];

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Upload (TX)',
                    data: txData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Download (RX)',
                    data: rxData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + formatBytes(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatBytes(value);
                            }
                        }
                    }
                },
                animation: {
                    duration: 0
                }
            }
        });

        // Traffic history
        const history = [];
        const maxHistory = 10;

        function addToHistory(tx, rx) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID');

            history.unshift({
                time: timeStr,
                tx: tx,
                rx: rx,
                total: tx + rx
            });

            if (history.length > maxHistory) {
                history.pop();
            }

            updateHistoryTable();
        }

        function updateHistoryTable() {
            const tbody = document.getElementById('traffic-history');
            tbody.innerHTML = '';

            history.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.time}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium text-green-600">${formatBytes(item.tx)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium text-blue-600">${formatBytes(item.rx)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">${formatBytes(item.total)}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Update progress bar
        function updateProgressBar(elementId, value, maxValue = 1000000000) {
            const percentage = Math.min((value / maxValue) * 100, 100);
            document.getElementById(elementId).style.width = percentage + '%';
        }

        // Save traffic data to database (background job)
        function saveTrafficDataToDatabase() {
            fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Job berhasil di-queue, tidak perlu menampilkan pesan
                        console.log('Traffic data save job queued successfully');
                    } else {
                        // Error di-handle secara silent agar tidak mengganggu tampilan
                        console.warn('Failed to queue traffic data save job:', data.message);
                    }
                })
                .catch(error => {
                    // Error di-handle secara silent agar tidak mengganggu tampilan
                    console.warn('Error queueing traffic data save job:', error);
                });
        }

        // Fetch traffic data
        function fetchTrafficData() {
            fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tx = data.data.tx;
                        const rx = data.data.rx;
                        const txMbps = data.data.tx_mbps;
                        const rxMbps = data.data.rx_mbps;

                        // Update stats
                        document.getElementById('tx-value').textContent = formatNumber(tx);
                        document.getElementById('tx-mbps').textContent = txMbps + ' Mbps';
                        document.getElementById('rx-value').textContent = formatNumber(rx);
                        document.getElementById('rx-mbps').textContent = rxMbps + ' Mbps';

                        // Update progress bars (max 1 Gbps)
                        updateProgressBar('tx-bar', tx, 1000000000);
                        updateProgressBar('rx-bar', rx, 1000000000);

                        // Update chart
                        const now = new Date();
                        const timeLabel = now.toLocaleTimeString('id-ID');

                        labels.push(timeLabel);
                        txData.push(tx);
                        rxData.push(rx);

                        if (labels.length > maxDataPoints) {
                            labels.shift();
                            txData.shift();
                            rxData.shift();
                        }

                        chart.update('none');

                        // Add to history
                        addToHistory(tx, rx);

                        // Update status indicator
                        document.getElementById('status-indicator').classList.remove('bg-red-500');
                        document.getElementById('status-indicator').classList.add('bg-green-500');

                        // Save data to database in background (non-blocking)
                        saveTrafficDataToDatabase();
                    } else {
                        document.getElementById('status-indicator').classList.remove('bg-green-500');
                        document.getElementById('status-indicator').classList.add('bg-red-500');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('status-indicator').classList.remove('bg-green-500');
                    document.getElementById('status-indicator').classList.add('bg-red-500');
                });
        }

        // Start fetching data
        fetchTrafficData();
        setInterval(fetchTrafficData, 3000); // Update every 3 seconds
    </script>
</x-layouts.app>
