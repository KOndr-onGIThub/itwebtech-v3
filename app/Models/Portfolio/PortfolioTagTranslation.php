<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioTagTranslation extends Model
{
    protected $table = 'portfolio_tag_translations';

    protected $fillable = [
        'tag_id',
        'locale',
        'name',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(PortfolioTag::class, 'tag_id');
    }
}
