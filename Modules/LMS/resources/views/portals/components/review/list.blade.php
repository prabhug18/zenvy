@php
    $editRoute = 'course-review.edit';
    $deleteRoute = 'course-review.destroy';

    if (isOrganization()) {
        $editRoute = 'organization.course-review.edit';
        $deleteRoute = 'organization.course-review.destroy';
    }

    if (isInstructor()) {
        $editRoute = 'instructor.course-review.edit';
        $deleteRoute = 'instructor.course-review.destroy';
    }
    $isStudent = isStudent();
    if ($isStudent) {
        $editRoute = 'student.course-review.edit';
        $deleteRoute = 'student.course-review.destroy';
    }
@endphp

<table
    class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium">
    <thead>
        <tr class="text-primary-500">
            <th
                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                {{ translate('User') }}
            </th>
            <th
                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                {{ translate('Course Title') }}
            </th>
            <th
                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                {{ translate('Author') }}
            </th>
            <th
                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                {{ translate('Review') }}
            </th>
            <th
                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                {{ translate('Rating') }}
            </th>
            <th
                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                {{ translate('Status') }}
            </th>
            @if (!$isStudent)
                <th
                    class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right w-28">
                    {{ translate('Action') }}
                </th>
            @endif
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
        @foreach ($reviews as $review)
            @php
                $rating = ($review->support_quality + $review->content_quality + $review->instructor_skills) / 3;
                $average_rating = round($rating);

                $student = $review->user->userable ?? null;
                $translations = parse_translation($student);
                $courseTranslations = parse_translation($review->course);
                $title = '';
                $instructors = $review->course->instructors ?? [];
            @endphp
            <tr>
                <td class="px-4 py-4">
                    {{ $translations['first_name'] ?? ($student->first_name ?? '') }}
                    {{ $translations['last_name'] ?? ($student->last_name ?? '') }}
                </td>
                <td class="px-4 py-4">
                    {{ $courseTranslations['title'] ?? ($review?->course?->title ?? '') }}
                </td>
                <td class="px-4 py-4">
                    @foreach ($instructors as $instructor)
                        @php
                            $userInfo = $instructor->userable ?? null;
                            $courseTranslations = parse_translation($userInfo);
                        @endphp
                    @endforeach
                    {{ $userInfo->first_name ?? '' }}
                    {{ $userInfo->last_name ?? '' }}
                </td>
                <td class="px-4 py-4">
                    {{ $review->content }}
                </td>
                <td class="px-4 py-4 text-warning">
                    {!! show_rating($average_rating) !!}
                </td>
                <td class="px-4 py-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="appearance-none peer review-status-toggle" 
                               data-id="{{ $review->id }}" 
                               data-url="{{ route('instructor.course-review.status', $review->id) }}"
                               {{ $review->status ? 'checked' : '' }}>
                        <span class="switcher switcher-primary-solid"></span>
                    </label>
                </td>

                @if (!$isStudent)
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2">
                            <button data-modal-target="view-review-{{ $review->id }}"
                                data-modal-toggle="view-review-{{ $review->id }}"
                                class="btn-icon btn-info-icon-light size-8">
                                <i class="ri-eye-line text-inherit text-base"></i>
                            </button>
                            <a href="{{ route($editRoute, $review->id) }}"
                                class="btn-icon btn-primary-icon-light size-8">
                                <i class="ri-edit-2-line text-inherit text-base"></i>
                            </a>
                            <button data-action="{{ route($deleteRoute, $review->id) }}"
                                class="btn-icon btn-danger-icon-light size-8 delete-btn-cs">
                                <i class="ri-delete-bin-line text-inherit text-base"></i>
                            </button>
                        </div>
                        <!-- View Modal -->
                        <div id="view-review-{{ $review->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-modal flex-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow-lg dark:bg-dark-card-shade dk-theme-card-square border border-gray-200 dark:border-dark-border-three">
                                    <!-- Modal header -->
                                    <div
                                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-dark-border-three">
                                        <h3 class="text-xl font-semibold text-heading dark:text-white">
                                            {{ translate('Review Details') }}
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-hide="view-review-{{ $review->id }}">
                                            <i class="ri-close-line text-xl"></i>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-5 whitespace-normal">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-dark-card-two p-4 rounded-lg">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 dark:text-dark-text uppercase tracking-wider mb-1">
                                                    {{ translate('User') }}
                                                </p>
                                                <p class="text-sm text-heading dark:text-white font-medium">
                                                    {{ $translations['first_name'] ?? ($student->first_name ?? '') }}
                                                    {{ $translations['last_name'] ?? ($student->last_name ?? '') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 dark:text-dark-text uppercase tracking-wider mb-1">
                                                    {{ translate('Course') }}
                                                </p>
                                                <p class="text-sm text-heading dark:text-white font-medium">
                                                    {{ $courseTranslations['title'] ?? ($review?->course?->title ?? '') }}
                                                </p>
                                            </div>
                                            <div class="md:col-span-2">
                                                <p class="text-xs font-semibold text-gray-500 dark:text-dark-text uppercase tracking-wider mb-1">
                                                    {{ translate('Rating') }}
                                                </p>
                                                <div class="text-warning text-lg">
                                                    {!! show_rating($average_rating) !!}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="pt-2">
                                            <h4 class="text-sm font-semibold text-gray-500 dark:text-dark-text uppercase tracking-wider mb-3">
                                                {{ translate('Review Content') }}
                                            </h4>
                                            <div class="p-4 border-l-4 border-primary-500 bg-primary-50 dark:bg-dark-card-two rounded-r-lg">
                                                <p class="text-base leading-relaxed text-gray-700 dark:text-gray-300 italic">
                                                    "{{ $review->content }}"
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex items-center justify-end p-5 border-t border-gray-200 rounded-b dark:border-dark-border-three">
                                        <!-- <button data-modal-hide="view-review-{{ $review->id }}" type="button"
                                            class="btn b-solid btn-primary-solid dk-theme-card-square">{{ translate('Close') }}</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                @endif

            </tr>
        @endforeach
    </tbody>
</table>

@push('js')
<script>
    $(document).on('change', '.review-status-toggle', function() {
        let url = $(this).data('url');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'success') {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message, 'Success', { positionClass: 'toast-top-right' });
                    }
                }
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Something went wrong.', 'Error', { positionClass: 'toast-top-right' });
                }
            }
        });
    });
</script>
@endpush
