<?php

namespace App\Http\Controllers\Campaign\Google;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advertisement\Google\AddAdsGoogleRequest;
use App\Http\Requests\User\AddUserRequest;
use App\Services\QueryAdsFacebookService;
use App\Services\QueryUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddAdsGoogleController extends Controller
{
    public function __construct(private readonly QueryAdsFacebookService $queryAdsFacebookService)
    {
    }

    public function __invoke(AddAdsGoogleRequest $addAdsFacebookRequest): JsonResponse
    {
        $data = $this->queryAdsFacebookService->add($addAdsFacebookRequest->validated());

        return $this->responseData($data);
    }
}
