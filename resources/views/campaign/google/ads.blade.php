@php
    use App\Enums\PermissionEnum;
    use Carbon\Carbon;
@endphp

@extends('layouts.app')
@section('content') <!-- Content section in Blade -->
<div class="container">
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between">
                <span class="text-primary fw-bold">Thêm nhóm quảng cáo - {{$campaign->name}}</span>
                <a class="btn btn-secondary" href="{{ url()->previous() }}">Quay lại</a> <!-- Routing -->
            </h5>

            <form action="{{ route('campaign.google.ads.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-4">
                        <input hidden type="text" value="{{$campaign->id}}" name="campaign_id">
                        <input
                            type="file"
                            name="file"
                        class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                        accept=".xls,.xlsx,.csv"
                        />
                        @if ($errors->has('file')) <!-- Blade error handling -->
                        <div class="invalid-feedback">{{ $errors->first('file') }}</div>
                        @endif
                    </div>
                </div>

                <div class="d-flex my-3">
                    <button class="btn btn-primary me-2 ms-auto" type="submit">Thêm</button>
                    <a class="btn btn-secondary" href="{{ url()->previous() }}">Hủy</a> <!-- Routing -->
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
