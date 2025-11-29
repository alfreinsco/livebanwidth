<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">PPPoE Secret</h1>
            <p class="text-sm text-gray-600">Manajemen akun PPPoE pada router MikroTik.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-100 px-4 py-3 text-sm text-gray-600 flex justify-between">
            <span>Total Secret: <span class="font-semibold">{{ $totalsecret ?? 0 }}</span></span>
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
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($secret as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item['name'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['service'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['profile'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['local-address'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['remote-address'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada data PPPoE secret.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>


