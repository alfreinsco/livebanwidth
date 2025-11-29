<x-layouts.app>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Search Traffic UP</h2>
        <p class="text-gray-600">Cari data traffic berdasarkan rentang tanggal</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('search.report') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <b>Mulai Tanggal:</b>
                </label>
                <input type="date" 
                       name="tgl_awal" 
                       id="tgl_awal" 
                       value="{{ date('Y-m-d') }}" 
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
                       value="{{ date('Y-m-d') }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <div class="flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors font-medium">
                    <i class="fa-solid fa-search mr-2"></i>Search
                </button>
                <a href="{{ route('search.report') }}" 
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                    <i class="fa-solid fa-rotate mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    @if(isset($view_tgl))
    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4 mb-6">
        <p class="text-cyan-800 font-medium">{{ $view_tgl }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Ke</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($data ?? [] as $no => $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $no + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{!! $row['text'] !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ date("d F Y, h:i A", strtotime($row['time'])) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['id'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>

