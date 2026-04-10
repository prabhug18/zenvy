@php
    $general = $data['general'] ?? (get_theme_option(key: 'general') ?? []);

    $activeThemeSlug = key_snake_case(active_theme_slug());
    $logo =
        $data['logo_options'] ??
        (get_theme_option(key: 'theme_logo_' . $activeThemeSlug) ?? (get_theme_option(key: 'theme_logo') ?? []));
    $footerLogo = $logo['footer_logo'] ?? '';
    $defaultLogo =
        $logo && fileExists('lms/theme-options', $footerLogo) == true && $footerLogo != ''
            ? asset('storage/lms/theme-options/' . $footerLogo)
            : asset('lms/frontend/assets/images/logo/default-logo-dark.svg');
@endphp

<div class="grid grid-cols-12 items-center gap-x-4 xl:gap-x-7 gap-y-7">
    <div class="col-span-full md:col-span-6 lg:col-span-3">
        <a href="{{ route('home.index') }}" class="flex-center w-max" aria-label="Footer logo">
            <img data-src="{{ $defaultLogo }}" alt="Footer logo" class="max-w-40">
        </a>
    </div>
    <div class="col-span-full md:col-span-6 lg:col-span-3">
        <div class="flex items-center gap-4">
            <div class="size-12 flex-center rounded-50 border border-white/15 text-white overflow-hidden shrink-0">
                <i class="ri-customer-service-fill"></i>
            </div>
            <div class="grow">
                <h6 class="text-white text-base font-semibold leading-none">{{ translate('Phone') }}</h6>
                <div class="text-white/80 text-sm mt-2">
                    <a href="tel:+{{ $general['phone'] ?? '' }}"
                        aria-label="Company phone" class="text-white/80 hover:text-white transition-colors">{{ $general['phone'] ?? 'no data found' }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-full md:col-span-6 lg:col-span-3">
        <div class="flex items-center gap-4">
            <div class="size-12 flex-center rounded-50 border border-white/15 text-white overflow-hidden shrink-0">
                <i class="ri-mail-send-fill"></i>
            </div>
            <div class="grow">
                <h6 class="text-white text-base font-semibold leading-none">{{ translate('Mail') }}</h6>
                <div class="text-white/80 text-sm mt-2">
                    <a href="mailto:{{ $general['email'] ?? '' }}"
                        aria-label="Company mail" class="text-white/80 hover:text-white transition-colors">{{ $general['email'] ?? 'no data found' }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-full md:col-span-6 lg:col-span-3">
        <div class="flex items-center gap-4">
            <div class="size-12 flex-center rounded-50 border border-white/15 text-white overflow-hidden shrink-0">
                <i class="ri-map-pin-fill"></i>
            </div>
            <div class="grow">
                <h6 class="text-white text-base font-semibold leading-none">{{ translate('Our Address') }}</h6>
                <div class="text-white/80 text-sm leading-none mt-2" aria-label="Company address">
                    {{ $general['address'] ?? 'no data found' }}
                </div>
            </div>
        </div>
    </div>
</div>
