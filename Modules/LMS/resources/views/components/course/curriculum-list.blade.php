@php
    $sideBarShow = $sideBarShow ?? null;
    // Fetch current student's enrollment permissions for this course
    $enrollmentPermissions = null;
    $authCheck = authCheck();
    if ($authCheck && isset($purchaseCheck) && $purchaseCheck !== false) {
        $enrollmentRecord = \Modules\LMS\Models\Purchase\PurchaseDetails::where([
            'user_id' => $authCheck->id,
            'course_id' => $course->id,
        ])->whereNotNull('topic_permissions')->latest()->first();
        $enrollmentPermissions = $enrollmentRecord?->topic_permissions;
    }
@endphp

@foreach ($course->chapters as $key => $chapter)
    @php
        $chapterId = $chapter->id;
        $select_chapter_id = $data['chapter_id'] ?? null;
        $start_topic_id = $data['topic_id'] ?? null;
        $showClass = $select_chapter_id == $chapterId ? 'panel-show' : ($loop->first ? 'panel-show' : '');
    @endphp
    <!-- CHAPTER ITEM -->
    <div class="bg-white border-y border-border rounded-sm lms-accordion select-none">
        <div
            class="bg-primary-50 px-3 py-3 cursor-pointer lms-accordion-button  {{ $showClass }} group/accordion peer/accordion">
            <h5
                class="text-sm text-heading dark:text-white font-bold flex items-start justify-between gap-2 grow after:shrink-0 after:right-2.5 after:top-2.5 after:content-['\ea4e'] after:font-remix">
                {{ $chapter->title }}
            </h5>
            <div class="text-xs text-primary font-light mt-1.5 leading-none">
                {{ $chapter?->topics?->count() . ' ' . translate('Lesson') }}
            </div>
        </div>
        <div class="lms-accordion-panel peer-[.panel-show]/accordion:block hidden">
            @foreach ($chapter->topics as $key => $chapterTopic)
                @php
                    $topic = $chapterTopic?->topicable ?? null;
                @endphp
                @if ($topic?->topic_type?->slug == 'video')
                    @php $topicAllowed = $enrollmentPermissions ? ($enrollmentPermissions['video'] ?? true) : true; @endphp
                    <x-theme::course.curriculum-item.item :start_topic_id="$start_topic_id" :chapter_id="$chapterId" :topic="$topic"
                        :course="$course" icon='<i class="ri-file-video-line text-sm"></i>'
                        sideBarShow="{{ $sideBarShow }}" :key="$key" :auth="$auth ?? false"
                        :purchaseCheck="$purchaseCheck" :videoLocked="$purchaseCheck === false || !$topicAllowed" />
                @endif

                @if ($topic?->topic_type?->slug == 'reading')
                    @php $topicAllowed = $enrollmentPermissions ? ($enrollmentPermissions['reading'] ?? true) : true; @endphp
                    <x-theme::course.curriculum-item.item :start_topic_id="$start_topic_id" :chapter_id="$chapterId" :topic="$topic"
                        :course="$course" icon='' sideBarShow="{{ $sideBarShow }}" :key="$key"
                        :auth="$auth ?? false" :purchaseCheck="$topicAllowed ? $purchaseCheck : false" />
                @endif

                @if ($topic?->topic_type?->slug == 'supplement')
                    <x-theme::course.curriculum-item.item :start_topic_id="$start_topic_id" :chapter_id="$chapterId" :topic="$topic"
                        :course="$course" icon='<i class="ri-file-text-line text-sm"></i>'
                        sideBarShow="{{ $sideBarShow }}" :key="$key" :auth="$auth ?? false"
                        :purchaseCheck=$purchaseCheck />
                @endif

                @if ($topic?->topic_type?->slug == 'assignment')
                    @php $topicAllowed = $enrollmentPermissions ? ($enrollmentPermissions['assignment'] ?? true) : true; @endphp
                    <x-theme::course.curriculum-item.item :start_topic_id="$start_topic_id" :chapter_id="$chapterId" :topic="$topic"
                        :course="$course" icon='<i class="ri-a-b text-sm "></i>' sideBarShow="{{ $sideBarShow }}"
                        :key="$key" :auth="$auth ?? false" :purchaseCheck="$topicAllowed ? $purchaseCheck : false" />
                @endif

                @if ($topic?->topic_type?->slug == 'quiz')
                    @php $topicAllowed = $enrollmentPermissions ? ($enrollmentPermissions['quiz'] ?? true) : true; @endphp
                    <x-theme::course.curriculum-item.item :start_topic_id="$start_topic_id" :chapter_id="$chapterId" :topic="$topic"
                        :course="$course" icon='<i class="ri-questionnaire-line text-sm"></i>'
                        sideBarShow="{{ $sideBarShow }}" :key="$key" :auth="$auth ?? false"
                        :purchaseCheck="$topicAllowed ? $purchaseCheck : false" />
                @endif
            @endforeach
        </div>
    </div>
@endforeach
