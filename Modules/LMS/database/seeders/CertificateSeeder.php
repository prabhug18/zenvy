<?php

namespace Modules\LMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\LMS\Models\Certificate\Certificate;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'title' =>  'Frontend Development',
                'certificate_content' => '<div class="certificate-template-container" id="certificateImg" style="background-image: url(\'http://127.0.0.1:8000/lms/assets/images/certificate-template.jpg\'); background-repeat: no-repeat; background-size: 100% 100%;">
                            <div data-name="student" class="dragable-element ui-draggable ui-draggable-handle" style="left: 440px; top: 422px;">{student_name}</div>
                            <div data-name="course-title" class="dragable-element ui-draggable ui-draggable-handle" style="left: 440px; top: 472px;">{course_title}</div>
                            <div data-name="course-completed-date" class="dragable-element ui-draggable ui-draggable-handle" style="left: 545px; top: 522px;">{course_completed_date}</div>
                            <div data-name="instructor-name" class="dragable-element ui-draggable ui-draggable-handle" style="left: 284px; top: 645px;">{instructor_name}</div>
                            <div data-name="platform-name" class="dragable-element ui-draggable ui-draggable-handle" style="left: 450px; top: 745px;">{platform_name}</div>
                        </div>',
                'input_content' => '{"bg":null,"title":{"color":"#000000","font_size":"18"}}',
                'type'  =>  'course',

            ],
        ];

        if (Certificate::count() == 0) {
            Certificate::insert($data);
        }
    }
}
