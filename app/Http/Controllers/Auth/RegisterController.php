<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\OrderShipped;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

   /**
    * Доставка данного заказа.
    *
    * @param  Request  $request
    * @param  int  $orderId
    * @return Response
    */
    public function ship(Request $request, $id)//subId - ? for custom Mail/OrdShip
    {
        $subs = Subscription::findOrFail($id);

        // Доставка заказа...

        Mail::to($request->user())->send(new OrderShipped($sub));
        }
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' =>  'required|email|unique:subscriptions'
        ]);
        
        $subs = Subscription::add($request->get('email'));
        $subs->generateToken();
        
        \Mail::to($subs)->send(new SubscribeEmail($subs));

        return redirect()->back()->with('status','Проверьте вашу почту!');
    }

    public function verify($token)
    {
        $subs = Subscription::where('token', $token)->firstOrFail();
        $subs->token = null;
        $subs->save();
        return redirect('/')->with('status', 'Ваша почта подтверждена!СПАСИБО!');
    }    

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
