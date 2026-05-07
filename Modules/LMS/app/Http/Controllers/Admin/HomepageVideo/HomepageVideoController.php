<?php

namespace Modules\LMS\Http\Controllers\Admin\HomepageVideo;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\LMS\Repositories\HomepageVideo\HomepageVideoRepository;

class HomepageVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $options = [];
        $filterType = $request->filter ?? '';

        switch ($filterType) {
            case 'trash':
                $options['onlyTrashed'] = [];
                break;
            case 'all':
                $options['withTrashed'] = [];
                break;
        }

        $response = HomepageVideoRepository::paginate(10, [
            'translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }
        ], $options);

        $videos = $response['data'] ?? [];
        $countResponse = HomepageVideoRepository::trashCount();

        $countData = [
            'total' => 0,
            'published' => 0,
            'trashed' => 0
        ];

        if ($countResponse['status'] === 'success' && isset($countResponse['data'])) {
            $countData = $countResponse['data']->toArray();
        }

        return view('portal::admin.homepage-video.index', compact('videos', 'countData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('portal::admin.homepage-video.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        if (!has_permissions($request->user(), ['add.homepage-video'])) {
            return json_error('You have no permission.');
        }

        $response = HomepageVideoRepository::save($request);

        if ($response['status'] !== 'success') {
            return response()->json($response);
        }

        return $this->jsonSuccess('Video saved successfully!', route('homepage-video.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $response = HomepageVideoRepository::first($id, withTrashed: true);
        $video = $response['data'] ?? null;
        return view('portal::admin.homepage-video.view', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     */
    public function edit($id, Request $request)
    {
        if (!has_permissions($request->user(), ['edit.homepage-video'])) {
            toastr()->error(translate('You have no permission.'));
            return redirect()->back();
        }

        $locale = $request->get('locale', app()->getLocale());
        $response = HomepageVideoRepository::first($id, relations: [
            'translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }
        ]);

        $video = $response['data'] ?? [];

        return view('portal::admin.homepage-video.create', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        if (!has_permissions($request->user(), ['edit.homepage-video'])) {
            return json_error('You have no permission.');
        }

        $response = HomepageVideoRepository::update($id, $request);

        if ($response['status'] !== 'success') {
            return response()->json($response);
        }
        return $this->jsonSuccess('Video updated successfully!', route('homepage-video.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     */
    public function destroy($id, Request $request)
    {
        if (!has_permissions($request->user(), ['delete.homepage-video'])) {
            return json_error('You have no permission.');
        }

        $response = HomepageVideoRepository::delete($id, ['status' => 0]);
        $response['url'] = route('homepage-video.index');

        return response()->json($response);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     */
    public function restore($id, Request $request)
    {
        if (!has_permissions($request->user(), ['delete.homepage-video'])) {
            return json_error('You have no permission.');
        }

        $response = HomepageVideoRepository::restore($id);
        $response['url'] = route('homepage-video.index');

        return response()->json($response);
    }

    /**
     * Change status of the specified resource.
     *
     * @param int $id
     * @param Request $request
     */
    public function statusChange($id, Request $request)
    {
        if (!has_permissions($request->user(), ['status.homepage-video'])) {
            return json_error('You have no permission.');
        }

        $response = HomepageVideoRepository::statusChange($id);
        return response()->json($response);
    }
}
