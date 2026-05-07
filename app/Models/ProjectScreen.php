<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectScreen extends Model
{
    protected $fillable = [
        'project_id',
        'is_video',
        'video_url',
        'screen_shot',
        'position',
        'title',
        'description',
    ];

    protected $casts = [
        'is_video' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
