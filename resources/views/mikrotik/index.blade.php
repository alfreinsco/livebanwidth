<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen MikroTik</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola router MikroTik untuk monitoring</p>
            </div>
            <a href="{{ route('mikrotik.create') }}"
                class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">
                <i class="fa-solid fa-plus mr-2"></i>Tambah MikroTik
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

        @if($mikrotiks->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fa-solid fa-server text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg font-medium mb-2">Belum ada MikroTik</p>
            <p class="text-gray-400 text-sm mb-6">Tambahkan router MikroTik pertama Anda untuk mulai monitoring</p>
            <a href="{{ route('mikrotik.create') }}"
                class="inline-flex items-center px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>Tambah MikroTik
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($mikrotiks as $mikrotik)
            <div class="bg-white rounded-lg shadow-md overflow-hidden {{ $mikrotik->id == ($activeMikroTik->id ?? null) ? 'ring-2 ring-cyan-500' : '' }}">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $mikrotik->name }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ $mikrotik->ip_address }}</p>
                        </div>
                        @if($mikrotik->id == ($activeMikroTik->id ?? null))
                        <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded-full">
                            Aktif
                        </span>
                        @endif
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fa-solid fa-user w-5"></i>
                            <span>{{ $mikrotik->username }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fa-solid fa-network-wired w-5"></i>
                            <span>Port: {{ $mikrotik->port }}</span>
                        </div>
                        @if($mikrotik->description)
                        <div class="flex items-start text-sm text-gray-600">
                            <i class="fa-solid fa-info-circle w-5 mt-0.5"></i>
                            <span>{{ $mikrotik->description }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2 pt-4 border-t border-gray-200">
                        @if($mikrotik->id != ($activeMikroTik->id ?? null))
                        <form action="{{ route('mikrotik.set-active', $mikrotik->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full px-3 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-sm font-medium">
                                <i class="fa-solid fa-check mr-1"></i>Set Aktif
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('mikrotik.show', $mikrotik->id) }}"
                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('mikrotik.edit', $mikrotik->id) }}"
                            class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                        <form action="{{ route('mikrotik.destroy', $mikrotik->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus MikroTik ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-layouts.app>

