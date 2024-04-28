@extends('layouts.app') <!-- Extend the dashboard layout -->

@section('content') <!-- Blade section for content -->
<div class="container">
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between">
                <span>Thông tin tài khoản</span>
                <a class="btn btn-secondary" href="{{ route('dashboard') }}">Quay lại</a> <!-- Change RouterLink to a -->
            </h5>

            <!-- Form for updating profile -->
            <form action="{{ route('profile.update') }}" method="POST"> <!-- Form action with CSRF token -->
                @csrf <!-- Laravel CSRF protection -->
                @method('PUT') <!-- Method override for updating -->

                <div class="row">
                    <div class="col">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $user->name) }}" placeholder="VD: Nguyễn Văn A">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="col">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email', $user->email) }}" placeholder="VD: email@example.com">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="col">
                        <label class="form-label">SĐT</label>
                        <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone', $user->phone) }}" placeholder="VD: 0912345678">
                        @if ($errors->has('phone'))
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row my-3">
                    <div class="col-4">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="role" class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}" disabled> <!-- Disabled roles -->
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->role) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('role'))
                            <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                        @endif
                    </div>
                    <div class="col-4">
                        <label class="form-label">Ngày tạo</label>
                        <input type="text" class="form-control" disabled value="{{ $user->created_at->format('Y-m-d H:i:s') }}">
                    </div>
                </div>

                <div class="d-flex">
                    <button class="btn btn-primary me-2 ms-auto" type="submit">Cập nhật</button>
                    <a class="btn btn-secondary" href="{{ route('dashboard') }}">Hủy</a> <!-- Cancel -->
                </div>
            </form>

            <!-- Change Password Section -->
            <h5 class="card-title d-flex justify-content-between">
                <span>Đổi mật khẩu</span>
            </h5>

            <!-- Form for changing password -->
            <form action="{{ route('profile.changePassword') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Mật khẩu hiện tại</label>
                    <div class="col-sm-10">
                        <input type="password" name="old_password"
                               class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}"
                               placeholder="Nhập mật khẩu hiện tại">
                        @if ($errors->has('old_password'))
                            <div class="invalid-feedback">{{ $errors->first('old_password') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Mật khẩu mới</label>
                    <div class="col-sm-10">
                        <input type="password" name="password"
                               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="Nhập mật khẩu mới">
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Nhập lại mật khẩu mới</label>
                    <div class="col-sm-10">
                        <input type="password" name="password_confirmation"
                               class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                               placeholder="Xác nhận mật khẩu mới">
                        @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>
                </div>

                <div class="d-flex">
                    <button class="btn btn-primary me-2 ms-auto" type="submit">Cập nhật</button>
                    <a class="btn btn-secondary" href="{{ route('dashboard') }}">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
