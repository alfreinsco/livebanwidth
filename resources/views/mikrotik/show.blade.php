<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <a href="{{ route('mikrotik.index') }}" class="text-cyan-600 hover:text-cyan-700">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $mikrotik->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">Detail informasi router MikroTik</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Router</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Nama</span>
                            <span class="font-medium text-gray-800">{{ $mikrotik->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">IP Address</span>
                            <span class="font-mono font-medium text-gray-800">{{ $mikrotik->ip_address }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Username</span>
                            <span class="font-medium text-gray-800">{{ $mikrotik->username }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Port</span>
                            <span class="font-medium text-gray-800">{{ $mikrotik->port }}</span>
                        </div>
                        @if($mikrotik->description)
                        <div class="py-2">
                            <span class="text-gray-600 block mb-2">Deskripsi</span>
                            <p class="text-gray-800">{{ $mikrotik->description }}</p>
                        </div>
                        @endif
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Status</span>
                            @if($mikrotik->id == (Auth::user()->active_mikrotik_id ?? null))
                            <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-sm font-semibold rounded-full">
                                Aktif
                            </span>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                                Tidak Aktif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($isConnected && $routerInfo)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Router MikroTik</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Identity</span>
                            <span class="font-medium text-gray-800">{{ $routerInfo['identity'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Version</span>
                            <span class="font-medium text-gray-800">{{ $routerInfo['version'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Model</span>
                            <span class="font-medium text-gray-800">{{ $routerInfo['model'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Board Name</span>
                            <span class="font-medium text-gray-800">{{ $routerInfo['board_name'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Uptime</span>
                            <span class="font-medium text-gray-800">{{ $routerInfo['uptime'] }}</span>
                        </div>
                    </div>
                </div>
                @elseif(!$isConnected)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 mr-3 text-xl"></i>
                        <div>
                            <p class="text-red-800 font-medium">Tidak dapat terhubung ke router</p>
                            <p class="text-red-700 text-sm mt-1">Pastikan router dapat diakses dan kredensial benar</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions Card -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi</h2>
                    <div class="space-y-3">
                        @if($mikrotik->id != (Auth::user()->active_mikrotik_id ?? null))
                        <form action="{{ route('mikrotik.set-active', $mikrotik->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors font-medium">
                                <i class="fa-solid fa-check mr-2"></i>Set Sebagai Aktif
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('mikrotik.edit', $mikrotik->id) }}"
                            class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-center">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('mikrotik.destroy', $mikrotik->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus MikroTik ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                <i class="fa-solid fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi</h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center justify-between">
                            <span>Dibuat</span>
                            <span class="font-medium">{{ $mikrotik->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Diperbarui</span>
                            <span class="font-medium">{{ $mikrotik->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

