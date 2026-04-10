<!-- START ABOUT US AREA -->
@php
    $aboutUs = get_theme_option('about_us' . active_language()) ?: get_theme_option('about_usen');
    $activeTheme = function_exists('active_theme_slug') ? active_theme_slug() : 'default';
    
    // Map theme slugs to their respective image keys in settings
    $themeImageMap = [
        'elearning-education' => 'banner_img_elearning',
        'digital-education' => 'banner_img_digital',
        'lms-education' => 'banner_img_lms',
        'kindergarten' => 'banner_img_kindergarten',
    ];

    $imageKey = $themeImageMap[$activeTheme] ?? 'banner_img_elearning';
    $imageName = $aboutUs[$imageKey] ?? '';

    // Fallback logic: if theme-specific image is missing, try others
    if (!$imageName) {
        $imageName = $aboutUs['banner_img_elearning'] 
            ?? $aboutUs['banner_img_kindergarten'] 
            ?? $aboutUs['banner_img_digital'] 
            ?? $aboutUs['banner_img_lms'] 
            ?? '';
    }

    $aboutImg =
        isset($imageName) && fileExists('lms/theme-options', $imageName) == true
            ? asset('storage/lms/theme-options/' . $imageName)
            : asset('lms/frontend/assets/images/banner/banner_placeholder_2.svg');
@endphp
<x-frontend-layout>
    <x-theme::breadcrumbs.breadcrumb-one pageTitle="About Us" pageRoute="About Us" pageName="About Us" />
    <!-- Blog -->

    <div class="container">
        <div class="grid grid-cols-12 gap-x-4 xl:gap-x-7 gap-y-7 items-center">
            <div class="col-span-full lg:col-span-6">
                <img data-src="{{ $aboutImg }}" alt="About Us">
            </div>
            <div class="col-span-full lg:col-span-6">
                <div class="lg:pl-[10%] rtl:lg:pl-0 rtl:lg:pr-[10%]">
                    <div class="outline-text-one text-4xl lg:text-7xl">{{ translate('About Us') }}</div>
                    <h2 class="area-title mt-1">{{ $aboutUs['title'] ?? '' }}</h2>
                    <div class="area-description mt-2.5 line-clamp-6">
                        {!! clean($aboutUs['short_description'] ?? '') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MISSION & VISION SECTION -->
    <div class="pt-16 sm:pt-24 lg:pt-[100px] pb-16 sm:pb-24 lg:pb-[120px] bg-white">
        <div class="container relative">
            <!-- SECTION HEADER -->
            <div class="grid grid-cols-12 gap-4 items-center mb-10 lg:mb-[60px]">
                <div class="col-span-full text-center max-w-[640px] mx-auto">
                    <h2 class="area-title">
                        {{ translate('Our Foundational') }}
                        <span class="title-highlight-two">{{ translate('Mission & Vision') }}</span>
                    </h2>
                </div>
            </div>

            <!-- CARDS GRID -->
            <div class="grid grid-cols-12 gap-x-4 xl:gap-x-7 gap-y-7">
                <!-- MISSION CARD -->
                <div class="col-span-full lg:col-span-6">
                    <div class="bg-[#DEC8FE] px-9 pt-11 pb-16 sm:pb-24 h-full image-mask mask-work-process group hover:shadow-2xl transition-all duration-500">
                        <div class="flex-center flex-col text-center">
                            <div class="size-20 rounded-50 flex-center bg-white p-2 shrink-0 image-mask mask-star shadow-md group-hover:scale-110 transition-transform duration-300">
                                <span class="font-secondary text-primary text-3xl italic font-bold -mb-1">01</span>
                            </div>
                            
                            <h3 class="area-title text-2xl sm:text-3xl mt-8 text-purple-900">{{ translate('Our Mission') }}</h3>
                            <div class="area-description text-lg sm:text-xl mt-4 leading-relaxed text-purple-900/80 px-[5%] mx-auto max-w-[450px]">
                                {{ translate('At Zenvy Coaching, our mission is to empower development techniques, creative learning strategies, and real-world communication skills, building confidence, creativity, individuals for lifelong success.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VISION CARD -->
                <div class="col-span-full lg:col-span-6">
                    <div class="bg-[#FBD983] px-9 pt-11 pb-16 sm:pb-24 h-full image-mask mask-work-process group hover:shadow-2xl transition-all duration-500">
                        <div class="flex-center flex-col text-center">
                            <!-- STAR BADGE -->
                            <div class="size-20 rounded-50 flex-center bg-white p-2 shrink-0 image-mask mask-star shadow-md group-hover:scale-110 transition-transform duration-300">
                                <span class="font-secondary text-primary text-3xl italic font-bold -mb-1">02</span>
                            </div>
                            
                            <h3 class="area-title text-2xl sm:text-3xl mt-8 text-orange-900">{{ translate('Our Vision') }}</h3>
                            <div class="area-description text-lg sm:text-xl mt-4 leading-relaxed text-orange-950/80 px-[5%] mx-auto max-w-[450px]">
                                {{ translate('At Zenvy Coaching, our vision is to become a leading and transformative learning ecosystem that empowers individuals to grow with confidence, think independently, and communicate effectively in an ever-evolving world.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-frontend-layout>
