<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hotspot Users</h1>
            <p class="text-sm text-gray-600">Manajemen user hotspot pada router MikroTik.</p>
        </div>
        <button
            class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">
            <i class="fa-solid fa-plus mr-2"></i>
            Tambah User
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-100 px-4 py-3 text-sm text-gray-600 flex justify-between">
            <span>Total User: <span class="font-semibold">{{ $totalhotspotuser ?? 0 }}</span></span>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Username</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Server</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Profile</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Limit Uptime</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Comment</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($hotspotuser as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item['name'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['server'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['profile'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['limit-uptime'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['comment'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada data user hotspot.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>


