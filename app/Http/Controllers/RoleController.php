<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:role-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.index')
            ->with('roles', Role::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create($request->all());
        \Notification::add('success', 'Role '.$role->display_name.' added');
        return \Redirect::route('role.show', $role);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('role.show')
            ->with('role', $role)
            ->with('users', User::with('driver')->whereNotIn('id', $role->users->pluck('id'))->get())
            ->with('permissions', Permission::whereNotIn('id', $role->permissions->pluck('id')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('role.edit')
            ->with('role', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role->fill($request->all());
        $role->save();
        \Notification::add('success', 'Role '.$role->name.' updated');
        return \Redirect::route('role.show', $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role->users->count()) {
            \Notification::add('error', 'Role "'.$role->name.'" cannot be deleted - there are users assigned to it');
            return \Redirect::route('role.show', $role);
        } else {
            $role->delete();
            \Notification::add('success', 'Role "'.$role->name.'" deleted');
            return \Redirect::route('role.index');
        }
    }

    public function addUser(Request $request, Role $role)
    {
        $user = User::findOrFail($request->get('user'));
        $user->assignRole($role);
        \Notification::add('success', 'User "'.$user->email.'" added');
        return \Redirect::route('role.show', $role);
    }

    public function removeUser(Role $role, User $user)
    {
        $user->removeRole($role);
        \Notification::add('success', 'User "'.$user->email.'" removed');
        return \Redirect::route('role.show', $role);
    }

    public function addPermission(Request $request, Role $role)
    {
        $permission = Permission::findOrFail($request->get('permission'));
        $role->givePermissionTo($permission);
        \Notification::add('success', 'Permission "'.$permission->name.'" added');
        return \Redirect::route('role.show', $role);
    }

    public function removePermission(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission);
        \Notification::add('success', 'Permission "'.$permission->name.'" removed');
        return \Redirect::route('role.show', $role);
    }    
}
