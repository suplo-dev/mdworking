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
                <h5 class="card-title text-primary fw-bold">Danh sách chiến dịch Facebook</h5>
                <form action="{{ route('campaign.facebook.index') }}">
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
                    @if (Auth::user()->hasPermissionTo(PermissionEnum::ADD_ADS_FB))
                        <a class="btn btn-primary" href="{{ route('campaign.facebook.add') }}">Thêm chiến dịch</a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                        <tr class="align-middle">
                            @foreach(TableHeaderEnum::CAMPAIGN_FACEBOOK as $item)
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
                                <td class="text-center">
                                    <div class="form-check custom form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               {{$campaign->status ? 'checked' : ''}}
                                               onclick="updateStatus('{{$campaign->user_id}}','{{$campaign->name}}', {{!!$campaign->status}})">
                                    </div>
                                </td>
                                <td>{{ $campaign->name }}</td>
                                <td>{{ $campaign->result }}</td>
                                <td>{{ $campaign->reach }}</td>
                                <td>{{ $campaign->impression }}</td>
                                <td>{{ number_format($campaign->cost_per_result) }}</td>
                                <td>{{ number_format($campaign->amount_spent) }}</td>
                                <td>{{ $campaign->ended_at ? Carbon::parse($campaign->ended_at)->format('d-m-Y') : 'Đang diễn ra' }}</td>
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
        $.ajax({
            url: '{{ route('campaign.facebook.update') }}',
            type: 'POST',
            dataType: 'json',
            data: {
                user_id: userId,
                name: name,
                status: status,
            },
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function (data) {
                console.log("Campaign status updated successfully!");
            },
            error: function (error) {
                console.error("Error updating status:", error);
            }
        })
    }
</script>

