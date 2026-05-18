<?php

namespace Modules\LMS\Models\Purchase;

use Modules\LMS\Models\User;
use Modules\LMS\Models\Courses\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\LMS\Models\Courses\Bundle\CourseBundle;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PurchaseDetails extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    protected $casts = [
        'details' => 'array',
        'topic_permissions' => 'array',
    ];

    public function course(): BelongsTo
    {

        return $this->belongsTo(Course::class);
    }

    public function courseBundle(): BelongsTo
    {
        return $this->belongsTo(CourseBundle::class, 'bundle_id', 'id');
    }

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    public function purchase(): BelongsTo
    {

        return $this->belongsTo(Purchase::class);
    }

    /**
     * Check if a topic type is permitted for this enrollment.
     * Defaults to true if permissions are not set (backward compatible).
     */
    public function canAccessTopic(string $type): bool
    {
        if (is_null($this->topic_permissions)) {
            return false; // restricted access by default
        }
        return $this->topic_permissions[$type] ?? false;
    }
}
