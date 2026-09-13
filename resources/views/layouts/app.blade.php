<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Capacity Connect - A Digital Capacity Building and Learning Management Portal">
    <title>@yield('title', 'Capacity Connect')</title>
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    
    <!-- PWA Connection Status Bar -->
    <div id="pwa-status-bar" class="bg-dark text-white py-1 px-3 small text-center" style="display: none;">
        <span id="pwa-status-text"><i class="bi bi-wifi me-1"></i> ONLINE</span>
    </div>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.ai-assistant')
    @include('partials.guided-tour')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Service Worker Registration & Connectivity Monitor -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(reg => {
                    console.log('CapacityConnect SW registered: ', reg);
                }).catch(err => {
                    console.log('CapacityConnect SW registration failed: ', err);
                });
            });
        }

        function updateOnlineStatus() {
            const statusBar = document.getElementById('pwa-status-bar');
            const statusText = document.getElementById('pwa-status-text');
            if (!statusBar || !statusText) return;

            if (navigator.onLine) {
                statusBar.className = 'bg-success text-white py-1 px-3 small text-center';
                statusText.innerHTML = '<i class="bi bi-wifi me-1"></i> ONLINE &bull; Portal Connected';
                setTimeout(() => { statusBar.style.display = 'none'; }, 3000);
            } else {
                statusBar.style.display = 'block';
                statusBar.className = 'bg-danger text-white py-1 px-3 small text-center fw-bold';
                statusText.innerHTML = '<i class="bi bi-wifi-off me-1"></i> OFFLINE &bull; Operating on Field Offline Mode';
            }
        }

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        document.addEventListener('DOMContentLoaded', updateOnlineStatus);
    </script>
</body>
</html>
