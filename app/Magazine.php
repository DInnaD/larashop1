<?php

namespace App;

use \Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Magazine extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'item_id', 'name', 'author_name', 'lenght', 'publisher', 'year', 'number', 'number_per_year', 'dimensions', 'price', 'sub_price', 'status_sub_price', 'old_price', 'img','code', 'discont_global', 'status_discont_global', 'discont_id', 'status_discont_id', 'new_price', 'created_by'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'newPrice' => 'float',
        'isPausedPublished' => 'float',
        //'date'
    ];
    protected $status_draft = 'status_draft';//likes status
    protected $attributes = ['isPausedPublished', 'newPrice',
       // 'status_draft' => 10
    ];
    protected $touches = ['purchase','user',
       // 'status_draft' => 10
    ];

    protected $appends = ['isPausedPublished']; instead of myToggle?
    protected $appends = ['newPrice'];

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;//published
    
    public function author()//isAdmin
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusDraft()//??????????//
    {
        return $this->purchases()->where('status_draft', 1)->get();
    }

    public function purchases()//+boot
    {
        return $this->morphMany('App\Purchase', 'purchaseable');   
    }

    // public function getIsPausedSubPriceAttribute()
    // {
    // 	$isPausedSubPrice = false;//1
    // 	if (!$this->status_sub_price){
    // 		$isPausedSubPrice  = true;//0
    // 	}
    // }

    public function remove()
    {
    	//$this->toggleStatusSoftDeletesUnPublished();
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if($this->img != null)//rewrite
        {
            Storage::delete('uploads/' . $this->img);
        }
    }

    public function uploadImage($img)
    {
        if($img == null) { return; }

        $this->removeImage();
        $filename = str_random(10) . '.' . $img->extension();
        $img->storeAs('uploads', $filename);
        $this->img = $filename;
        $this->save();
    }

    public function getImage()
    {
        if($this->img == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->img;

    }

    public function setDraft()
    {
    	$this->status_draft = Magazin::IS_DRAFT;//status_draft db absent
    	$this->save();
    }

    public function setPublic()
    {
    	$this->status_draft = Magazin::IS_PUBLIC;
    	$this->save();
    }

    public function toggleStatusDraft()
    {
    	if($this->status_draft == 0)
    	{
    		return $this->setPublic();
    	}

    	return $this->setDraft();
    }

    public function getIsPausedPublishedAttribute()//status_sub_price magazine->is_paused_published
    {
    	$isPausedPublished = false;//1 IS_PUBLIC
    	if ($this->status_draft == 1){//==0??? !$this
    		$isPausedPublished  = true;//0 IS_DRAFT
    	}
    }
    //Price with discont
    public function getNewPriceAttributes()
    {
        return $this->shouldShowNewPrice(); 
    }

    public function shouldShowNewPrice()
    {
    	if(shouldShowNewPriceSubcription()){    		
    		return $this->getNewPriceSubscription();
    	}
    	return $this->shouldShowNewPriceBasic();	
	  	
    }
    //Trait shouldShowNewPriceBasic
    public function shouldShowNewPriceBasic()
    {
    	if(shouldShowNewPriceDiscontId()){
	    		return $this->getNewPriceDiscontId();
	    	}elseif(shouldShowNewPriceDiscontGlobal()){
	    		return $this->getNewPriceDiscontGlobal();
	    	}
	    	return $this->getNewPrice();
    }

    public function shouldShowNewPriceSubcription()
    {
    	return $this->purchaseable()->isPausedSubPrice == true && $this->status_sub_price == 1;
    }

    public function getNewPriceSubscription()
    {  
    	
    	return $newPrice = $this->price - ($this->price * $this->sub_price / 100);	
    }

    public function shouldShowNewPriceDiscontId()
    {
    	return (($this->author->isPausedDiscontId == false && $this->status_discont_id == 1) && $this->discont_global < $this->discont_id) or ($this->author->isPausedDiscontGlobal == true or $this->status_discont_global == 0);
    }

    public function getNewPriceDiscontId()
    {
    	$newPrice = $this->price - ($this->price * $this->discont_id / 100);
    	return $newPrice;	
    }

    public function shouldShowNewPriceDiscontGlobal()
    {
    	return (($this->author->isPausedDiscontGlobal == false && $this->status_discont_global == 1) && $this->discont_global >= $this->discont_id) or ($this->author->isPausedDiscontId == true or $this->status_discont_id == 0);
    }

    public function getNewPriceDiscontGlobal()
    {
    	$newPrice = $this->price - ($this->price * $this->discont_global / 100);
    	return $newPrice;	
    }

    public function getNewPrice()
    {
        return $newPrice = $this->price; 
    }//end

    public function makeVisibleDiscontGlobal()
    {
        $this->status_discont_global = 1;
        $this->save();
    }

    public function makeUnVisibleDiscontGlobal()
    {
        $this->status_discont_global = 0;
        $this->save();
    }

    public function toggleStatusVisibleGl()
    {
        if($this->status_discont_global == 0)//!= null default i nullable is the same?
        {
            return $this->makeVisibleDiscontGlobal();
        }

        return $this->makeUnVisibleDiscontGlobal();
    }

    public function makeVisibleDiscontId()
    {
        $this->status_discont_id = 1;
        $this->save();
    }

    public function makeUnVisibleDiscontId()
    {
        $this->status_discont_id = 0;
        $this->save();
    }

    public function toggleStatusVisibleId()
    {
        if($this->status_discont_id == 0)//!= null default i nullable is the same?
        {
            return $this->makeVisibleDiscontId();
        }

        return $this->makeUnVisibleDiscontId();
    }

    public function makeSubscription()
    {
        $this->status_sub_price = 1;
        $this->save();
    }

    public function makeUnSubscription()
    {
        $this->status_sub_price = 0;
        $this->save();
    }

    public function toggleStatusSubPrice()
    {
        if($this->status_sub_price == 0)//!= null default i nullable is the same?
        {
            return $this->makeSubscription();
        }

        return $this->makeUnSubscription();
    }

    public function getIsPausedSubscriptionAttribute()//status_sub_price magazine->status_draft
    {
        $isPausedSubscription = false;//1 IS_PUBLIC
        if ($this->status_sub_price == 1){
            $isPausedSubscription  = true;//0 IS_DRAFT
        }
    }

    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');

        return $date;
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }
}
