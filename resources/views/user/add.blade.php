@php
    use App\Enums\PermissionEnum;
    use Carbon\Carbon;
@endphp

@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                    <span class="text-primary fw-bold">Thêm tài khoản</span>
                    <a class="btn btn-secondary" href="{{ route('user.index') }}">Quay lại</a>
                </h5>
                <form action="{{ route('user.add') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name') }}" placeholder="VD: Nguyễn Văn A">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email') }}" placeholder="VD: email@example.com">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col">
                            <label class="form-label">SĐT</label>
                            <input type="text" name="phone"
                                   class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                   value="{{ old('phone') }}" placeholder="VD: 0912345678">
                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-4">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select name="role" class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}">
                                @foreach ($roles as $role)
                                    <option
                                        value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role'))
                                <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="row mt-3 mb-2">
                        <label for="">Quyền</label>
                    </div>
                    @foreach(\App\Enums\ConstantEnum::PERMISSIONS as $keyGroup => $itemGroup)
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">{{ $itemGroup['label'] }}</label>
                            </div>
                            @if($itemGroup['hasChild'])
                                <div class="col">
                                    @foreach($itemGroup['value'] as $keyGroupChild => $itemGroupChild)
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="form-label">{{ $itemGroupChild['label'] }}</label>
                                            </div>
                                            <div class="col">
                                                @foreach($itemGroupChild['value'] as $key => $item)
                                                    <div class="form-check form-check-inline me-5">
                                                        <input name="permissions[]" class="form-check-input"
                                                               type="checkbox"
                                                               id="{{$keyGroup.$keyGroupChild.$key}}"
                                                               value="{{$item['value']}}"
                                                            {{ in_array($item['value'], old('permissions') ?? []) ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label"
                                                               for="{{$keyGroup.$keyGroupChild.$key}}">{{ $item['label'] }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col">
                                    <div class="row">
                                        <div class="col offset-3">
                                            @foreach($itemGroup['value'] as $key => $item)
                                                <div class="form-check form-check-inline me-5">
                                                    <input name="permissions[]" class="form-check-input" type="checkbox"
                                                           id="{{$keyGroup.$key}}" value="{{$item['value']}}"
                                                        {{ in_array($item['value'], old('permissions') ?? []) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label"
                                                           for="{{$keyGroup.$key}}">{{ $item['label'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="d-flex">
                        <button class="btn btn-primary me-2 ms-auto" type="submit">Thêm</button>
                        <a class="btn btn-secondary" href="{{ route('user.index') }}">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
