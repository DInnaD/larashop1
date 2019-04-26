<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Faundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
        //$this->middleware(['auth' => 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $users = User::all();
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request User $user)
    {
        $user->create($request->all());
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.users.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user->update($request->all());

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user->remove();

        return redirect()->route('admin.users.index');
    }

    public function toggleAdmin($id)
    {
        $user = User::find($id);
        $user->toggleAdmin();


        return redirect()->back();
    }

    public function toggleBan($id)
    {
        $user = User::find($id);
        $user->toggleBan();


        return redirect()->back();
    }

    public function toggleVisibleDiscontGlobal($id)
    {
        $user = User::find($id);
        $user->toggleStatusVisibleGlobal();

        return redirect()->back();
    }

    // //admin on/off global discont forTEST
    public function toggleUnVisibleDiscontIdAll()
    {
        $user_id = \Auth::user()->id;
        $users = User::where('user_id', $user_id)->get();
        foreach($users as $user)
            {
                $user->makeUnVisibleDiscontId();

        } 
       return redirect()->back();
        
    }

     public function toggleVisibleDiscontGlobalAll()
    {
        $user_id = \Auth::user()->id;
        $users = User::where('user_id', $user_id)->get();
        foreach($users as $user)
            {
                $user->toggleStatusVisibleGlobal();

        } 
       return redirect()->back();
        
    }
}
