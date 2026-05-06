<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioProjectScreenshotTranslation extends Model
{
    protected $table = 'portfolio_project_screenshot_translations';

    protected $fillable = [
        'screenshot_id',
        'locale',
        'alt',
        'caption',
    ];

    public function screenshot(): BelongsTo
    {
        return $this->belongsTo(PortfolioProjectScreenshot::class, 'screenshot_id');
    }
}
