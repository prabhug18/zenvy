<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('certificates:fix', function () {
    $this->info('Fixing certificate master template and user certificates...');
    
    $master = \Modules\LMS\Models\Certificate\Certificate::first();
    if ($master) {
        $content = $master->certificate_content;
        $bgUrl = 'http://zenvycoaching.com/storage/lms/certificates/lms-sepsUL0cpl.png';
        if (preg_match('/storage\/lms\/certificates\/[a-zA-Z0-9_-]+\.(png|jpg|jpeg|webp)/i', $content, $m)) {
            $bgUrl = asset($m[0]);
        }
        
        $cleanTemplate = '<div class="certificate-template-container" id="certificateImg" style="background-image: url(\'' . $bgUrl . '\'); background-repeat: no-repeat; background-size: 100% 100%;">' . "\n" .
            '    <div data-name="student" class="dragable-element ui-draggable ui-draggable-handle" style="left: 440px; top: 422px;">{student_name}</div>' . "\n" .
            '    <div data-name="course-title" class="dragable-element ui-draggable ui-draggable-handle" style="left: 440px; top: 472px;">{course_title}</div>' . "\n" .
            '    <div data-name="course-completed-date" class="dragable-element ui-draggable ui-draggable-handle" style="left: 545px; top: 522px;">{course_completed_date}</div>' . "\n" .
            '    <div data-name="instructor-name" class="dragable-element ui-draggable ui-draggable-handle" style="left: 284px; top: 645px;">{instructor_name}</div>' . "\n" .
            '    <div data-name="platform-name" class="dragable-element ui-draggable ui-draggable-handle" style="left: 450px; top: 745px;">{platform_name}</div>' . "\n" .
            '</div>';

        $master->certificate_content = $cleanTemplate;
        $master->save();
        $this->info('Master certificate template updated.');
    }

    $setting = get_theme_option('backend_setting') ?? [];
    $platformName = $setting['app_name'] ?? config('app.name');

    foreach (\Modules\LMS\Models\Certificate\UserCertificate::all() as $userCert) {
        $exam = \Modules\LMS\Models\Auth\UserCourseExam::with('user.userable', 'course.instructors.userable')->where('id', $userCert->quiz_id)->first();
        
        $studentName = '';
        $courseTitle = $userCert->subject ?? '';
        $instructorName = '';
        $date = customDateFormate($userCert->certificated_date, format: 'd-m-Y');

        if ($exam) {
            $user = $exam->user?->userable;
            if ($user) {
                $studentName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            }
            if ($exam->course?->title) {
                $courseTitle = $exam->course->title;
            }
            $instructor = $exam->course?->instructors[0] ?? null;
            if ($instructor && $instructor->userable) {
                $instructorName = trim(($instructor->userable->first_name ?? '') . ' ' . ($instructor->userable->last_name ?? ''));
            }
        }
        
        if (empty($studentName)) {
            $u = \Modules\LMS\Models\User::with('userable')->where('id', $userCert->user_id)->first();
            if ($u && $u->userable) {
                $studentName = trim(($u->userable->first_name ?? '') . ' ' . ($u->userable->last_name ?? ''));
            }
        }

        $dataPayload = [
            'student_name' => $studentName,
            'course_title' => $courseTitle,
            'instructor_name' => $instructorName,
            'course_completed_date' => $date,
            'platform_name' => $platformName,
        ];

        $mergedContent = str_replace(
            ['{student_name}', '{platform_name}', '{course_title}', '{instructor_name}', '{course_completed_date}'],
            [$studentName, $platformName, $courseTitle, $instructorName, $date],
            $master ? $master->certificate_content : $userCert->certificate_content
        );

        $userCert->certificate_data = $dataPayload;
        $userCert->certificate_content = $mergedContent;
        $userCert->save();

        $this->line("Updated user certificate #{$userCert->id} ({$studentName} - {$courseTitle})");
    }

    $this->info('All certificates repaired successfully.');
})->purpose('Fix and clean certificate templates and backfill user certificates');
