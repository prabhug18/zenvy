@php
    $testimonials = $testimonials ?? [];
    $backgrounds = ['bg-[#DEC8FE]', 'bg-[#FBD983]', 'bg-[#D5EEB4]', 'bg-[#E6F3EB]'];
@endphp

@if ($testimonials->count() > 0)
    <div class="pt-16 sm:pt-24 lg:pt-[120px] pb-16 sm:pb-24 lg:pb-[120px] bg-white relative overflow-hidden">
        <div class="container">
            <!-- HEADER -->
            <div class="grid grid-cols-12 gap-4 items-center mb-10 lg:mb-[60px]">
                <div class="col-span-full text-center max-w-[640px] mx-auto">
                    <h2 class="area-title">
                        {{ translate('What Our Parents') }}
                        <span class="title-highlight-two">{{ translate('Are Saying') }}</span>
                    </h2>
                    <p class="area-description mt-4">
                        {{ translate('Real experiences from families who have joined our Zenvy learning community.') }}
                    </p>
                </div>
            </div>

            <!-- BODY -->
            <div class="swiper testimonial-slider">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $index => $testimonial)
                        @php
                            $translations = parse_translation($testimonial);
                            $profileImagePath = 'storage/lms/testimonials/' . $testimonial->profile_image;
                            $defaultProfileImage = 'lms/frontend/assets/images/370x396.svg';
                            $profileImageSrc =
                                fileExists('lms/testimonials', $testimonial->profile_image) && $testimonial->profile_image != ''
                                ? asset($profileImagePath)
                                : asset($defaultProfileImage);
                            $bgColor = $backgrounds[$index % count($backgrounds)];
                        @endphp
                        <div class="swiper-slide h-auto pb-10">
                            <!-- Card Background -->
                            <div
                                class="{{ $bgColor }} px-8 pt-16 pb-12 rounded-2xl h-full flex flex-col relative mt-12 shadow-sm group hover:shadow-md transition-all duration-300">

                                <!-- Profile Image (Centered Top) -->
                                <div
                                    class="size-20 rounded-full border-4 border-white overflow-hidden absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2 shadow-sm">
                                    <img data-src="{{ $profileImageSrc }}"
                                        alt="{{ $translations['name'] ?? ($testimonial->name ?? 'User') }}"
                                        class="size-full object-cover">
                                </div>

                                <!-- Stars Rating (Left Aligned & Forced Yellow) -->
                                <div
                                    class="flex items-center gap-1 mt-2 mb-5 [&_i]:!text-[#FDB022] [&_.ri-star-fill]:!text-[#FDB022]">
                                    {!! show_rating($translations['rating'] ?? ($testimonial->rating ?? 5)) !!}
                                </div>

                                <!-- Comments (Left Aligned) -->
                                <div class="area-description italic text-heading leading-[1.8] text-base mb-8 flex-grow">
                                    {!! clean($translations['comments'] ?? ($testimonial->comments ?? '')) !!}
                                </div>

                                <!-- Footer Area -->
                                <div class="mt-auto flex justify-between items-end">
                                    <!-- Author Identity -->
                                    <div>
                                        <h6 class="area-title text-xl font-bold !leading-none text-heading">
                                            {{ $translations['name'] ?? ($testimonial->name ?? '') }}
                                        </h6>
                                        @if(!empty($testimonial->designation))
                                            <p class="area-description !leading-none mt-2 text-sm text-heading/70">
                                                {{ $translations['designation'] ?? ($testimonial->designation ?? '') }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Quote Icon (Bottom Right) -->
                                    <div class="opacity-10 shrink-0">
                                        <i class="ri-double-quotes-r text-6xl text-heading"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- PAGINATION -->
            <div class="flex-center mt-10 lg:mt-[60px]">
                <div class="testimonial-pagination swiper-custom-pagination"></div>
            </div>
        </div>
    </div>
@endif