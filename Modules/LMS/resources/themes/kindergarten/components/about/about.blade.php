@php

    $aboutUs =
        get_theme_option('about_us' . active_language()) ?:
        get_theme_option('about_usen') ?? get_theme_option('about_us' . app('default_language'));
    $bannerImageOne = $aboutUs['banner_img_kindergarten'] ?? '';
    $bannerPath = "storage/lms/theme-options/{$bannerImageOne}";
    $defaultBanner = 'lms/frontend/assets/images/banner/banner_image_3.svg';

    // Check if the banner image exists and is not empty
    $bannerImage =
        !empty($bannerImageOne) && file_exists(public_path($bannerPath)) ? asset($bannerPath) : asset($defaultBanner);
@endphp

<div class="pt-16 sm:pt-24 lg:pt-[120px]">
    <div class="container relative">
        <div class="grid grid-cols-12 gap-x-4 xl:gap-x-7 gap-y-7 items-center">
            <div class="col-span-full lg:col-span-6">
                <img data-src="{{ $bannerImage }}" alt="about">
            </div>
            <div class="col-span-full lg:col-span-6">
                <div class="lg:pl-[10%] rtl:lg:pl-0 rtl:lg:pr-[10%]">
                    <h2 class="area-title">
                        {{ $aboutUs['title'] ?? '' }}
                        <span class="title-highlight-two">
                            {{ $aboutUs['highlight_title'] ?? '' }}
                        </span>
                    </h2>
                    <div class="area-description mt-2">
                        {{ $aboutUs['short_description'] ?? '' }}
                    </div>
                    <!-- <div
                        class="area-description mt-5 font-bold text-lg">
                        {!! clean($aboutUs['add_description'] ?? '') !!}
                    </div> -->
                    <a href="{{ route('about.us') }}" aria-label="Read More About"
                        title="{{ translate('Read More About us') }}"
                        class="btn b-solid btn-primary-solid btn-lg !px-7 !rounded-full !text-base font-bold shrink-0 mt-11">
                        {{ translate('Read More About') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-x-4 xl:gap-x-7 gap-y-7 pt-10 sm:pt-16 lg:pt-[90px]">
            <div class="col-span-full lg:col-span-4">
                <div class="bg-[#F0F6E8] px-9 py-11 rounded-xl h-full">
                    <div class="flex items-start gap-3">
                        <div class="size-14 rounded-50 flex-center bg-primary text-white p-2 shrink-0">
                            <i class="ri-presentation-fill text-2xl"></i>
                        </div>
                        <div class="grow">
                            <h6 class="area-title text-xl !leading-none">{{ translate('Expert-Led Training') }}</h6>
                            <div class="area-description text-base mt-3">
                                {{ translate('Learn from experienced trainers who specialize in Abacus and Vedic Maths. Our teaching methods are designed to make concepts simple, engaging, and easy to understand for every child.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-full lg:col-span-4">
                <div class="bg-[#F4F4FF] px-9 py-11 rounded-xl h-full">
                    <div class="flex items-start gap-3">
                        <div class="size-14 rounded-50 flex-center bg-blue-700 text-white p-2 shrink-0">
                            <i class="ri-bar-chart-fill text-2xl"></i>
                        </div>
                        <div class="grow">
                            <h6 class="area-title text-xl !leading-none">{{ translate('Structured Learning Levels') }}</h6>
                            <div class="area-description text-base mt-3">
                                {{ translate('Our courses are divided into levels from beginner to advanced, ensuring step-by-step learning and continuous improvement in speed, accuracy, and confidence.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-full lg:col-span-4">
                <div class="bg-[#F0F6E8] px-9 py-11 rounded-xl h-full">
                    <div class="flex items-start gap-3">
                        <div class="size-14 rounded-50 flex-center bg-secondary text-white p-2 shrink-0">
                            <i class="ri-brain-line text-2xl"></i>
                        </div>
                        <div class="grow">
                            <h6 class="area-title text-xl !leading-none">{{ translate('Proven Skill Development') }}</h6>
                            <div class="area-description text-base mt-3">
                                {{ translate('Improve concentration, memory, and calculation speed through scientifically designed programs that enhance both academic performance and brain development.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
