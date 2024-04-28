<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignFacebook extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'status',
        'type',
        'result',
        'reach',
        'impression',
        'amount_spent',
        'started_at',
        'ended_at',
    ];

}
