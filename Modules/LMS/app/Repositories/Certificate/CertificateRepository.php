<?php

namespace Modules\LMS\Repositories\Certificate;

use Modules\LMS\Models\Auth\UserCourseExam;
use Modules\LMS\Repositories\BaseRepository;
use Modules\LMS\Models\Certificate\Certificate;
use Modules\LMS\Models\Certificate\UserCertificate;

class CertificateRepository extends BaseRepository
{
    protected static $model = Certificate::class;

    protected static $exactSearchFields = [];

    protected static $excludedFields = [
        'save' => ['_token', 'item'],
        'update' => ['_token', '_method', 'item'],
    ];

    /**
     * @param  mixed  $request
     */
    public static function save($request): array
    {
        $request->request->add(['input_content' => $request->item]);
        return parent::save($request->all());
    }

    /**
     * @param  int  $id
     * @param  mixed  $request
     */
    public static function update($id, $request): array
    {
        $request->request->add([
            'input_content' => $request->item,
            'title' => $request->title ?? ''
        ]);

        return parent::update($id, $request->all());
    }

    /**
     * firstItem
     *
     * @return object
     */
    public function firstItem()
    {
        return static::$model::first();
    }

    public function requestCertificate($id)
    {
        $exam = UserCourseExam::with('user', 'course.courseSetting', 'quiz')->where('id', $id)->first();

        // Final gate: check if course allows certificates
        if (!$exam || !$exam->course?->courseSetting?->is_certificate) {
            return;
        }

        $certificate =  Certificate::first();
        $instructor =  $exam?->course?->instructors[0] ?? null;
        $user = $exam->user->userable ?? null;
        $studentName = ($user->first_name ?? '') . ' ' . ($user->last_name ?? '');
        $courseTitle = $exam->course->title;
        $instructorName = $instructor 
            ? trim(($instructor->userable?->first_name ?? '') . ' ' . ($instructor->userable?->last_name ?? ''))
            : null;

        $setting = get_theme_option('backend_setting') ?? [];
        $platformName = $setting['app_name'] ?? config('app.name');
        $date = customDateFormate($exam->updated_at, format: 'd-m-Y');
        $content =  str_replace(['{student_name}', '{platform_name}', '{course_title}', '{instructor_name}', '{course_completed_date}'], [$studentName, $platformName, $courseTitle,   $instructorName, $date], $certificate->certificate_content);
        $dataPayload = [
            'student_name' => $studentName,
            'course_title' => $courseTitle,
            'instructor_name' => $instructorName,
            'course_completed_date' => $date,
            'platform_name' => $platformName,
        ];

        UserCertificate::updateOrCreate(
            ['quiz_id' => $id, 'user_id' => authCheck()->id],
            [
                'certificate_id' => random_string(5),
                'type'  => 'quiz',
                'subject' => $courseTitle,
                'certificated_date' => now(),
                'certificate_content'  => $content,
                'certificate_data' => $dataPayload,
            ]
        );
    }
}
