<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <a href="{{ route('users.index') }}" class="text-cyan-600 hover:text-cyan-700">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">Detail informasi user</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi User</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Nama</span>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Email</span>
                            <span class="font-medium text-gray-800">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">MikroTik Aktif</span>
                            @if($user->activeMikroTik)
                            <div class="text-right">
                                <span class="font-medium text-gray-800">{{ $user->activeMikroTik->name }}</span>
                                <span class="text-sm text-gray-500 block font-mono">{{ $user->activeMikroTik->ip_address }}</span>
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Total MikroTik</span>
                            <span class="font-medium text-gray-800">{{ $user->mikrotiks->count() }}</span>
                        </div>
                    </div>
                </div>

                @if($user->mikrotiks->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar MikroTik</h2>
                    <div class="space-y-2">
                        @foreach($user->mikrotiks as $mikrotik)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                            <div>
                                <span class="font-medium text-gray-800">{{ $mikrotik->name }}</span>
                                <span class="text-sm text-gray-500 block font-mono">{{ $mikrotik->ip_address }}</span>
                            </div>
                            @if($mikrotik->id == $user->active_mikrotik_id)
                            <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded-full">
                                Aktif
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions Card -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi</h2>
                    <div class="space-y-3">
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-center">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                <i class="fa-solid fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi</h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center justify-between">
                            <span>Dibuat</span>
                            <span class="font-medium">{{ $user->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Diperbarui</span>
                            <span class="font-medium">{{ $user->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

