<?php

namespace Modules\LMS\Repositories\HomepageVideo;

use Modules\LMS\Models\HomepageVideo;
use Modules\LMS\Repositories\BaseRepository;

class HomepageVideoRepository extends BaseRepository
{
    protected static $model = HomepageVideo::class;

    protected static $exactSearchFields = [];

    protected static $excludedFields = [
        'save' => ['video', 'thumbnail_file', '_token', 'locale'],
        'update' => ['video', 'thumbnail_file', '_token', '_method', 'locale'],
    ];

    protected static $rules = [
        'save' => [
            'title' => 'required|string',
            'video' => 'required_if:video_type,upload|file|mimes:mp4,mov,ogg,qt',
            'video_url' => 'required_if:video_type,youtube,vimeo',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp',
        ],
        'update' => [
            'title' => 'required|string',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp',
        ],
    ];

    /**
     */
    public static function save($request): array
    {
        $data = $request->all();
        // Remove video_url from data if it's empty to prevent overwriting with NULL
        if (empty($data['video_url'])) {
            unset($data['video_url']);
        }
        if ($request->hasFile('video')) {
            $videoPath = parent::upload($request, fieldname: 'video', file: '', folder: 'lms/videos');
            $data['video_url'] = $videoPath;
        }

        if ($request->hasFile('thumbnail_file')) {
            $thumbnailPath = parent::upload($request, fieldname: 'thumbnail_file', file: '', folder: 'lms/videos/thumbnails');
            $data['thumbnail'] = $thumbnailPath;
        }

        $response = parent::save($data);
        $video = $response['data'] ?? null;
        if ($response['status'] === 'success' && $video) {
            $translationData = self::translateData($request->all());
            self::translate($video, $translationData, locale: $request->locale ?? app()->getLocale());
        }

        return $response;
    }

    /**
     * @param  int  $id
     * @param  mixed  $request
     */
    public static function update($id, $request): array
    {
        $videoResponse = parent::first(value: $id);
        $homepageVideo = $videoResponse['data'] ?? null;

        if (!$homepageVideo) {
            return [
                'status' => 'error',
                'data' => 'The model not found.',
            ];
        }

        $translationData = self::translateData($request->all());
        $defaultLanguage = app()->getLocale();
        $locale = $request->locale ?? $defaultLanguage;
        self::translate($homepageVideo, $translationData, $locale);

        if ($request->locale && $defaultLanguage !== $request->locale) {
            return [
                'status' => 'success',
                'data' => $homepageVideo,
            ];
        }

        $data = $request->all();
        // Remove video_url from data if it's empty to prevent overwriting with NULL
        if (empty($data['video_url'])) {
            unset($data['video_url']);
        }

        if ($request->video_type == 'upload' && $request->hasFile('video')) {
            $videoPath = parent::upload($request, fieldname: 'video', file: $homepageVideo->video_url, folder: 'lms/videos');
            $data['video_url'] = $videoPath;
        }

        if ($request->hasFile('thumbnail_file')) {
            $thumbnailPath = parent::upload($request, fieldname: 'thumbnail_file', file: $homepageVideo->thumbnail, folder: 'lms/videos/thumbnails');
            $data['thumbnail'] = $thumbnailPath;
        }

        \Illuminate\Support\Facades\Log::info('Updating video data:', $data);
        $response = parent::update($id, $data);

        return $response;
    }

    /**
     *  delete
     */
    public static function delete($id, $data = [], $options = [], $relations = []): array
    {
        $response = parent::first($id, withTrashed: true);
        $video = $response['data'] ?? null;
        if ($response['status'] == 'success' && $video) {
            $isDeleteAble = true;
            if (static::isSoftDeleteEnable() && !$video->trashed()) {
                $isDeleteAble = false;
            }

            if ($isDeleteAble) {
                if ($video->video_type == 'upload') {
                    parent::fileDelete(folder: 'lms/videos', file: $video->video_url);
                }
                if ($video->thumbnail) {
                    parent::fileDelete(folder: 'lms/videos/thumbnails', file: $video->thumbnail);
                }
            }
            return parent::delete($id, $data);
        }
        return $response;
    }

    /**
     *  statusChange
     */
    public static function statusChange($id): array
    {
        $video = parent::first($id);
        $video = $video['data'];
        $video->status = !$video->status;
        $video->save();

        return [
            'status' => 'success',
            'message' => translate('Status Change Successfully')
        ];
    }

    public static function translateData(array $data)
    {
        return [
            'title' => $data['title'],
        ];
    }

    public static function translate($video, $data, $locale)
    {
        $video->translations()->updateOrCreate(['locale' => $locale], [
            'locale' => $locale,
            'data' => $data
        ]);
    }
}
