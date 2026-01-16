<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Controle de Séries </title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script>
        (function(){
            // Apply saved preferences ASAP to avoid FOUC and contrast issues
            const t = localStorage.getItem('app_theme') || 'light';
            const f = localStorage.getItem('app_font_size') || 'md';
            document.documentElement.setAttribute('data-bs-theme', t === 'dark' ? 'dark' : 'light');
            const map = { sm: '0.95rem', md: '1rem', lg: '1.125rem' };
            document.documentElement.style.setProperty('--bs-body-font-size', map[f] || map['md']);
        })();
    </script>
</head>
<body class="min-vh-100">
    <x-navbar />
    <main class="container py-4">
        {{ $slot }}
    </main>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            const THEME_KEY = 'app_theme';
            const FONT_KEY = 'app_font_size';
            function applyTheme(theme){
                const t = theme === 'dark' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-bs-theme', t);
            }
            function applyFont(size){
                const map = { sm: '0.95rem', md: '1rem', lg: '1.125rem' };
                const val = map[size] || map['md'];
                document.documentElement.style.setProperty('--bs-body-font-size', val);
            }
            document.addEventListener('DOMContentLoaded', function(){
                const theme = localStorage.getItem(THEME_KEY) || 'light';
                const font = localStorage.getItem(FONT_KEY) || 'md';
                applyTheme(theme);
                applyFont(font);
            });
        })();
    </script>
</body>
</html>
