<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MikroTik Router</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($data ?? [] as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @if($item->mikrotik)
                        <div class="flex items-center">
                            <i class="fa-solid fa-router text-cyan-600 mr-2"></i>
                            <div>
                                <div class="font-medium">{{ $item->mikrotik->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->mikrotik->ip_address }}</div>
                            </div>
                        </div>
                    @else
                        <span class="text-gray-400 italic">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{!! $item->text !!}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ date("d F Y, h:i A", strtotime($item->time)) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

