<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Auth' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <script>
            (function() {
                const savedTheme = localStorage.getItem('isDarkMode');

                let isDark;

                if (savedTheme !== null) {
                    isDark = savedTheme === 'true';
                } else {
                    isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    localStorage.setItem('isDarkMode', isDark ? 'true' : 'false');
                }

                if (isDark) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
    </head>
    <body class="bg-[#f8fafc] dark:bg-[#0f172a] min-h-screen">
        <div class="min-h-screen flex flex-col">
            {{-- Theme Toggle --}}
            <div class="absolute top-4 right-4 z-50">
                <livewire:theme-toggle />
            </div>

            {{-- Main Content --}}
            <div class="flex-1 flex items-center justify-center px-4 py-12">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer --}}
            <footer class="py-6 text-center text-[#64748b] dark:text-[#94a3b8] text-sm">
                <p>&copy; {{ date('Y') }} DevContent. Todos os direitos reservados.</p>
            </footer>
        </div>

        @livewireScripts

        <script>
            document.addEventListener('livewire:initialized', () => {
                const savedTheme = localStorage.getItem('isDarkMode');

                let isDark;

                if (savedTheme !== null) {
                    isDark = savedTheme === 'true';
                } else {
                    isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    localStorage.setItem('isDarkMode', isDark ? 'true' : 'false');
                }

                Livewire.dispatch('theme-initialized', { isDarkMode: isDark });

                Livewire.on('theme-changed', (event) => {
                    const newIsDark = event.isDarkMode;

                    if (newIsDark) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('isDarkMode', 'true');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('isDarkMode', 'false');
                    }
                });

                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    const hasManualPreference = localStorage.getItem('userSetTheme') === 'true';

                    if (!hasManualPreference) {
                        const isDark = e.matches;

                        if (isDark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }

                        localStorage.setItem('isDarkMode', isDark ? 'true' : 'false');
                        Livewire.dispatch('theme-initialized', { isDarkMode: isDark });
                    }
                });
            });
        </script>
    </body>
</html>
