<?php

namespace App\Http\Controllers\Campaign\Google;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advertisement\Google\SearchCampaignGoogleRequest;
use App\Models\CampaignGoogle;
use App\Services\QueryAdsFacebookService;
use App\Services\QueryCampaignGoogleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DetailCampaignGoogleController extends Controller
{
    public function __construct(private readonly QueryCampaignGoogleService $queryCampaignGoogleService)
    {
    }

    public function __invoke(CampaignGoogle $campaignGoogle, SearchCampaignGoogleRequest $searchCampaignGoogleRequest): JsonResponse
    {
        $data = $this->queryCampaignGoogleService->detail([...$searchCampaignGoogleRequest->all(), 'campaign_id' => $campaignGoogle?->id]);

        return $this->responseData($data);
    }
}
