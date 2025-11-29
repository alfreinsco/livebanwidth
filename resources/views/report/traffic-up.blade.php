<x-layouts.app>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Report Traffic UP</h2>
        <p class="text-gray-600">Report Data Traffic UP - Monitoring Real-time</p>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-center space-x-2">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-cyan-600"></div>
            <span class="text-gray-600">Memuat data...</span>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state" class="hidden bg-white rounded-lg shadow-md p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal Memuat Data</h3>
            <p class="text-sm text-gray-500 mb-4" id="error-message">Terjadi kesalahan saat memuat data.</p>
            <button onclick="loadReportData()"
                class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                <i class="fa-solid fa-rotate mr-2"></i>Coba Lagi
            </button>
        </div>
    </div>

    <!-- Content State -->
    <div id="content-state" class="hidden bg-white rounded-lg shadow-md p-6">
        <div id="load"></div>
    </div>

    <script>
        function loadReportData() {
            // Show loading, hide error and content
            document.getElementById('loading-state').classList.remove('hidden');
            document.getElementById('error-state').classList.add('hidden');
            document.getElementById('content-state').classList.add('hidden');

            fetch('{{ route('report-up.load') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('load').innerHTML = html;
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('content-state').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading report data:', error);
                    document.getElementById('error-message').textContent = 'Terjadi kesalahan saat memuat data: ' +
                        error.message;
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('error-state').classList.remove('hidden');
                });
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadReportData();
            // Auto-refresh every 1 second
            setInterval(loadReportData, 1000);
        });
    </script>
</x-layouts.app>
