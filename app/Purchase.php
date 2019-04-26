<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $fillable['user-id', 'order_id', 'book_id', 'magazine_id', 'qty', 'status_bought', 'status_paid', 'status_discont_id','created_by', ];

	/**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'code' => 'integar',
    	'count' => 'integar',
    	'total' => 'float',
    	'isPausedSubPrice' => 'boolean',
    ];
    protected $attributes = ['isPausedSubPrice', 'code', 'total', 'count',
       // 'status_draft' => 10
    ];
    protected $touches = ['order', 'user', 'book', 'magazine',
       // 'status_draft' => 10
    ];

    protected $appends = ['isPausedSubPrice'];
    protected $appends = ['code'];    
	protected $appends = ['count'];
	protected $appends = ['total'];

	public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
    	return $this->belongsTo(Order::class, 'order_id');
    }

	public function purchaseable()
    {
        return $this->morphTo();   
    }

	public function setCountAttributes()
    {
    	foreach($purchases as $purchase){
    	$count += $purchase->qty; 
    	}
    	$this->attributes['count'] = $count;	
    }

    public function getCountAttributes()
    {
    	foreach($purchases as $purchase){
    	$count += $purchase->qty; 
    	return $count;	
    }

    public function getCount()
    {
        return $count = sum($this->qty); 
    }
 
     public function setTotalAttributes()
    {
    	$total = sum($purchase->qty * $purchase->purchaseable()->newPrice);
    	$this->attributes['total'] = $total;	
    }

    public function getTotalAttributes()
    {
    	$total = sum($purchase->qty * $purchase->purchaseable()->newPrice);
    	return $total;	
    }

    public function getTotal()
    {
        return $total = sum($this->qty * $this->purchaseable()->newPrice); 
    }

    public function setCodeAttributes()
    {
    	$code = $purchase->purchaseable()->code;
    	$this->attributes['code'] = $code;	
    }

    public function getCodeAttributes()//? to 1
    {
    	$code = $purchase->purchaseable()->code;
    	return $code;	
    }

    public function getCode()//?
    {
    	return $code = $this->purchaseable()->code;
    }

    public function Buy()
    {
    	
    	$this->status_bought = 1;
    	$this->save();
    	
    }

    public function disBuy()
    {
    	$this->status_bought = 0;
    	$this->save();
    }

    public function toggleStatusBuy()
    {
    	if($this->status_bought == 0)
    	{
    		return $this->Buy();
    	}

    	return $this->disBuy();
    }   

    public function getIsPausedSubPriceAttribute()
    {
    	$isPausedSubPrice = false;//1
    	if ($this->status_sub_price == 1){
    		$isPausedSubPrice  = true;//0
    	}
    } 
    //for admin controller
    public function pay()
    {
    	
    	$this->status_paid = 1;
    	$this->save();
    	
    }

    public function disPay()
    {
    	$this->status_paid = 0;
    	$this->save();
    }

    public function toggleStatus()
    {
    	if($this->status_paid == 0)
    	{
    		return $this->pay();
    	}

    	return $this->disPay();
    }

    
}
