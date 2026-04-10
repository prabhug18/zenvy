@php
    if (!$course) {
        return;
    }
    $reviews = review($course);
    $imagePath = 'lms/courses/thumbnails';
    $thumbnail =
        !empty($course?->thumbnail) && fileExists($imagePath, $course->thumbnail)
            ? asset("storage/{$imagePath}/{$course->thumbnail}")
            : asset('lms/frontend/assets/images/420x252.svg');

    $translations = $translations ?? parse_translation($course);
@endphp

<div class="flex flex-col bg-white h-full hover:bg-primary px-5 py-6 image-mask mask-kid-course-wrapper custom-transition duration-300 group/kid-course">
    <!-- COURSE THUMBNAIL -->
    <div class="relative aspect-video rounded-2xl overflow-hidden shrink-0">
        <img data-src="{{ $thumbnail }}" alt="Course Thumbnail"
            class="size-full object-cover group-hover/kid-course:scale-110 custom-transition">
    </div>
    <!-- COURSE CONTENT -->
    <div class="px-8 pb-5 mt-6 flex-center flex-col text-center grow">
        <h6
            class="area-title font-bold !text-xl group-hover/kid-course:text-white duration-300 hover:!text-heading custom-transition">
            <a href="{{ route('course.detail', $course->slug) }}" class=""
                aria-label="Course category link">
                {{ $translations['title'] ?? ($course->title ?? '') }}
            </a>
        </h6>
        <div class="area-description group-hover/kid-course:text-white duration-300 mt-4">
            {!! clean($translations['short_description'] ?? ($course->short_description ?? '')) !!}
        </div>
        <div class="flex items-center gap-3 mt-6">
            <a href="{{ route('course.detail', $course->slug) }}" aria-label="Course details link"
                class="btn b-outline btn-primary-outline px-6 group-hover/kid-course:bg-white hover:!text-heading !font-semibold rounded-full">
                {{ translate('See Details') }}
            </a>
            @auth
                @php
                    $class = user_wishlist_check($course->id) ? 'active' : '';
                @endphp
                <label for="course_{{ $course->id }}"
                    class="flex-center p-2 text-primary cursor-pointer select-none z-[1] add-wishlist group/wishlist {{ $class }} group-hover/kid-course:text-white custom-transition hover:scale-110"
                    data-id="{{ $course->id }}">
                    <input type="checkbox" id="course_{{ $course->id }}"
                        class="appearance-none flex-center before:font-remix before:content-['\ee0f'] before:leading-none before:text-current group-[.active]/wishlist:before:text-primary before:text-2xl group-[.active]/wishlist:before:content-['\ee0e'] cursor-pointer">
                </label>
            @else
                <label for="course_{{ $course->id }}"
                    class="flex-center p-2 text-primary cursor-pointer select-none z-[1] group-hover/kid-course:text-white custom-transition hover:scale-110"
                    data-id="{{ $course->id }}">
                    <a href="{{ route('auth.login') }}" id="course_{{ $course->id }}"
                        class="appearance-none flex-center before:font-remix before:content-['\ee0f'] before:leading-none before:text-current before:text-2xl checked:before:content-['\ee0e'] cursor-pointer text-inherit">
                    </a>
                </label>
            @endauth
        </div>
    </div>
</div>
