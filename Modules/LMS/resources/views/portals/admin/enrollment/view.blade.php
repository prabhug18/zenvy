@php

    $userInfo = $enrollment?->user->userable ?? null;
    $course = $enrollment?->course ?? null;

    $userTranslations = parse_translation($userInfo);
    $courseTranslations = parse_translation($course);

@endphp
<x-dashboard-layout>
    <x-slot:title>{{ translate('New Enrolled') }}</x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb back-url="{{ route('enrollment.index') }}" title="View Enroll" page-to="New Enroll" />

    <div class="grid grid-cols-12 card">
        <div class="col-span-full md:col-span-6">
            <div class="leading-none">
                <label for="courseTitle" class="form-label"> {{ translate('Student Name') }} : </label>
                {{ $userTranslations['first_name'] ?? $userInfo?->first_name }}
                {{ $userTranslations['last_name'] ?? $userInfo?->last_name }}
            </div>
            <div class="mt-6 leading-none">
                <label for="code" class="form-label">
                    {{ translate('Enrolled Course') }} :
                </label>
                {{ $courseTranslations['title'] ?? ($course->title ?? '')   }}
            </div>
            <div class="mt-8 border-t pt-6">
                <label class="form-label font-bold text-heading mb-4 block">{{ translate('Topic Permissions') }}</label>
                <div class="flex flex-wrap gap-3">
                    @foreach(['video' => 'Video', 'assignment' => 'Assignment', 'quiz' => 'Quiz', 'reading' => 'Reading'] as $key => $label)
                        @php $allowed = $enrollment->topic_permissions[$key] ?? false; @endphp
                        @if($allowed)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                {{ translate($label) }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
