@props(['videos'])

@if ($videos && count($videos) > 0)
    <div class="relative py-16 sm:py-24 lg:py-[120px] bg-white overflow-hidden">
        <div class="container relative z-[1]">
            <!-- HEADER -->
            <div class="grid grid-cols-12 gap-4 items-center mb-10 lg:mb-[60px]">
                <div class="col-span-full text-center max-w-[670px] mx-auto">
                    <h2 class="area-title">
                        {{ translate('Featured') }}
                        <span class="title-highlight-two">{{ translate('Videos') }}</span>
                    </h2>
                </div>
            </div>

            <!-- BODY (Swiper Slider) -->
            <div class="swiper video-slider mt-10 lg:mt-[60px]">
                <div class="swiper-wrapper">
                    @foreach ($videos as $video)
                        @php
                            $translations = parse_translation($video);
                            $title = $translations['title'] ?? ($video->title ?? '');
                            $thumb =
                                fileExists('lms/videos/thumbnails', $video->thumbnail) && $video->thumbnail != ''
                                    ? asset('storage/lms/videos/thumbnails/' . $video->thumbnail)
                                    : asset('lms/frontend/assets/images/420x252.svg');

                            $videoUrl = '';
                            if ($video->video_type == 'upload') {
                                $videoUrl = asset('storage/lms/videos/' . $video->video_url);
                            } else {
                                $videoUrl = $video->video_url;
                            }
                        @endphp

                        <div class="swiper-slide">
                            <div class="group h-full flex flex-col">
                                <div class="relative overflow-hidden rounded-2xl shadow-lg border-4 border-white bg-[#F8F9FA]" style="aspect-ratio: 16 / 9; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ $thumb }}" alt="{{ $title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        onerror="this.src='{{ asset('lms/frontend/assets/images/420x252.svg') }}'">

                                    <!-- Link to video in new tab as requested -->
                                    <a href="{{ $videoUrl }}" target="_blank"
                                        class="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/30 transition-colors">
                                        <div
                                            class="size-12 md:size-16 bg-white rounded-full flex items-center justify-center text-primary shadow-lg group-hover:scale-110 transition-transform">
                                            <i class="ri-play-fill text-2xl md:text-3xl font-bold"></i>
                                        </div>
                                    </a>
                                </div>
                                <h3 class="text-lg font-bold text-heading text-center line-clamp-2 mt-4 px-2">
                                    {{ $title }}
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- SWIPER PAGINATION -->
                <div class="flex-center mt-10 lg:mt-[60px]">
                    <div class="video-pagination swiper-custom-pagination"></div>
                </div>
            </div>
        </div>

        <!-- POSITIONAL ELEMENTS -->
        <ul>
            <li class="absolute top-10 left-[2%] animate-bounce opacity-20"><img
                    src="{{ asset('lms/frontend/assets/images/icons/triangle.svg') }}" alt="shape"></li>
            <li class="absolute bottom-10 right-[2%] animate-pulse opacity-20"><img
                    src="{{ asset('lms/frontend/assets/images/icons/role-ab.svg') }}" alt="shape"></li>
        </ul>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Swiper for videos
                if (typeof Swiper !== 'undefined') {
                    new Swiper('.video-slider', {
                        slidesPerView: 1,
                        spaceBetween: 16,
                        loop: document.querySelectorAll(".video-slider .swiper-slide").length > 3,
                        autoplay: {
                            delay: 2500,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true
                        },
                        pagination: {
                            el: '.video-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                            },
                            1024: {
                                slidesPerView: 3,
                            },
                            1320: {
                                slidesPerView: 3,
                                spaceBetween: 30
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endif
