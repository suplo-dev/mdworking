<?php

namespace App\Http\Controllers\Campaign;

use App\Enums\PermissionEnum;
use App\Enums\TableSearchEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\Google\AddCampaignGoogleRequest;
use App\Imports\AdsGoogleImport;
use App\Imports\CampaignGoogleImport;
use App\Models\AdsGoogle;
use App\Models\CampaignGoogle;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\PieChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CampaignGoogleController extends Controller
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
        $params['column'] = data_get($params, 'column', 'user_id');
        $params['sort'] = data_get($params, 'sort', TableSearchEnum::SORT_DESC);
        $params['started_at'] = Carbon::parse($dateRange[0] ?: Carbon::now()->startOfMonth());
        $params['ended_at'] = Carbon::parse($dateRange[1] ?? Carbon::yesterday());

        $user = request()->user();
        $campaigns = CampaignGoogle::query()
            ->select(
                'campaign_googles.id',
                'campaign_googles.user_id',
                'campaign_googles.name',
                'campaign_googles.type',
                DB::raw('SUM(ads_googles.click) as click'),
                DB::raw('(SUM(ads_googles.click)/SUM(ads_googles.reach)*100) as ctr'),
                DB::raw('SUM(ads_googles.amount_spent) as amount_spent'),
                DB::raw('(SUM(ads_googles.amount_spent)/SUM(ads_googles.click)) as avg_cpc'),
            )
            ->leftJoin('ads_googles', function ($join) {
                $join->on('ads_googles.campaign_id', '=', 'campaign_googles.id');
            })
            ->when(data_get($params, 'keyword'), function ($q, $keyword) {
                $q->where('campaign_googles.name', 'like', "%$keyword%");
            })
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where(function ($q) use ($startDateTime) {
                    $q->where('ads_googles.started_at', '>=', $startDateTime);
                    $q->orWhere('ads_googles.started_at', null);
                });
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where(function ($q) use ($endDateTime) {
                    $q->where('ads_googles.started_at', '<=', $endDateTime);
                    $q->orWhere('ads_googles.started_at', null);
                });
            })
            ->when(!$user->hasAnyPermission([PermissionEnum::ADD_ADS_GG, PermissionEnum::UPDATE_ADS_GG]), function ($q) use ($user) {
                $q->where('campaign_googles.user_id', '=', $user->id);
            })
            ->groupBy('campaign_googles.id', 'campaign_googles.user_id', 'campaign_googles.name', 'campaign_googles.type')
            ->orderBy($params['column'], $params['sort'])
            ->paginate(perPage: $params['per_page'], page: $params['page']);
        return view('campaign.google.index', ['campaigns' => $campaigns]);
    }

    public function detail(Request $request)
    {
        $params = $request->all();
        $dateRange = explode(' - ', $request->query('dateRange'));
        $params['per_page'] = data_get($params, 'per_page', TableSearchEnum::PER_PAGE);
        $params['page'] = data_get($params, 'page', TableSearchEnum::PAGE);
        $params['column'] = data_get($params, 'column', 'name');
        $params['sort'] = data_get($params, 'sort', TableSearchEnum::SORT_DESC);
        $params['started_at'] = Carbon::parse($dateRange[0] ?: Carbon::now()->startOfMonth());
        $params['ended_at'] = Carbon::parse($dateRange[1] ?? Carbon::yesterday());

        $user = request()->user();
        $campaign = CampaignGoogle::query()
            ->select(
                'campaign_googles.id',
                'campaign_googles.user_id',
                'campaign_googles.name',
                'campaign_googles.type',
                DB::raw('SUM(ads_googles.click) as click'),
                DB::raw('SUM(ads_googles.reach)as reach'),
                DB::raw('(SUM(ads_googles.click)/SUM(ads_googles.reach)*100) as ctr'),
                DB::raw('SUM(ads_googles.amount_spent) as amount_spent'),
                DB::raw('(SUM(ads_googles.amount_spent)/SUM(ads_googles.click)) as avg_cpc'),
            )
            ->leftJoin('ads_googles', function ($join) {
                $join->on('ads_googles.campaign_id', '=', 'campaign_googles.id');
            })
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where(function ($q) use ($startDateTime) {
                    $q->where('ads_googles.started_at', '>=', $startDateTime);
                    $q->orWhere('ads_googles.started_at', null);
                });
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where(function ($q) use ($endDateTime) {
                    $q->where('ads_googles.started_at', '<=', $endDateTime);
                    $q->orWhere('ads_googles.started_at', null);
                });
            })
            ->when(!$user->hasAnyPermission([PermissionEnum::ADD_ADS_GG, PermissionEnum::UPDATE_ADS_GG]), function ($q) use ($user) {
                $q->where('campaign_googles.user_id', '=', $user->id);
            })
            ->where('campaign_googles.user_id', $request->get('user_id'))
            ->where('campaign_googles.name', $request->get('name'))
            ->groupBy('campaign_googles.id', 'campaign_googles.user_id', 'campaign_googles.name', 'campaign_googles.type')
            ->get()->first();
        $adsGoogles = AdsGoogle::query()
            ->select('*',
                DB::raw('amount_spent/click as avg_cpc'),
                DB::raw('click/reach*100 as ctr'))
            ->when(data_get($params, 'keyword'), function ($q, $keyword) {
                $q->where('name', 'like', "%$keyword%");
            })
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where('started_at', '>=', $startDateTime);
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where('started_at', '<=', $endDateTime);
            })
            ->whereRelation('campaign', 'user_id', '=', $request->get('user_id'))
            ->whereRelation('campaign', 'name', '=', $request->get('name'))
            ->orderBy($params['column'], $params['sort'])
            ->paginate(perPage: $params['per_page'], page: $params['page']);

        $adsChart = $this->renderAdsChart([...$params, 'campaign_id' => $campaign->id]);
        $clickChart = $this->renderClickChart([...$params, 'campaign_id' => $campaign->id]);
        $amountChart = $this->renderAmountChart([...$params, 'campaign_id' => $campaign->id]);

        return view('campaign.google.detail', ['campaign' => $campaign, 'adsGoogles' => $adsGoogles, 'adsChart' => $adsChart, 'clickChart' => $clickChart, 'amountChart' => $amountChart]);
    }

//    private function renderAdsChart($params)
//    {
//        $adsGoogles = AdsGoogle::query()
//            ->where('campaign_id', $params['campaign_id'])
//            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
//                $q->where('started_at', '>=', $startDateTime);
//            })
//            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
//                $q->where('started_at', '<=', $endDateTime);
//            })
//            ->orderBy('click', 'desc')
//            ->take(9)->get();
//        $adsGooglesSub = AdsGoogle::query()
//            ->select(
//                DB::raw('SUM(click) as click'),
//            )
//            ->where('campaign_id', $params['campaign_id'])
//            ->whereNotIn('id', $adsGoogles->pluck('id'))
//            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
//                $q->where('started_at', '>=', $startDateTime);
//            })
//            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
//                $q->where('started_at', '<=', $endDateTime);
//            })
//            ->groupBy('campaign_id')
//            ->get()->first();
//        $data = $adsGooglesSub?->click > 0 ? [...$adsGoogles->pluck('click')->toArray(), (int)$adsGooglesSub?->click] : $adsGoogles->pluck('click')->toArray();
//        $labels = $adsGooglesSub?->click > 0 ? [...$adsGoogles->pluck('name')->toArray(), 'Khác'] : $adsGoogles->pluck('name')->toArray();
//        return [
//            "series" => $data,
//            "chart" => [
//                "type" => "pie",
//            ],
//            "labels" => $labels,
//        ];
//    }

    private function renderAdsChart($params): PieChart
    {
        $chart = new LarapexChart();

        $adsGoogles = AdsGoogle::query()
            ->where('campaign_id', $params['campaign_id'])
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where('started_at', '>=', $startDateTime);
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where('started_at', '<=', $endDateTime);
            })
            ->orderBy('click', 'desc')
            ->take(9)->get();
        $adsGooglesSub = AdsGoogle::query()
            ->select(
                DB::raw('SUM(click) as click'),
            )
            ->where('campaign_id', $params['campaign_id'])
            ->whereNotIn('id', $adsGoogles->pluck('id'))
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where('started_at', '>=', $startDateTime);
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where('started_at', '<=', $endDateTime);
            })
            ->groupBy('campaign_id')
            ->get()->first();
        $data = $adsGooglesSub?->click > 0 ? [...$adsGoogles->pluck('click')->toArray(), (int)$adsGooglesSub?->click] : $adsGoogles->pluck('click')->toArray();
        $labels = $adsGooglesSub?->click > 0 ? [...$adsGoogles->pluck('name')->toArray(), 'Khác'] : $adsGoogles->pluck('name')->toArray();
        return $chart->pieChart()
            ->addData($data)
            ->setLabels($labels);

    }

    private function renderClickChart(array $params)
    {
        $statics = AdsGoogle::query()
            ->select(
                'started_at',
                DB::raw('SUM(click) as click'),
                DB::raw('SUM(reach) as reach'),
                DB::raw('ROUND(SUM(click)/SUM(reach)*100,2) as ctr'),
            )
            ->where('campaign_id', $params['campaign_id'])
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where('started_at', '>=', $startDateTime);
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where('started_at', '<=', $endDateTime);
            })
            ->groupBy('campaign_id', 'started_at')
            ->get();
        $arrDate = $this->getDate($params['started_at'], $params['ended_at']);
        return [
            'click' => $statics->map(function ($record) {
                return ['x' => $record->started_at, 'y' => $record->click];
            }),
            'ctr' => $statics->map(function ($record) {
                return ['x' => $record->started_at, 'y' => $record->ctr];
            }),
            'labels' => $arrDate,
        ];
    }

    private function renderAmountChart(array $params)
    {
        $statics = AdsGoogle::query()
            ->select(
                'started_at',
                DB::raw('ROUND(SUM(amount_spent)/SUM(click)) as avg_cpc'),
                DB::raw('SUM(amount_spent) as amount_spent'),
            )
            ->where('campaign_id', $params['campaign_id'])
            ->when(data_get($params, 'started_at'), function ($q, $startDateTime) {
                $q->where('started_at', '>=', $startDateTime);
            })
            ->when(data_get($params, 'ended_at'), function ($q, $endDateTime) {
                $q->where('started_at', '<=', $endDateTime);
            })
            ->groupBy('campaign_id', 'started_at')
            ->get();
        $arrDate = $this->getDate($params['started_at'], $params['ended_at']);
        return [
            'avg_cpc' => $statics->map(function ($record) {
                return ['x' => $record->started_at, 'y' => $record->avg_cpc];
            }),
            'amount_spent' => $statics->map(function ($record) {
                return ['x' => $record->started_at, 'y' => $record->amount_spent];
            }),
            'labels' => $arrDate,
        ];
    }

    private function getDate($startDate, $endDate): array
    {
        // Calculate the number of days between the start and end dates
        $daysBetween = $startDate->diffInDays($endDate);

        $dates = [];
        for ($i = 0; $i <= $daysBetween; $i++) {
            $dates[] = $startDate->copy()->addDays($i)->format('Y-m-d');
        }
        return $dates;

    }


    public function add(AddCampaignGoogleRequest $request)
    {
        $params = $request->validated();
        if (data_get($params, 'file')) {
            Excel::import(new CampaignGoogleImport, $params['file']);
        }
        return redirect()->route('campaign.google.index');
    }

    public function addAds(AddCampaignGoogleRequest $request)
    {
        $params = $request->validated();
        if (data_get($params, 'file')) {
            Excel::import(new AdsGoogleImport, $params['file']);
        }
        return redirect()->route('campaign.google.index');
    }

    public function indexAds(Request $request)
    {
        $campaign = CampaignGoogle::query()
            ->where('user_id', $request->get('user_id'))
            ->where('name', $request->get('name'))
            ->first();
        return view('campaign.google.ads', ['campaign' => $campaign]);
    }

    public function view(Request $request)
    {
        return view('campaign.google.add');
    }
}
