<x-layouts.guest>
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md mx-4">
            <div class="mb-6 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-b from-cyan-500 to-cyan-600 text-white">
                    <i class="fa-solid fa-network-wired text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">LiveBandwidth</h1>
                <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500"
                        placeholder="nama@email.com" required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500"
                        placeholder="••••••••" required>
                </div>

                <button type="submit"
                    class="mt-2 flex w-full items-center justify-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>
                    Masuk
                </button>
            </form>
    </div>
</x-layouts.guest>


