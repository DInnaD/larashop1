<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Item;
use App\Magazine;
use App\Http\Requests\MagazineRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class MagazinesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Magazine::class, 'magazine');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Magazine $magazine)
    {
        $magazines = Magazine::all();

        return view('admin.magazines.index', compact('magazines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.magazines.create', compact('magazine'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MagazineRequest $request, Magazine $magazine)
    {
        $magazine->create($request->all());
        $magazine->uploadImage($request->file('img'));

        return redirect()->route('admin.magaines.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.magazines.show',compact('magazine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Item::item();//id
        return view('admin.magazines.edit', compact('magazine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MagazineRequest $request, $id)
    {        
        $magaine->update($request->all());
        $magazine->uploadImage($request->file('img'));

        return redirect()->route('admin.magazines.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $magazine->remove();

        return redirect()->route('admin.magazines.index');
    }

    public function toggleSetPublished($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusDraft();

        return redirect()->back();
    }

    public function toggleDiscontGlM($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusVisibleGl();//dd($user->status_discont_id);

        return redirect()->back();
    }    
 
    public function toggleVisibleGlMAll()
    {
        $user_id = \Auth::user()->id;
        $magazines = Magazin::where('user_id', $user_id)->get();
        foreach($magazines as $magazine)
            {
                $magazine->toggleStatusVisibleGl();

        } 
       return redirect()->back();
        
    }  

    public function toggleDiscontIdM($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusVisibleId();//dd($user->status_discont_id);

        return redirect()->back();
    }    
 
    public function toggleVisibleIdMAll()
    {
        $user_id = \Auth::user()->id;
        $magazines = Magazin::where('user_id', $user_id)->get();
        foreach($magazines as $magazine)
            {
                $magazine->toggleStatusVisibleId();

        } 
       return redirect()->back();
        
    }

     public function toggleSubPrice($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusSubPrice();

        return redirect()->back();
    }  
}
