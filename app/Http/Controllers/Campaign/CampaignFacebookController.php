<?php

namespace App\Http\Controllers\Campaign;

use App\Enums\PermissionEnum;
use App\Enums\TableSearchEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\Facebook\AddCampaignFacebookRequest;
use App\Imports\CampaignFacebookImport;
use App\Models\CampaignFacebook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CampaignFacebookController extends Controller
{
    public function __construct()
    {
    }

    public function search(Request $request)
    {
        $params = $request->all();
        $dateRange = explode(' - ', $request->query('dateRange'));
        $params['per_page'] = data_get($params, 'per_page', TableSearchEnum::PER_PAGE);
        $params['page'] = data_get($params, 'page', TableSearchEnum::PAGE);
        $params['column'] = data_get($params, 'column', 'status');
        $params['sort'] = data_get($params, 'sort', TableSearchEnum::SORT_DESC);
        $params['started_at'] = Carbon::parse($dateRange[0] ? $dateRange[0] : Carbon::now()->startOfMonth());
        $params['ended_at'] = Carbon::parse($dateRange[1] ?? Carbon::yesterday());
        $user = request()->user();
        $campaigns = CampaignFacebook::query()
            ->select('user_id', 'name', 'status', 'type',
                DB::raw('SUM(result) as result'),
                DB::raw('SUM(reach) as reach'),
                DB::raw('SUM(impression) as impression'),
                DB::raw('SUM(amount_spent) as amount_spent'),
                DB::raw('(SUM(amount_spent)/SUM(result)) as cost_per_result'),
                'ended_at')
            ->when(data_get($params, 'keyword'), function ($q, $keyword) {
                $q->where('name', 'like', "%$keyword%");
            })
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where('started_at', '>=', $startDateTime);
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where('started_at', '<=', $endDateTime);
            })
            ->when(!$user->hasAnyPermission([PermissionEnum::ADD_ADS_FB, PermissionEnum::UPDATE_ADS_FB]), function ($q) use ($user) {
                $q->where('user_id', '=', $user->id);
            })
            ->havingNotNull('type')
            ->orderBy($params['column'], $params['sort'])
            ->groupBy('user_id', 'name', 'status', 'type', 'ended_at')
            ->paginate(perPage: $params['per_page'], page: $params['page']);
        return view('campaign.facebook.index', ['campaigns' => $campaigns]);
    }

    public function updateStatus(Request $request)
    {
        CampaignFacebook::query()
            ->where('user_id', $request->get('user_id'))
            ->where('name', $request->get('name'))
            ->where('status', !!$request->get('status'))
            ->update(['status' => !$request->get('status')]);
        return back()->withInput();
    }

    public function addView()
    {
        return view('campaign.facebook.add');
    }

    public function add(AddCampaignFacebookRequest $request)
    {
        $params = $request->validated();
        if (data_get($params, 'file')) {
            Excel::import(new CampaignFacebookImport, $params['file']);
        }
        return view('campaign.facebook.add');
    }
}
