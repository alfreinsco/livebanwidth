<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">PPPoE Secret</h1>
            <p class="text-sm text-gray-600">Manajemen akun PPPoE pada router MikroTik.</p>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-100 px-4 py-3 text-sm text-gray-600">
            <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-cyan-600"></div>
                <span>Memuat data PPPoE...</span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @for($i = 0; $i < 5; $i++)
                <div class="animate-pulse flex space-x-4">
                    <div class="flex-1 space-y-2 py-1">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state" class="hidden bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-center">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal Memuat Data</h3>
                <p class="text-sm text-gray-500 mb-4" id="error-message">Terjadi kesalahan saat memuat data PPPoE.</p>
                <button onclick="loadPPPoEData()" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                    <i class="fa-solid fa-rotate mr-2"></i>Coba Lagi
                </button>
            </div>
        </div>
    </div>

    <!-- Content State -->
    <div id="content-state" class="hidden bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-100 px-4 py-3 text-sm text-gray-600 flex justify-between">
            <span>Total Secret: <span class="font-semibold" id="total-secret">0</span></span>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Username</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Service</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Profile</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Local Address</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Remote Address</th>
                </tr>
            </thead>
            <tbody id="pppoe-table-body" class="divide-y divide-gray-100 bg-white">
                <!-- Data akan diisi via JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
        function loadPPPoEData() {
            // Show loading, hide error and content
            document.getElementById('loading-state').classList.remove('hidden');
            document.getElementById('error-state').classList.add('hidden');
            document.getElementById('content-state').classList.add('hidden');

            fetch('{{ route("pppoe.secret.data") }}')
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update total secret
                        document.getElementById('total-secret').textContent = data.data.totalsecret;

                        // Populate table
                        const tbody = document.getElementById('pppoe-table-body');
                        tbody.innerHTML = '';

                        if (data.data.secret && data.data.secret.length > 0) {
                            data.data.secret.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="px-4 py-2">${item.name || '-'}</td>
                                    <td class="px-4 py-2">${item.service || '-'}</td>
                                    <td class="px-4 py-2">${item.profile || '-'}</td>
                                    <td class="px-4 py-2">${item['local-address'] || '-'}</td>
                                    <td class="px-4 py-2">${item['remote-address'] || '-'}</td>
                                `;
                                tbody.appendChild(row);
                            });
                        } else {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Tidak ada data PPPoE secret.
                                    </td>
                                </tr>
                            `;
                        }

                        // Show content, hide loading
                        document.getElementById('loading-state').classList.add('hidden');
                        document.getElementById('content-state').classList.remove('hidden');
                    } else {
                        throw new Error(data.message || 'Gagal memuat data');
                    }
                })
                .catch(error => {
                    console.error('Error loading PPPoE data:', error);
                    document.getElementById('error-message').textContent = error.message || 'Terjadi kesalahan saat memuat data PPPoE.';
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('error-state').classList.remove('hidden');
                });
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadPPPoEData();
        });
    </script>
</x-layouts.app>


