<?php

namespace App\Http\Controllers;

use App\Enums\TableSearchEnum;
use App\Http\Requests\User\AddUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\DetailUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController
{
    public function view(Request $request)
    {
        $roles = Role::query()->get();
        return view('user.add', ['roles' => $roles]);
    }

    public function add(AddUserRequest $request)
    {
        $params = $request->validated();
        $user = User::query()->create([...$params, 'password' => bcrypt('123456')]);
        $user->syncPermissions(data_get($params, 'permissions'));
        $user->syncRoles(data_get($params, 'role'));

        return redirect()->route('user.index');
    }

    public function detail(User $user, Request $request)
    {
        $user = User::with('roles:id,name')->find($user->id);
        $roles = Role::query()->get();

        return view('user.detail', ['user' => (new DetailUserResource($user))->resolve(), 'roles' => $roles]);
    }

    public function search(Request $request)
    {
        $params = $request->all();
        $params['per_page'] = data_get($params, 'per_page', TableSearchEnum::PER_PAGE);
        $params['page'] = data_get($params, 'page', TableSearchEnum::PAGE);
        $params['column'] = data_get($params, 'column', TableSearchEnum::COLUMN);
        $params['sort'] = data_get($params, 'sort', TableSearchEnum::SORT);
        $users = User::query()
            ->when(data_get($params, 'keyword'), function ($q, $keyword) {
                $q->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                    $q->orWhere('email', 'like', "%$keyword%");
                    $q->orWhere('phone', 'like', "%$keyword%");
                });
            })
            ->when(data_get($params, 'role'), function ($q, $role) {
                $q->whereRelation('roles', 'id', '=', $role);
            })
            ->orderBy($params['column'], $params['sort'])
            ->paginate(perPage: $params['per_page'], page: $params['page']);

        $roles = Role::query()->get();

        return view('user.index', ['users' => $users, 'roles' => $roles]);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $params = $request->validated();
        $user->syncPermissions(data_get($params, 'permissions'));
        $user->syncRoles(data_get($params, 'role'));
        $status = $user->update($params);
        return back()->withInput();
    }
}
