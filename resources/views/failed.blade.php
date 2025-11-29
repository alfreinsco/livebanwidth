<x-layouts.guest>
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md text-center mx-4">
            <div
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-800 mb-2">Gagal Terhubung ke Router</h1>
            <p class="text-sm text-gray-600 mb-6">
                Pastikan IP Address, username, dan password router MikroTik sudah benar dan API diaktifkan.
            </p>
            <a href="{{ route('auth.index') }}"
                class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Kembali ke Halaman Login
            </a>
    </div>
</x-layouts.guest>


