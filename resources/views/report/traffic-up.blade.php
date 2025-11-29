<x-layouts.app>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Report Traffic UP</h2>
        <p class="text-gray-600">Report Data Traffic UP - Monitoring Real-time</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="table-responsive">
            <div id="load"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        setInterval('load();', 1000);
        function load() {
            $('#load').load('{{ route('report-up.load') }}')
        }
    </script>
</x-layouts.app>

