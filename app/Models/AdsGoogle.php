<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsGoogle extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'name',
        'click',
        'reach',
        'amount_spent',
        'started_at',
        'ended_at',
    ];

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CampaignGoogle::class);
    }
}
