<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

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
    <body class="bg-[#f8fafc] dark:bg-[#0f172a]">
        @if(auth()->check())
            <livewire:navbar />
            <x-sidebar />
        @endif

        {{-- Main Content - com padding para sidebar (desktop) --}}
        <div class="{{ auth()->check() ? 'pt-16 pl-64' : 'min-h-screen' }}">
            {{ $slot }}
        </div>

        @livewireScripts
	
	    <x-toaster-hub />

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
