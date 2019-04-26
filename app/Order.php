<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = ['user_id', 'count', 'total', 'date', 'email', 'token', 'created_by'];// 'email', 'token' to mail

	/**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
    protected $attributes = [
       // 'status_draft' => 10
    ];
    protected $touches = ['purchase', 'user',
       // 'status_draft' => 10
    ];
    protected $appends = [];
    protected $dates = [
    	'date',
    ];
    protected $dateFormat = 'Y-m-d';


	public function author()//isAdmin
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

     public static function add($fields)
    {
        $order = new static;
        $order->fill($fields);
        $order->user_id = \Auth::user()->id;
        $order->save();

        return $order;

    }

    

	public function setCountAttributes()
    {
    	foreach($order->purchases as $purchase){
    	$count += $order->purchase->qty; 
    	}
    	$this->attributes['count'] = $count;	
    }


    public function getCountAttributes()
    {
    	foreach($order->purchases as $purchase){
    	#protected $appends = ['count'];
    	$count += $order->purchase->qty; 
    	return $count;	
    }

    public function getCount()
    {
        return $count = sum($this->purchase->qty); 
    }

    public function setTotalAttributes()
    {
    	$total = sum($order->purchase->qty * $order->purchase->purchaseable()->newPrice);//???
    	$this->attributes['total'] = $total;	
    }

    public function getTotalAttributes()
    {
    	$total = sum($order->purchase->qty * $order->purchase->purchaseable()->newPrice);//???
    	return $total;	
    }

    public function getTotal()
    {
        return $total = sum($this->purchase->qty * $this->purchase->purchaseable()->newPrice); 
    }

     public function setCodeAttributes()
    {
    	$code = $order->purchase->purchaseable()->code;
    	$this->attributes['code'] = $code;	
    }

    public function getCodeAttributes()
    {
    	$code = $this->purchase->purchaseable()->code;
    	return $code;	
    }

    public function getCode()
    {
        return $code = $this->purchase->purchaseable()->code; 
    }

    public function setDateAttribute()
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');

        return $date;
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }    
}
