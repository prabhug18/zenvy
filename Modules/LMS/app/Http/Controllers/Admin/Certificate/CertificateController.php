<?php

namespace Modules\LMS\Http\Controllers\Admin\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Repositories\Certificate\CertificateRepository;

class CertificateController extends Controller
{
    public function __construct(protected CertificateRepository $certificate) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = $this->certificate->get();
        $certificates = $certificates['data'];

        return view('portal::admin.certificate.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $certificate = $this->certificate->firstItem();
        return view('portal::admin.certificate.create', compact('certificate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $certificate = $this->certificate->save($request);
        if ($certificate['status'] !== 'success') {
            return $certificate;
        }
        return [
            'status' => 'success',
            'message' => translate('Save Successfully'),
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $certificate = $this->certificate->first($id);
        $certificate = $certificate['data'];

        return view('portal::admin.certificate.create', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $certificate = $this->certificate->update($id, $request);
        if ($certificate['status'] !== 'success') {
            return $certificate;
        }
        return [
            'status' => 'success',
            'message' => translate('Update Successfully'),
        ];
    }

    /**
     * Preview the specified resource with dummy data.
     */
    public function preview($id)
    {
        $certificateRes = $this->certificate->first($id);
        $certificate = $certificateRes['data'];
        
        if (!$certificate) {
            return back()->with('error', translate('Certificate not found'));
        }

        $setting = get_theme_option('backend_setting') ?? [];
        $platformName = $setting['app_name'] ?? config('app.name');

        $content = str_replace(
            ['{student_name}', '{platform_name}', '{course_title}', '{instructor_name}', '{course_completed_date}'],
            ['Santhosh (Test Student)', $platformName, 'Mastering Web Development (Test Course)', 'Jane Smith (Test Instructor)', date('d-m-Y')],
            $certificate->certificate_content
        );

        $certificate->certificate_content = $content;

        return view('portal::certificate.download', compact('certificate'));
    }
}
