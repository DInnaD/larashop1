<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Faundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->remove();
        return redirect()->back();
    }

    public function toggleVisibleDiscontId($id)
    {
        $user = User::find($id);
        $user->toggleStatusVisibleDiscontId();

        return redirect()->back();
    }

    public function toggleAdmin($id)
    {
        $user = User::find($id);
        $user->toggleStatusAdmin();

        return redirect()->back();
    }

    public function toggleBan($id)
    {
        $user = User::find($id);
        $user->toggleStatusBan();

        return redirect()->back();
    }

    public function toggleSubPrice($id)
    {
        $user = User::find($id);
        $user = toggleStatusSubPrice();
    }
}
