<x-layouts.app>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Search Traffic UP</h2>
        <p class="text-gray-600">Cari data traffic berdasarkan rentang tanggal</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form id="search-form" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <b>Mulai Tanggal:</b>
                </label>
                <input type="date" 
                       name="tgl_awal" 
                       id="tgl_awal" 
                       value="{{ $tgl_awal ?? date('Y-m-d') }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <b>Sampai Tanggal:</b>
                </label>
                <input type="date" 
                       name="tgl_akhir" 
                       id="tgl_akhir" 
                       value="{{ $tgl_akhir ?? date('Y-m-d') }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <div class="flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors font-medium">
                    <i class="fa-solid fa-search mr-2"></i>Search
                </button>
                <button type="button" onclick="resetSearch()"
                        class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                    <i class="fa-solid fa-rotate mr-2"></i>Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-center space-x-2">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-cyan-600"></div>
            <span class="text-gray-600">Memuat data...</span>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal Memuat Data</h3>
            <p class="text-sm text-gray-500 mb-4" id="error-message">Terjadi kesalahan saat memuat data.</p>
            <button onclick="loadSearchData()" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                <i class="fa-solid fa-rotate mr-2"></i>Coba Lagi
            </button>
        </div>
    </div>

    <!-- Info State -->
    <div id="info-state" class="hidden bg-cyan-50 border border-cyan-200 rounded-lg p-4 mb-6">
        <p class="text-cyan-800 font-medium" id="info-message"></p>
    </div>

    <!-- Content State -->
    <div id="content-state" class="hidden bg-white rounded-lg shadow-md p-6">
        <div id="pagination-info" class="mb-4 flex items-center justify-between">
            <!-- Will be populated by JavaScript -->
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MikroTik Router</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interface</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">TX (Mbps)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">RX (Mbps)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total (Mbps)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    </tr>
                </thead>
                <tbody id="data-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="pagination-controls" class="mt-6">
            <!-- Pagination will be populated by JavaScript -->
        </div>
    </div>

    <script>
        const apiUrl = '{{ route("search.report.data") }}';
        let currentPage = 1;
        let currentPerPage = 15;

        // Update URL dengan state saat ini
        function updateURL() {
            const tglAwal = document.getElementById('tgl_awal').value;
            const tglAkhir = document.getElementById('tgl_akhir').value;
            
            const url = new URL(window.location.href);
            url.searchParams.set('tgl_awal', tglAwal || '');
            url.searchParams.set('tgl_akhir', tglAkhir || '');
            url.searchParams.set('page', currentPage);
            url.searchParams.set('per_page', currentPerPage);
            
            // Hapus parameter jika kosong
            if (!tglAwal) url.searchParams.delete('tgl_awal');
            if (!tglAkhir) url.searchParams.delete('tgl_akhir');
            
            // Update URL tanpa reload halaman
            window.history.pushState({}, '', url.toString());
        }

        // Baca state dari URL
        function loadStateFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            const tglAwal = urlParams.get('tgl_awal');
            const tglAkhir = urlParams.get('tgl_akhir');
            const page = urlParams.get('page');
            const perPage = urlParams.get('per_page');

            if (tglAwal) {
                document.getElementById('tgl_awal').value = tglAwal;
            }
            if (tglAkhir) {
                document.getElementById('tgl_akhir').value = tglAkhir;
            }
            if (page) {
                currentPage = parseInt(page);
            }
            if (perPage) {
                currentPerPage = parseInt(perPage);
            }
        }

        // Format date untuk display
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Format number dengan koma
        function formatNumber(num) {
            if (num === null || num === undefined) return '-';
            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
        }

        // Load search data
        function loadSearchData(page = null) {
            const tglAwal = document.getElementById('tgl_awal').value;
            const tglAkhir = document.getElementById('tgl_akhir').value;
            
            // Gunakan page dari parameter atau currentPage
            if (page !== null) {
                currentPage = page;
            }
            const perPage = currentPerPage;

            if (!tglAwal || !tglAkhir) {
                alert('Silakan pilih tanggal awal dan akhir');
                return;
            }

            // Update URL dengan state saat ini
            updateURL();

            // Show loading, hide error and content
            document.getElementById('loading-state').classList.remove('hidden');
            document.getElementById('error-state').classList.add('hidden');
            document.getElementById('content-state').classList.add('hidden');
            document.getElementById('info-state').classList.add('hidden');

            const url = new URL(apiUrl);
            url.searchParams.set('tgl_awal', tglAwal);
            url.searchParams.set('tgl_akhir', tglAkhir);
            url.searchParams.set('page', currentPage);
            url.searchParams.set('per_page', perPage);

            fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update info message
                    document.getElementById('info-message').textContent = data.view_tgl;
                    document.getElementById('info-state').classList.remove('hidden');

                    // Populate table
                    const tbody = document.getElementById('data-table-body');
                    tbody.innerHTML = '';

                    if (data.data && data.data.length > 0) {
                        data.data.forEach((item, index) => {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-gray-50';
                            
                            const rowNumber = data.pagination.from + index;
                            const mikrotikHtml = item.mikrotik 
                                ? `<div class="flex items-center">
                                    <i class="fa-solid fa-router text-cyan-600 mr-2"></i>
                                    <div>
                                        <div class="font-medium">${item.mikrotik.name}</div>
                                        <div class="text-xs text-gray-500">${item.mikrotik.ip_address}</div>
                                    </div>
                                </div>`
                                : '<span class="text-gray-400 italic">-</span>';
                            
                            const interfaceHtml = item.interface_name 
                                ? `<span class="font-mono">${item.interface_name}</span>`
                                : '<span class="text-gray-400 italic">-</span>';
                            
                            const txMbps = item.tx_mbps !== null ? formatNumber(item.tx_mbps) : '-';
                            const rxMbps = item.rx_mbps !== null ? formatNumber(item.rx_mbps) : '-';
                            const totalMbps = (item.tx_mbps !== null && item.rx_mbps !== null) 
                                ? formatNumber(parseFloat(item.tx_mbps) + parseFloat(item.rx_mbps))
                                : '-';
                            
                            const dateTime = item.created_at || item.time;
                            const dateTimeHtml = dateTime ? formatDate(dateTime) : '<span class="text-gray-400 italic">-</span>';
                            
                            const messageHtml = item.text 
                                ? item.text 
                                : '<span class="text-gray-400 italic">Traffic Log</span>';

                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rowNumber}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${mikrotikHtml}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${interfaceHtml}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    ${item.tx_mbps !== null ? `<span class="font-medium text-green-600">${txMbps}</span>` : '<span class="text-gray-400 italic">-</span>'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    ${item.rx_mbps !== null ? `<span class="font-medium text-blue-600">${rxMbps}</span>` : '<span class="text-gray-400 italic">-</span>'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    ${(item.tx_mbps !== null && item.rx_mbps !== null) ? `<span class="font-semibold">${totalMbps}</span>` : '<span class="text-gray-400 italic">-</span>'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${dateTimeHtml}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">${messageHtml}</td>
                            `;
                            tbody.appendChild(row);
                        });

                        // Update pagination info
                        const paginationInfo = document.getElementById('pagination-info');
                        paginationInfo.innerHTML = `
                            <div class="text-sm text-gray-600">
                                Menampilkan ${data.pagination.from} - ${data.pagination.to} dari ${new Intl.NumberFormat('id-ID').format(data.pagination.total)} data
                            </div>
                            <div class="text-sm text-gray-600">
                                Halaman ${data.pagination.current_page} dari ${data.pagination.last_page}
                            </div>
                        `;

                        // Update currentPage dan currentPerPage dari response
                        currentPage = data.pagination.current_page;
                        currentPerPage = data.pagination.per_page;
                        
                        // Update URL dengan state yang benar
                        updateURL();

                        // Update pagination controls
                        updatePaginationControls(data.pagination);

                        // Show content, hide loading
                        document.getElementById('loading-state').classList.add('hidden');
                        document.getElementById('content-state').classList.remove('hidden');
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data ditemukan
                                </td>
                            </tr>
                        `;
                        document.getElementById('loading-state').classList.add('hidden');
                        document.getElementById('content-state').classList.remove('hidden');
                    }
                } else {
                    throw new Error(data.message || 'Gagal memuat data');
                }
            })
            .catch(error => {
                console.error('Error loading search data:', error);
                document.getElementById('error-message').textContent = error.message || 'Terjadi kesalahan saat memuat data.';
                document.getElementById('loading-state').classList.add('hidden');
                document.getElementById('error-state').classList.remove('hidden');
            });
        }

        // Update pagination controls
        function updatePaginationControls(pagination) {
            const controls = document.getElementById('pagination-controls');
            
            if (pagination.last_page <= 1) {
                controls.innerHTML = '';
                return;
            }

            let paginationHtml = '<div class="flex flex-col sm:flex-row items-center justify-between gap-4">';
            paginationHtml += '<div class="flex items-center space-x-2">';
            paginationHtml += '<span class="text-sm text-gray-600">Per halaman:</span>';
            paginationHtml += `<select id="per-page-select" onchange="changePerPage(this.value)" class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">`;
            paginationHtml += `<option value="15" ${currentPerPage == 15 ? 'selected' : ''}>15</option>`;
            paginationHtml += `<option value="25" ${currentPerPage == 25 ? 'selected' : ''}>25</option>`;
            paginationHtml += `<option value="50" ${currentPerPage == 50 ? 'selected' : ''}>50</option>`;
            paginationHtml += `<option value="100" ${currentPerPage == 100 ? 'selected' : ''}>100</option>`;
            paginationHtml += `<option value="200" ${currentPerPage == 200 ? 'selected' : ''}>200</option>`;
            paginationHtml += '</select>';
            paginationHtml += '</div>';
            paginationHtml += '<div class="flex items-center space-x-1">';
            
            // Previous button
            if (pagination.current_page > 1) {
                paginationHtml += `<a href="#" onclick="loadSearchData(${pagination.current_page - 1}); return false;" class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-cyan-600 focus:outline-none focus:ring ring-cyan-300 focus:border-cyan-300 active:bg-cyan-50 active:text-cyan-600 transition ease-in-out duration-150 hover:bg-cyan-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>`;
            } else {
                paginationHtml += `<span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-l-md leading-5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>`;
            }

            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            if (startPage > 1) {
                paginationHtml += `<a href="#" onclick="loadSearchData(1); return false;" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-cyan-700 focus:outline-none focus:ring ring-cyan-300 focus:border-cyan-300 active:bg-cyan-50 active:text-cyan-700 transition ease-in-out duration-150 hover:bg-cyan-50">1</a>`;
                if (startPage > 2) {
                    paginationHtml += `<span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">...</span>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                if (i === pagination.current_page) {
                    paginationHtml += `<span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-cyan-600 border border-cyan-600 cursor-default leading-5">${i}</span>`;
                } else {
                    paginationHtml += `<a href="#" onclick="loadSearchData(${i}); return false;" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-cyan-700 focus:outline-none focus:ring ring-cyan-300 focus:border-cyan-300 active:bg-cyan-50 active:text-cyan-700 transition ease-in-out duration-150 hover:bg-cyan-50">${i}</a>`;
                }
            }

            if (endPage < pagination.last_page) {
                if (endPage < pagination.last_page - 1) {
                    paginationHtml += `<span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">...</span>`;
                }
                paginationHtml += `<a href="#" onclick="loadSearchData(${pagination.last_page}); return false;" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-cyan-700 focus:outline-none focus:ring ring-cyan-300 focus:border-cyan-300 active:bg-cyan-50 active:text-cyan-700 transition ease-in-out duration-150 hover:bg-cyan-50">${pagination.last_page}</a>`;
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                paginationHtml += `<a href="#" onclick="loadSearchData(${pagination.current_page + 1}); return false;" class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-cyan-600 focus:outline-none focus:ring ring-cyan-300 focus:border-cyan-300 active:bg-cyan-50 active:text-cyan-600 transition ease-in-out duration-150 hover:bg-cyan-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>`;
            } else {
                paginationHtml += `<span class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-r-md leading-5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>`;
            }

            paginationHtml += '</div>';
            paginationHtml += '</div>';
            
            controls.innerHTML = paginationHtml;
        }

        // Change per page
        function changePerPage(value) {
            currentPerPage = parseInt(value);
            currentPage = 1;
            updateURL();
            loadSearchData(1);
        }

        // Reset search
        function resetSearch() {
            document.getElementById('tgl_awal').value = '{{ date("Y-m-d") }}';
            document.getElementById('tgl_akhir').value = '{{ date("Y-m-d") }}';
            currentPage = 1;
            currentPerPage = 15;
            
            // Clear URL parameters
            const url = new URL(window.location.href);
            url.search = '';
            window.history.pushState({}, '', url.toString());
            
            document.getElementById('content-state').classList.add('hidden');
            document.getElementById('info-state').classList.add('hidden');
            document.getElementById('error-state').classList.add('hidden');
            document.getElementById('loading-state').classList.add('hidden');
        }

        // Form submit handler
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            currentPage = 1;
            updateURL();
            loadSearchData(1);
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            loadStateFromURL();
            const tglAwal = document.getElementById('tgl_awal').value;
            const tglAkhir = document.getElementById('tgl_akhir').value;
            if (tglAwal && tglAkhir) {
                loadSearchData();
            }
        });

        // Load data on page load if dates are set
        document.addEventListener('DOMContentLoaded', function() {
            // Baca state dari URL terlebih dahulu
            loadStateFromURL();
            
            const tglAwal = document.getElementById('tgl_awal').value;
            const tglAkhir = document.getElementById('tgl_akhir').value;
            
            // Jika ada tanggal di URL atau form, load data
            if (tglAwal && tglAkhir) {
                loadSearchData();
            }
        });
    </script>
</x-layouts.app>

