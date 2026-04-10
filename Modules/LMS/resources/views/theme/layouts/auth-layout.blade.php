@php
    $settings = [];
    $activeTheme = function_exists('active_theme_slug') ? active_theme_slug() : null;
    $bodyClass = 'home-online-education';
    if ($activeTheme) {
        $themeClass = match ($activeTheme) {
            'elearning-education' => 'home-e-learning',
            'lms-education' => 'home-lms-education',
            'digital-education' => 'home-digital-education',
            'kindergarten' => 'home-kindergarten',
            default => 'home-online-education',
        };
        $bodyClass = $themeClass;
    }
@endphp

@include('theme::layouts.partials.head')

<body class="{{ $bodyClass }}">
    @include('theme::layouts.partials.header', [
        'style' => 'one',
        'class' =>"flex-center bg-white lg:bg-header shadow-md py-4 fixed inset-0 h-[theme('spacing.header')] z-[101]",
        'data' => [
            'header_class' => "flex-center bg-white lg:bg-header shadow-md py-4 fixed inset-0 h-[theme('spacing.header')] z-[101]",
            'search' => [
                'is_show' => false,
            ],
            'components' => [
                'inner-header-top' => '',
            ],
            'cart' => [
                'is_show' => false,
            ],
            'wishlist' => [
                'is_show' => false,
            ]
        ]
    ])
    <main>
        {{ $slot }}
    </main>

    @include('theme::layouts.partials.footer-script', ['data' => []])
</body>

</html>
