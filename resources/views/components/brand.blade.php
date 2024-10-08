<div x-data="filamentLogo">
    <img src="{{ asset('assets/png/logo-no-background.png') }}" alt="{{ env('APP_NAME') }} Logo" class="h-4 mt-2"
        x-show="isLightMode">

    <img src="{{ asset('assets/png/logo-no-background-inverted.png') }}" alt="{{ env('APP_NAME') }} Logo"
        class="h-4 mt-2" x-show="isDarkMode">

    <h1 x-show="mode"></h1>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filamentLogo', () => ({
            init() {
                window.addEventListener('theme-changed', (event) => {
                    if (event.detail == 'dark') {
                        this.mode = 'dark'
                    } else {
                        this.mode = 'light'
                    }
                })

                this.mode = document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            },
            mode: 'light',
            isDarkMode() {
                return this.mode === 'dark'
            },
            isLightMode() {
                return this.mode === 'light'
            }
        }))
    })
</script>