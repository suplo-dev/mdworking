@php
    use Carbon\Carbon;
    use App\Enums\PermissionEnum;
    use App\Enums\TableHeaderEnum;
@endphp
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endpush
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush
@extends('layouts.app') <!-- Extending a base layout -->

@section('content')
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title text-primary fw-bold">Danh sách chiến dịch Google</h5>
                <form action="{{ route('campaign.google.index') }}">
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Nhập để tìm kiếm</label>
                            <input type="text" name="keyword" class="form-control" placeholder="VD: Tên chiến dịch"
                                   value="{{request()->keyword}}">
                        </div>
                        <div class="col">
                            <label class="form-label w-100">Thời gian</label>
                            <input type="text" class="form-control" name="dateRange"/>
                        </div>
                        <div class="col-auto align-self-end">
                            <button class="btn btn-primary mx-2">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
                <div class="my-3">
                    @if (Auth::user()->hasPermissionTo(PermissionEnum::ADD_ADS_GG))
                        <a class="btn btn-primary" href="{{ route('campaign.google.add') }}">Thêm chiến dịch</a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                        <tr class="align-middle">
                            @foreach(TableHeaderEnum::CAMPAIGN_GOOGLE as $item)
                                <th>
                                    @if($item['sortable'])
                                        <div class="d-flex align-items-center" onclick="sort('{{$item['column']}}')">
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
                        @foreach($campaigns as $campaign)
                            <tr>
                                <td class="text-center">{{ ($campaigns->currentPage() - 1) * $campaigns->perPage() + $loop->iteration }}</td>
                                <td>{{ $campaign->name }}</td>
                                <td>Search only</td>
                                    <td class="text-center">{{$campaign->click}}</td>
                                    <td class="text-end">{{ number_format($campaign->ctr,2) .'%' }}</td>
                                    <td class="text-end">{{ number_format($campaign->avg_cpc) }}</td>
                                    <td class="text-end">{{ number_format($campaign->amount_spent) }}</td>
                                <td class="text-center">
                                    <a class="btn btn-primary"
                                       href="{{ route('campaign.google.detail', ['user_id' => $campaign->user_id, 'name'=> $campaign->name]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{$campaigns}}
            </div>
        </div>
    </div>

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

