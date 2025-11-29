<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Active</h1>
            <p class="text-sm text-gray-600">Daftar user yang sedang aktif di router MikroTik.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-100 px-4 py-3 text-sm text-gray-600 flex justify-between">
            <span>Total User Active: <span class="font-semibold">{{ $totaluseractive ?? 0 }}</span></span>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Address</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Uptime</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($useractive as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item['name'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['address'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['uptime'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada user aktif.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>


