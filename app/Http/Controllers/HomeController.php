<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{


    // public function restore()
    // {
    //     $user = User::withTrashed->findOrFail($id);
    //     $user->restore();
    //     return view('restore.restoreUser');   
    // }

    if(Auth::check())
    	{
    		$user_id = \Auth::user()->id;
    		$books = Book::where('user_id', $user_id)->get();
    		$magazines = Magazine::where('user_id', $user_id)->get();
	        $users = User::all();
	        $purchases = Purchase::all();
	        $orders = Order::all();
    	}
    		
		$users = User::all();
        $books = Book::paginate(10);
        $magazines = Magazine::paginate(10);
        $purchases = Purchase::all();
	    $orders = Order::all();
		        
    		

        return view('homes.index')->with('books', $books)->with('magazins', $magazins)->with('purchases', $purchases)->with('users', $users)->with('orders', $order);
}
