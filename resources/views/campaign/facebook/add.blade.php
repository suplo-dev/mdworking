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
                <span class="text-primary fw-bold">Thêm chiến dịch Facebook</span>
                <a class="btn btn-secondary" href="{{ route('campaign.facebook.index') }}">Quay lại</a> <!-- Routing -->
            </h5>

            <form action="{{ route('campaign.facebook.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-4">
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
                    <a class="btn btn-secondary" href="{{ route('campaign.facebook.index') }}">Hủy</a> <!-- Routing -->
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
