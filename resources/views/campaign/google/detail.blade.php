@php
    use Carbon\Carbon;
    use App\Enums\PermissionEnum;
    use App\Enums\TableHeaderEnum;
@endphp
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .bg-cyan {
            background-color: #C6DFF2;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush
@extends('layouts.app') <!-- Extending a base layout -->

@section('content')
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                    <span class="text-primary fw-bold">Chi tiết chiến dịch - {{request()->get('name')}}</span>
                    <a class="btn btn-secondary" href="{{ route('campaign.google.index') }}">Quay lại</a>
                </h5>
                <form action="{{ route('campaign.google.detail') }}">
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Nhập để tìm kiếm</label>
                            <input type="text" name="keyword" class="form-control" placeholder="VD: Tên nhóm quảng cáo"
                                   value="{{request()->keyword}}">
                        </div>
                        <div class="col">
                            <label class="form-label w-100">Thời gian</label>
                            <input type="text" class="form-control" name="dateRange"/>
                        </div>
                        <input type="text" hidden value="{{request()->get('user_id')}}" name="user_id">
                        <input type="text" hidden value="{{request()->get('name')}}" name="name">
                        <div class="col-auto align-self-end">
                            <button class="btn btn-primary mx-2" type="submit">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
                <div class="my-3">
                    @if (Auth::user()->hasPermissionTo(PermissionEnum::ADD_ADS_GG))
                        <a class="btn btn-primary"
                           href="{{ route('campaign.google.ads.index', ['user_id' => request()->get('user_id'), 'name' => request()->get('name')]) }}">Thêm
                            nhóm quảng cáo</a>
                    @endif
                </div>
                <div class="row my-3 d-flex justify-content-between align-items-center">
                    <div class="col-6">
                        <div class="fs-5 text-primary fw-bold">Tỉ Lệ Nhấp Chuột & Tương Tác</div>
                        <div class="d-flex gap-4">
                            <div class="col bg-cyan p-3">
                                <div class="fs-6 text-secondary">Số nhấp chuột</div>
                                <div class="fs-4">{{number_format($campaign->click)}}</div>
                            </div>
                            <div class="col bg-cyan p-3">
                                <div class="fs-6 text-secondary">CTR</div>
                                <div class="fs-4">{{number_format($campaign->ctr,2).'%'}}</div>

                            </div>
                            <div class="col bg-cyan p-3">
                                <div class="fs-6 text-secondary">Hiển thị</div>
                                <div class="fs-4">{{number_format($campaign->reach)}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column justify-content-between">
                        <div class="fs-5 text-primary fw-bold">Chi Phí</div>
                        <div class="d-flex gap-4">
                            <div class="col bg-cyan p-3">
                                <div class="fs-6 text-secondary">Chi phí</div>
                                <div class="fs-4">{{number_format($campaign->amount_spent/1000) .' N đ̲'}}</div>
                            </div>
                            <div class="col bg-cyan p-3">
                                <div class="fs-6 text-secondary">CPC Trung bình</div>
                                <div class="fs-4">{{number_format($campaign->avg_cpc/1000, 2). ' N đ̲'}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-3 d-flex justify-content-between align-items-end">
                    <div class="col-6">
                        <div class="d-flex gap-4 p-2" style='background-color: #e7e7e7'>
                            <div class="w-100" id="clickChart"></div>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column justify-content-between">
                        <div class="d-flex gap-4 p-2" style='background-color: #e7e7e7'>
                            <div class="w-100" id="amountChart"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead>
                                <tr class="align-middle">
                                    @foreach(TableHeaderEnum::CAMPAIGN_GOOGLE_DETAIL as $item)
                                        <th>
                                            @if($item['sortable'])
                                                <div class="d-flex align-items-center"
                                                     onclick="sort('{{$item['column']}}')">
                                                    <div class="cursor-pointer user-select-none me-2">
                                                        {{$item['label']}}
                                                        @if(request()->get('column')=== $item['column'])
                                                            @if(request()->get('sort') === 'asc')
                                                                <i class='fa-duotone fa-sort-up'></i>
                                                            @elseif(request()->get('sort') === 'desc')
                                                                <i class='fa-duotone fa-sort-down'></i>
                                                            @endif
                                                        @else
                                                            <i class='fa-light fa-sort'></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                {{$item['label']}}
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($adsGoogles as $adsGoogle)
                                    <tr>

                                        <td class="text-center">{{ ($adsGoogles->currentPage() - 1) * $adsGoogles->perPage() + $loop->iteration }}</td>
                                        <td>{{ $adsGoogle->name }}</td>
                                        <td class="text-center">{{$adsGoogle->click}}</td>
                                        <td class="text-end">{{ number_format($adsGoogle->ctr,2) .'%' }}</td>
                                        <td class="text-end">{{ number_format($adsGoogle->avg_cpc) }}</td>
                                        <td class="text-end">{{ number_format($adsGoogle->amount_spent) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td class="text-center" colspan="2">Tổng cộng</td>
                                    <td class="text-center">{{$campaign->click}}</td>
                                    <td class="text-end">{{ number_format($campaign->ctr,2) .'%' }}</td>
                                    <td class="text-end">{{ number_format($campaign->avg_cpc) }}</td>
                                    <td class="text-end">{{ number_format($campaign->amount_spent) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        {{$adsGoogles}}
                    </div>
                    <div class="col-6 align-self-center">
                            {!! $adsChart->container() !!}
{{--                        <div class="d-flex flex-wrap align-content-center h-100" style='background-color: #e7e7e7'>--}}
{{--                            <div class="w-100" id="adsChart"></div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        Apex.chart = {
            locales: [{
                "name": "vi",
                "options": {
                    "months": ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
                    "shortMonths": ["Thg 1", "Thg 2", "Thg 3", "Thg 4", "Thg 5", "Thg 6", "Thg 7", "Thg 8", "Thg 9", "Thg 10", "Thg 11", "Thg 12"],
                    "days": ["Chủ nhật", "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7"], //hãy nhớ rằng chủ nhật là ngày đầu tuần nhé
                    "shortDays": ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                    "toolbar": { //tooltip hiển thị khi bạn hover vào các icon tương ứng
                        "exportToSVG": "Tải định dạng SVG",
                        "exportToPNG": "Tải định dạng PNG",
                    }
                }
            }],
            defaultLocale: "vi",
            toolbar: {
                show: false,
            }
        }
        let amountOptions = {
            series: [
                {
                    name: "Chi phí",
                    data: @json($amountChart['amount_spent'])
                },
                {
                    name: "CPC Tr.Bình",
                    data: @json($amountChart['avg_cpc'])
                },
            ],
            chart: {
                height: 150,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            labels: @json($amountChart['labels']),
            xaxis: {
                type: 'datetime',
            },
            yaxis: [{
                seriesName: 'Chi phí',
                labels: {
                    formatter: function (val) {
                        return (val / 1000).toFixed(0) + ' N';
                    },
                },
                title: {
                    text: 'Chi phí'
                },
                min: 0
            },
                {
                    seriesName: 'CPC Tr.Bình',
                    labels: {
                        formatter: function (val) {
                            return (val / 1000) + ' N';
                        },
                    },
                    opposite: true,
                    title: {
                        text: 'CPC Tr.Bình'
                    },
                    min: 0,
                },
            ]
        };

        let amountChart = new ApexCharts(document.querySelector("#amountChart"), amountOptions);
        amountChart.render();
    </script>
    {{ $adsChart->script() }}
{{--    <script>--}}
{{--        let adsOptions = @json($adsChart)--}}

{{--        let adsChart = new ApexCharts(document.querySelector("#adsChart"), adsOptions);--}}
{{--        adsChart.render();--}}
{{--    </script>--}}
    <script>
        let clickOptions = {
            series: [
                {
                    name: "Click",
                    data: @json($clickChart['click'])
                },
                {
                    name: "Ctr",
                    data: @json($clickChart['ctr'])
                },
            ],
            chart: {
                height: 150,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            labels: @json($clickChart['labels']),
            xaxis: {
                type: 'datetime',
            },
            yaxis: [{
                seriesName: 'Click',
                labels: {
                    formatter: function (val) {
                        return (val).toFixed(0);
                    },
                },
                title: {
                    text: 'Click'
                },
                min: 0
            },
                {
                    seriesName: 'CTR',
                    labels: {
                        formatter: function (val) {
                            return val + ' %';
                        },
                    },
                    opposite: true,
                    title: {
                        text: 'CTR'
                    },
                    min: 0,
                },
            ]
        };

        let clickChart = new ApexCharts(document.querySelector("#clickChart"), clickOptions);
        clickChart.render();
    </script>
    <script>
        $(function () {
            const searchParams = new URLSearchParams(window.location.search);
            let startDate = moment().startOf('month')
            let endDate = moment().subtract(1, 'days')
            if (searchParams.has('dateRange')) {
                const dateRange = searchParams.get('dateRange').split(' - ')
                startDate = moment(dateRange[0], 'DD-MM-YYYY')
                endDate = moment(dateRange[1], 'DD-MM-YYYY')
            }
            $('input[name="dateRange"]').daterangepicker({
                opens: 'left',
                startDate: startDate,
                endDate: endDate,
                maxDate: moment().subtract(1, 'days'),
                locale: {
                    format: 'DD-MM-YYYY',
                    cancelLabel: 'Hủy',
                    applyLabel: 'Chọn',
                    customRangeLabel: "Tùy chỉnh",
                },
                alwaysShowCalendars: true,
                ranges: {
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                    '14 ngày trước': [moment().subtract(14, 'days'), moment().subtract(1, 'days')],
                    '30 ngày trước': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
                    'Tuần này': [moment().startOf('week'), moment().subtract(1, 'days')],
                    'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').subtract(1, 'days')],
                    'Tháng này': [moment().startOf('month'), moment().subtract(1, 'days')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            }, function (start, end, label) {

            });
        });
    </script>
@endsection
<script>
    function sort(column) {
        let searchParams = new URLSearchParams(window.location.search);
        if (searchParams.get('column') === column) {
            searchParams.set('sort', searchParams.get('sort') === 'desc' ? 'asc' : 'desc')
        } else {
            searchParams.set('column', column)
            searchParams.set('sort', 'asc')
        }
        window.location.search = searchParams.toString();
    }

    function updateStatus(userId, name, status) {

    }
</script>

