<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <a href="{{ route('users.index') }}" class="text-cyan-600 hover:text-cyan-700">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
                <p class="text-sm text-gray-600 mt-1">Edit informasi user</p>
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

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Nama lengkap">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="nama@email.com">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                                placeholder="Ulangi password">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Kosongkan password jika tidak ingin mengubah</p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">MikroTik Aktif</label>
                        <select name="active_mikrotik_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                            <option value="">Pilih MikroTik (Opsional)</option>
                            @foreach($mikrotiks as $mikrotik)
                            <option value="{{ $mikrotik->id }}" {{ old('active_mikrotik_id', $user->active_mikrotik_id) == $mikrotik->id ? 'selected' : '' }}>
                                {{ $mikrotik->name }} ({{ $mikrotik->ip_address }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit"
                            class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors font-medium">
                            <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('users.index') }}"
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

