@php
    use App\Enums\PermissionEnum;
    use Carbon\Carbon;
@endphp

@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title text-primary fw-bold">Danh sách tài khoản</h5>
                <form action="{{ route('user.index') }}">
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Nhập để tìm kiếm</label>
                            <input type="text" class="form-control" name="keyword" value="{{request()->keyword}}"
                                   placeholder="VD: Họ tên, email, sdt,...">
                        </div>
                        <div class="col">
                            <label class="form-label">Vai trò</label>
                            <select class="form-select" name="role">
                                <option value="">Chọn vai trò</option>
                                @foreach($roles as $role)
                                    <!-- Assumes you have the roles list -->
                                    <option value="{{ $role['id'] }}"
                                            @if($role['id']==request()->role) selected @endif>{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="submit" class="btn btn-primary mx-2">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
                <div class="my-3">
                    @if (Auth::user()->hasPermissionTo(PermissionEnum::ADD_USER))
                        <a class="btn btn-primary" href="{{ route('user.add') }}">Thêm tài khoản</a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                        <tr>
                            @foreach(\App\Enums\TableHeaderEnum::USER as $item)
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
                            @if (Auth::user()->hasPermissionTo(PermissionEnum::UPDATE_USER))
                                <th>Hành động</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $index => $user)
                            <!-- Assumes you have the list of users -->
                            <tr>
                                <td class="text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <!-- Index in Blade -->
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td class="text-center">{{ $user->created_at->format('H:i d/m/Y') }}</td>
                                @if (Auth::user()->hasPermissionTo(PermissionEnum::UPDATE_USER))
                                    <td class="text-center">
                                        <a class="btn btn-primary"
                                           href="{{ route('user.detail', ['user' => $user->id]) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{$users}}
            </div>
        </div>
    </div>
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
</script>
