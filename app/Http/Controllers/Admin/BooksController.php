<?php

namespace App\Http\Controllers\Admin;

use Input;
use Auth;
use App\Item;
use App\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        $books = Book::all();

        return view('admin.books.index', compact('books'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        return view('admin.books.show', compact('book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request, Book $book)
    {

        $book->create($request->all());
        $book->uploadImage($request->file('img'));
         

        return redirect()->route('admin.books.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.books.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        return view('admin.books.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $id)
    {
        $book->update($request->all());
        $book->uploadImage($request->file('img'));

        return redirect()->route('admin.books.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $getIsSoftDeleted = Input::get('isSoftDalated');
        if(shouldShowIsSoftDeleted()){//0
           $book->remove(); 
            }else{
            $book->restore();
            }
        return redirect('\admin/books');//->route('admin.books.index'));
    }

    public function toggleSetPublished($id)
    {
        $book = Book::find($id);
        $book->toggleStatusDraft();

        return redirect()->back();
    }
//or select
    public function toggleBookFormat()($id)
    {
        $book = Book::find($id);
        $book->toggleStatusBookFormat();

        return redirect()->back();
    }

    public function toggleDiscontGlB($id)
    {
        $book = Book::find($id);
        $book->toggleStatusVisibleGl();

        return redirect()->back();
    }  
    //admin on/off global discont 
    public function toggleVisibleGlBAll()
    {
        $user_id = \Auth::user()->id;
        $books = Book::where('user_id', $user_id)->get();
        foreach($books as $book)
            {
                $book->toggleStatusVisibleGl();

        } 
       return redirect()->back();
        
    }

    public function toggleDiscontIdB($id)
    {
        $book = Book::find($id);
        $book->toggleStatusVisibleId();

        return redirect()->back();
    }  
    //admin on/off global discont 
    public function toggleVisibleIdBAll()
    {
        $user_id = \Auth::user()->id;
        $books = Book::where('user_id', $user_id)->get();
        foreach($books as $book)
            {
                $book->toggleStatusVisibleId();

        } 
       return redirect()->back();
        
    }
}
