<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Users</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola pengguna aplikasi</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">
                <i class="fa-solid fa-plus mr-2"></i>Tambah User
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fa-solid fa-check-circle text-green-600 mr-3"></i>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fa-solid fa-exclamation-circle text-red-600 mr-3"></i>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($users->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fa-solid fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg font-medium mb-2">Belum ada User</p>
            <p class="text-gray-400 text-sm mb-6">Tambahkan user pertama Anda</p>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>Tambah User
            </a>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                MikroTik Aktif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dibuat
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center text-white font-semibold mr-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->activeMikroTik)
                                <span class="text-sm text-gray-900">{{ $user->activeMikroTik->name }}</span>
                                <span class="text-xs text-gray-500 block">{{ $user->activeMikroTik->ip_address }}</span>
                                @else
                                <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-xs font-medium"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium"
                                        title="Edit">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>

