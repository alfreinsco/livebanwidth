<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <a href="{{ route('mikrotik.index') }}" class="text-cyan-600 hover:text-cyan-700">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit MikroTik</h1>
                <p class="text-sm text-gray-600 mt-1">Edit informasi router MikroTik</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fa-solid fa-exclamation-circle text-red-600 mr-3"></i>
                    <div>
                        <p class="text-red-800 font-medium">Terjadi kesalahan:</p>
                        <ul class="text-red-700 text-sm mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
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

            <form action="{{ route('mikrotik.update', $mikrotik->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Router *</label>
                        <input type="text" name="name" value="{{ old('name', $mikrotik->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Contoh: Router Utama">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IP Address *</label>
                        <input type="text" name="ip_address" value="{{ old('ip_address', $mikrotik->ip_address) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 font-mono"
                            placeholder="192.168.88.1">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                            <input type="text" name="username" value="{{ old('username', $mikrotik->username) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                                placeholder="admin">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Port</label>
                            <input type="number" name="port" value="{{ old('port', $mikrotik->port) }}" min="1" max="65535"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                                placeholder="8728">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password. Koneksi akan diuji jika password diubah.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Keterangan tambahan tentang router ini">{{ old('description', $mikrotik->description) }}</textarea>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit"
                            class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors font-medium">
                            <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('mikrotik.index') }}"
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

