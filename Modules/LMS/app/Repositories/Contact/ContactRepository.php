<?php

namespace Modules\LMS\Repositories\Contact;

use Illuminate\Support\Facades\Mail;
use Modules\LMS\Emails\NoticesMail;
use Modules\LMS\Models\General\Contact;
use Modules\LMS\Repositories\BaseRepository;

class ContactRepository extends BaseRepository
{
    protected static $model = Contact::class;

    protected static $exactSearchFields = [];

    protected static $excludedFields = [
        'save' => ['_token'],
        'update' => ['_token', '_method'],
    ];

    protected static $rules = [
        'save' => [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required',
        ],
    ];

    /**
     * @param  int  $id
     * @param  array  $data
     */
    public static function update($id, $data): array
    {
        return parent::update($id, $data);
    }

    /**
     * @param  array|object  $data
     */
    public static function save($data): array
    {
        $contact = parent::save($data);
        
        if ($contact['status'] === 'success') {
            try {
                $adminEmail = app()->isLocal() 
                    ? 'prabhu.ctrlnext@gmail.com' 
                    : 'zenvycoaching@gmail.com';
                
                $mailData = [
                    'title' => 'New Inquiry Received: ' . ($data['subject'] ?? 'No Subject'),
                    'message' => "<strong>Name:</strong> {$data['name']}<br>" .
                                 "<strong>Email:</strong> {$data['email']}<br>" .
                                 "<strong>Phone:</strong> " . ($data['phone'] ?? 'N/A') . "<br><br>" .
                                 "<strong>Message:</strong><br>{$data['message']}",
                ];
                
                Mail::to($adminEmail)->queue(new NoticesMail($mailData));
            } catch (\Throwable $th) {
                // Log the error so we can debug it
                \Log::error("Failed to send admin inquiry notification: " . $th->getMessage());
            }
        }
        
        return $contact;
    }

    /**
     *  reply
     *
     * @param  mixed  $request
     * @return array
     */
    public function reply($request)
    {
        try {
            $data = [
                'notice_title' => $request->title,
                'message' => $request->message,
            ];
            Mail::to($request->email)->queue(new NoticesMail($data));

            return [
                'status' => 'success',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' => translate('Something Wrong!'),
            ];
        }
    }
}
