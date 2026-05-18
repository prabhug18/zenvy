<x-dashboard-layout>
    <x-slot:title>{{ translate('New Enrolled') }}</x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb back-url="{{ route('enrollment.index') }}"
        title=" {{ isset($enrollment) ? 'Edit Enroll' : 'New Enroll' }}" page-to="New Enroll" />
  
    <form action="{{ route('enrollment.store') }}" method="post" class="form">
        @csrf
        @if (isset($enrollment))
            <input type="hidden" value="{{ $enrollment->id }}" name="id">
        @endif
        <div class="grid grid-cols-12 card">
            <div class="col-span-full md:col-span-6">
                <div class="leading-none">
                    <label for="courseTitle" class="form-label"> {{ translate('Student') }}
                        <span class="text-danger" title="{{ translate('This field is required') }}"><b>*</b></span>
                    </label>
                    <select class="form-input singleSelect" name="student_id">
                        <option selected disabled>{{ translate('Select Student') }}</option>
                        @foreach (get_all_student() as $student)
                            @php
                                $studentTranslations = parse_translation($student?->userable);
                            @endphp
                            <option value="{{ $student->id }}"
                                {{ isset($enrollment) && $enrollment->user_id == $student->id ? 'selected' : '' }}>
                                {{ $studentTranslations['first_name'] ?? $student?->userable?->first_name }}
                                {{ $studentTranslations['last_name'] ?? $student?->userable?->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text student_id_err"></span>
                </div>
                <div class="mt-6 leading-none">
                    <label for="code" class="form-label">
                        {{ translate('Select Course') }}
                        <span class="text-danger" title="{{ translate('This field is required') }}"><b>*</b></span>
                    </label>
                    <select class="form-input singleSelect" multiple name="courses[]">
                        @foreach (getCourseByStatus() as $course)
                            @php
                                $courseTranslations = parse_translation($course);
                                $courseType =$course?->courseSetting?->is_free == 0 ? 'Paid' :" Free";
                            @endphp
                            <option value="{{ $course->id }}"
                                {{ isset($enrollment) && $enrollment?->course?->id == $course->id ? 'selected' : '' }}>
                                {{ $courseTranslations['title'] ?? $course?->title  }} {{ '-' .''. $courseType }} </option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text courses_err"></span>
                </div>

                {{-- Topic Permissions Section --}}
                <div class="mt-6 leading-none">
                    <label class="form-label font-bold text-heading mb-2 block">{{ translate('Topic Permissions') }}</label>
                    <p class="text-sm text-gray-500 mb-4 italic">
                        {{ translate('Toggle off to restrict student access to specific content types.') }}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 bg-slate-50 p-4 rounded-md border border-slate-100">
                        @foreach(['video' => 'Video', 'assignment' => 'Assignment', 'quiz' => 'Quiz', 'reading' => 'Reading'] as $key => $label)
                            <div class="flex items-center justify-between gap-3 p-2 bg-white rounded border border-transparent hover:border-primary-100 transition-colors">
                                <span class="text-sm font-medium text-heading dark:text-white">{{ translate($label) }}</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="topic_permissions[{{ $key }}]"
                                        value="1"
                                        class="sr-only peer topic-permission-checkbox"
                                        data-label="{{ translate($label) }}"
                                        {{ isset($enrollment) && ($enrollment->topic_permissions[$key] ?? false) ? 'checked' : '' }}
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn b-solid btn-primary-solid w-max mt-8 dk-theme-card-square shadow-sm">
                    {{ translate('Enrolled') }}
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.form');
            let isConfirmed = false;

            if (form) {
                $(form).on('submit', function(e) {
                    if (isConfirmed) {
                        return true;
                    }

                    e.preventDefault();
                    e.stopImmediatePropagation();

                    // Get selected permissions
                    const selected = [];
                    document.querySelectorAll('.topic-permission-checkbox:checked').forEach(cb => {
                        selected.push(cb.getAttribute('data-label'));
                    });

                    let message = "{{ translate('No permissions selected.') }}";
                    if (selected.length > 0) {
                        message = "{{ translate('You have selected following permissions') }}: <br><strong class='text-primary-500'>" + selected.join(', ') + "</strong>";
                    }

                    Swal.fire({
                        title: "{{ translate('Confirm Enrollment') }}",
                        html: message,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5F71FA',
                        cancelButtonColor: '#FF4626',
                        confirmButtonText: "{{ translate('Confirm') }}",
                        cancelButtonText: "{{ translate('Cancel') }}",
                        customClass: {
                            title: 'text-heading',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isConfirmed = true;
                            $(form).submit();
                        }
                    });
                });
            }
        });
    </script>
</x-dashboard-layout>
