<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Mail;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Order $order, Purchase $purchase)
    {
        $orders = Order::all();

        return view('order.index', compact('orders'));
    }

    //show purchase
}
