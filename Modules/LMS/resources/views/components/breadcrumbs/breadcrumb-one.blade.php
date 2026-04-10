@php
    $activeTheme = function_exists('active_theme_slug') ? active_theme_slug() : 'default';

    $wrapperClass = 'bg-transparent';
    $blobLeft = 'bg-[#1AEBC5]/15';
    $blobCenter = 'bg-[#F98272]/15';
    $blobRight = 'bg-[#5F3EED]/20';

    if ($activeTheme === 'kindergarten') {
        $wrapperClass = 'bg-gradient-to-b from-[#FEFBF0] to-[#E6F3EB] border-b border-[#00A264]/10';
        $blobLeft = 'bg-[#D2EB1A]/20';
        $blobCenter = 'bg-[#B326F4]/15';
        $blobRight = 'bg-[#00A264]/10';
    }
@endphp
<!-- START INNER HERO AREA -->
<div class="{{ $wrapperClass }} relative overflow-hidden mb-16 sm:mb-24 lg:mb-[120px]">
    <div class="container py-[70px] relative z-10">
        <h1 class="area-title xl:text-5xl text-center lg:text-left rtl:lg:text-right">
            {{ isset($pageTitle) && $pageTitle ? translate($pageTitle) : '' }}</h1>
        <!-- BREADCRUMB -->
        <ul
            class="flex items-center bg-white px-4 py-3 lg:px-6 lg:py-3.5 mx-0 lg:mx-3 gap-1.5 *:flex-center *:gap-1.5 leading-none absolute bottom-0 left-1/2 -translate-x-1/2 lg:left-auto rtl:lg:left-0 lg:right-0 rtl:lg:right-auto lg:translate-x-0 rounded-t-2xl w-max shadow-sm border border-b-0 border-heading/5">
            <li
                class="text-heading/70 font-semibold after:font-remix after:flex-center after:font-thin after:text-heading/70 after:size-5 after:content-['\f3c1'] after:text-[6px] after:translate-y-[1.4px] last:after:hidden">
                <a href="{{ route('home.index') }}" aria-label="Go to homepage"
                    class="flex-center shrink-0 gap-2 text-inherit hover:text-primary transition-colors">
                    <i class="ri-home-7-fill"></i>
                    {{ translate('Home') }}
                </a>
            </li>
            <li
                class="text-primary font-bold after:font-remix after:flex-center after:font-thin after:text-heading/70 after:size-5 after:content-['\f3c1'] after:text-[6px] after:translate-y-[1.4px] last:after:hidden">
                {{ translate($pageName) ?? '' }}
            </li>
        </ul>
    </div>
    <!-- POSITIONAL ELEMENTS -->
    <ul class="z-0">
        <!-- LEFT -->
        <li
            class="block size-[550px] w-[33.33vw] rounded-50 {{ $blobLeft }} blur-[180px] absolute top-1/2 -translate-y-1/2 -left-[10%] rtl:left-auto rtl:-right-[10%] -z-10">
        </li>
        <!-- CENTER -->
        <li
            class="block size-[550px] w-[33.33vw] rounded-50 {{ $blobCenter }} blur-[180px] absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 -z-10">
        </li>
        <!-- RIGHT -->
        <li
            class="block size-[550px] w-[33.33vw] rounded-50 {{ $blobRight }} blur-[180px] absolute top-1/2 -translate-y-1/2 -right-[10%] rtl:right-auto rtl:-left-[10%] -z-10">
        </li>
    </ul>
</div>