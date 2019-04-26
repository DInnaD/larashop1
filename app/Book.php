<?php

namespace App;

use \Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes, Selectable, Owned//, Searchable; 

    protected $fillable = ['user_id', 'item_id', 'name', 'author_name', 'lenght', 'publisher', 'year', 'format', 'genre', 'dimensions', 'price', 'old_price', 'img','code', 'discont_global', 'status_discont_global', 'discont_id', 'status_discont_id', 'new_price', 'created_by'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'newPrice' => 'float',
        'isPausedPublished' => 'float',
        'isSoftDeleted' => 'boolean',
        //'date'
    ];
    protected $status_draft = 'status_draft';//likes status
    protected $attributes = ['newPrice', 'isSoftDeleted',
       // 'status_draft' => 10
    ];
    protected $touches = ['purchase','user',
       // 'status_draft' => 10
    ];
    //protected $appends = ['isPausedPublished'];//public
    protected $appends = ['isPausedPublished', 'newPrice', 'isSoftDeleted',];


    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;//published
    const IS_HARDCOVER = 0;//select 
    const IS_KINDLE = 1;

    public function author()//isAdmin
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function purchases()//+boot
    {
        return $this->morphMany('App\Purchase', 'purchaseable');   
    }

    public function getStatusDraft()//??????????//
    {
        return $this->purchases()->where('status_draft', 1)->get();
    }  

    public function getValueIsHardCover()
    {
        //return 
        return $this->is_hard_cover = Input::make($fields['is_hard_cover']);
        // $this->fill($fields); //name,email
        // $this->is_hard_cover = Input::make($fields['is_hard_cover']);
        // $this->save();
    }
//checkBox del/restore

    public function shouldShowIsSoftDeleted()
    {
        return $getIsSoftDeleted = $this->where('isSottDeleted', 0)->get();
    } 
    
    public function getIsSoftDeletedAttribute()
    {
        return $this->isSoftDeleted = 0;
    } 

    public function remove()
    {
        //$this->toggleStatusSoftDeletesUnPublished();
        $this->removeImage();
        $this->delete();
    }

    public function restore()
    {
        return $this->restore();   
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

    public function getIsPausedPublishedAttribute()//status_sub_price magazine->is_paused_publeshed
    {
        $isPausedPublished = false;//1 IS_PUBLIC
        if ($this->status_draft == 1){
            $isPausedPublished  = true;//0 IS_DRAFT
        }
    }    
    //Price with discont
    public function getNewPriceAttributes()
    {
        return $newPrice = $this->shouldShowNewPrice(); 
    }

    public function shouldShowNewPrice()
    {
        return $this->shouldShowNewPriceBasic();
    }
//to trait
    public function shouldShowNewPriceBasic()
    {
        if(shouldShowNewPriceDiscontId()){
            return $this->getNewPriceDiscontId();
        }elseif(shouldShowNewPriceDiscontGlobal()){
            return $this->getNewPriceDiscontGlobal();
        }
        return $this->getNewPrice();
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
    }  
//selectable AudioBook Audio CD
    public function setHardCover()//chernovik
    {
        $this->is_hard_cover = Book::IS_HARDCOVER;
        $this->save();
    }

    public function setKindle()
    {
        $this->is_hard_cover = Book::IS_KINDLE;
        $this->save();
    }

    public function toggleStatusBookFormat()
    {
        if($this->is_hard_cover == 0)
        {
            return $this->setNoHard();
        }

        return $this->setHard();
    }

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

// php namespace App\Services;

// use App\CartItem;
// use App\Product;
// use App\Exceptions\CartException as Exception;
// use Illuminate\Contracts\Auth\Guard;
// use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Http\Request;
// use Illuminate\Session\Store as Session;

// class Cart {

// 	/**
// 	 * @var Session
// 	 */
// 	private $session;

// 	/**
// 	 * @var Guard
// 	 */
// 	private $auth;

// 	/**
// 	 * @var CartItem
// 	 */
// 	private $model;

// 	/**
// 	 * @var string
// 	 */
// 	private $code;


// 	function __construct(Request $request, Guard $auth, CartItem $model)
// 	{
// 		$this->session = $request->session();
// 		$this->auth = $auth;
// 		$this->model = $model;

// 		$this->code = $this->session->get('cart.code');
// 		if (is_null($this->code)) {
// 			$this->createNewCart();
// 		}
// 	}


// 	public function code()
// 	{
// 		return $this->code;
// 	}


// 	public function clear()
// 	{
// 		$this->session->remove('cart');
// 		$this->model->whereCode($this->code)->delete();
// 		$this->createNewCart();
// 	}


// 	/**
// 	 * Сумма стоимостей всех позиций в корзине. Итоговая сумма на чеке.
// 	 *
// 	 * @return float
// 	 */
// 	public function total()
// 	{
// 		$total = $this->session->get('cart.total');

// 		if (is_null($total)) {
// 			/** @var Collection $items */
// 			$items = $this->model->with([ 'product' => function ($query) { $query->select([ 'id', 'price' ]); } ])
// 								 ->whereCode($this->code)
// 								 ->get([ 'product_id', 'quantity' ]);

// 			$total = $items->sum(function ($item) { return $item->quantity * $item->product->price; });

// 			$this->session->set('cart.total', $total);
// 		}

// 		return $total;
// 	}


// 	/**
// 	 * Суммарное количество единиц товара в заказе.
// 	 *
// 	 * @return int
// 	 */
// 	public function count()
// 	{
// 		$count = $this->session->get('cart.count');

// 		if (is_null($count)) {

// 			$count = $this->model->whereCode($this->code)->sum('quantity');

// 			$this->session->set('cart.count', $count);
// 		}

// 		return $count;
// 	}


// 	/**
// 	 * Позиции заказа.
// 	 *
// 	 * @param array $columns
// 	 * @param bool  $lock
// 	 *
// 	 * @return Collection
// 	 */
// 	public function items($columns = [ '*' ], $lock = false)
// 	{
// 		if ($lock) {
// 			return $this->model->with(
// 				[
// 					'product' => function ($query) {
// 						$query->lockForUpdate();
// 					}
// 				]
// 			)->whereCode($this->code)->latest('created_at')->lockForUpdate()->get($columns);
// 		}

// 		return $this->model->with('product')->whereCode($this->code)->latest('created_at')->get($columns);
// 	}


// 	/**
// 	 * Изменения количества позиции в заказе. Проверяет наличие на складе. Не проверяет баланс пользователя.
// 	 *
// 	 * @param string|array $condition
// 	 * @param int          $quantity
// 	 *
// 	 * @throws Exception
// 	 */
// 	public function setQuantity($condition, $quantity)
// 	{
// 		if (!is_array($condition)) {
// 			$condition = [ 'product_id' => $condition ];
// 		}

// 		// проверка наличия на складе

// 		$item = $this->model->with('product')->whereCode($this->code)->where($condition)->first();

// 		if ($quantity > $item->product->quantity) {
// 			throw new Exception(setting('message.not_enough_in_stock') ?: 'Количество на складе ограничено.');
// 		}

// 		$item->update(compact('quantity'));

// 		$this->clearCounters();
// 	}


// 	/**
// 	 * @param Product $product
// 	 * @param int     $quantity
// 	 *
// 	 * @return CartItem
// 	 * @throws Exception
// 	 */
// 	public function addItem($product, $quantity = 1)
// 	{
// 		if ($this->auth->guest()) {
// 			abort(401);
// 		}

// 		if (!$this->auth->user()->is_admin) {

// 			// перед добавлением товара в корзину надо проверить баланс пользователя
// 			// если баллов не хватает, контроллеру возвращается false для того
// 			// чтобы тот мог уведомить пользователя надлежащим способом

// 			$possible_total = $this->total() + $product->price;

// 			if ($possible_total > $this->auth->user()->balance) {

// 				throw new Exception(
// 					setting(
// 						'message.not_enough_points'
// 					) ?: 'На вашем балансе недостаточно баллов для выполнения этого действия.'
// 				);

// 			}
// 		}

// 		// если товар уже есть в корзине - только увеличим его количество

// 		/** @var CartItem $item */
// 		$item = $this->model->whereCode($this->code)->whereProductId($product->getKey())->first();

// 		if ($item) {

// 			// проверка наличия на складе

// 			if ($item->quantity + $quantity > $product->quantity) {
// 				throw new Exception(setting('message.not_enough_in_stock') ?: 'Количество на складе ограничено.');
// 			}

// 			$item->increment('quantity', $quantity);

// 			$this->clearCounters();

// 			return $item;
// 		}

// 		// проверка наличия на складе

// 		if ($quantity > $product->quantity) {
// 			throw new Exception(setting('message.not_enough_in_stock') ?: 'Количество на складе ограничено.');
// 		}

// 		// добавление нового товара в корзину

// 		/** @var CartItem $newItem */
// 		$newItem = $this->model->create(
// 			[
// 				'code' => $this->code,
// 				'user_id' => $this->auth->user()->getAuthIdentifier(),
// 				'product_id' => $product->getKey(),
// 				'quantity' => $quantity,
// 			]
// 		);

// 		$this->clearCounters();

// 		return $newItem;
// 	}


// 	public function deleteItem($condition)
// 	{
// 		if (!is_array($condition)) {
// 			$condition = [ 'product_id' => $condition ];
// 		}

// 		$this->model->whereCode($this->code)->where($condition)->delete();

// 		$this->clearCounters();
// 	}


// 	private function createNewCart()
// 	{
// 		$this->code = str_random();
// 		$this->session->set('cart.code', $this->code);
// 		$this->clearCounters();
// 	}


// 	private function clearCounters()
// 	{
// 		$this->session->remove('cart.count');
// 		$this->session->remove('cart.total');
// 	}
// }
// app/CartItem.php:

// <?php namespace App;

// use Illuminate\Database\Eloquent\Model;

// *
//  * App\CartItem
//  *
//  * @property integer $id 
//  * @property string $code 
//  * @property integer $user_id 
//  * @property integer $product_id 
//  * @property integer $quantity 
//  * @property \Carbon\Carbon $created_at 
//  * @property \Carbon\Carbon $updated_at 
//  * @property-read \App\User $user 
//  * @property-read \App\Product $product 
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereId($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereCode($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereUserId($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereProductId($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereQuantity($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereCreatedAt($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\CartItem whereUpdatedAt($value)
 
// class CartItem extends Model {

// 	/**
// 	 * The attributes that should be casted to native types.
// 	 *
// 	 * @var array
// 	 */
// 	protected $casts = [ 'quantity' => 'integer' ];

// 	/**
// 	 * The attributes that are mass assignable.
// 	 *
// 	 * @var array
// 	 */
// 	protected $fillable = [ 'code', 'user_id', 'product_id', 'quantity' ];


// 	public function user()
// 	{
// 		return $this->belongsTo('App\User');
// 	}


// 	public function product()
// 	{
// 		return $this->belongsTo('App\Product');
// 	}

// }
// 	/**
// 	 * The attributes that should be casted to native types.
// 	 *
// 	 * @var array
// 	 */
// 	protected $casts = [ 'quantity' => 'integer' ];

// 	/**
// 	 * The attributes that are mass assignable.
// 	 *
// 	 * @var array
// 	 */
// 	protected $fillable = [ 'code', 'user_id', 'product_id', 'quantity' ];


// 	public function user()
// 	{
// 		return $this->belongsTo('App\User');
// 	}


// 	public function product()
// 	{
// 		return $this->belongsTo('App\Product');
// 	}

// }
// app/Product.php:

// <?php namespace App;

// use App\Events\CatalogWasChanged;
// use Illuminate\Database\Eloquent\Model;

// /**
//  * App\Product
//  *
//  * @property integer $id
//  * @property string $name
//  * @property string $photo
//  * @property string $sticker
//  * @property string $description
//  * @property string $properties
//  * @property float $price
//  * @property float $old_price
//  * @property integer $quantity
//  * @property boolean $published
//  * @property boolean $archived
//  * @property integer $top_position
//  * @property integer $updated_by
//  * @property string $updated_ip
//  * @property \Carbon\Carbon $created_at
//  * @property \Carbon\Carbon $updated_at
//  * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductCategory[] $categories
//  * @property-read \App\User $editor
//  * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $likes
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereId($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereName($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product wherePhoto($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereSticker($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereDescription($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereProperties($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product wherePrice($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereOldPrice($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereQuantity($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product wherePublished($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereArchived($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereTopPosition($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereUpdatedBy($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereUpdatedIp($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereCreatedAt($value)
//  * @method static \Illuminate\Database\Query\Builder|\App\Product whereUpdatedAt($value)
//  * @method static \App\Product available()
//  */
// class Product extends Model {

// 	/**
// 	 * The attributes that should be casted to native types.
// 	 *
// 	 * @var array
// 	 */
// 	protected $casts = [
// 		'published' => 'boolean',
// 		'archived' => 'boolean',
// 		'properties' => 'object',
//         'sort_order' => 'integer',
// 	];

// 	/**
// 	 * The attributes that are mass assignable.
// 	 *
// 	 * @var array
// 	 */
// 	protected $fillable = [
// 		'name',
// 		'photo',
// 		'sticker',
// 		'description',
// 		'properties',
// 		'price',
// 		'old_price',
// 		'quantity',
// 		'published',
// 		'archived',
// 		'sort_order',
// 		'updated_by',
// 		'updated_ip'
// 	];

// 	/**
// 	 * The attributes that should be hidden for arrays.
// 	 *
// 	 * @var array
// 	 */
// 	protected $hidden = [ 'updated_ip' ];


// 	public static function boot()
// 	{
// 		parent::boot();
// 		static::created(function () { event(new CatalogWasChanged); });
// 		static::saved(function () { event(new CatalogWasChanged); });
// 	}


// 	public function categories()
// 	{
// 		return $this->belongsToMany('App\ProductCategory');
// 	}


// 	public function editor()
// 	{
// 		return $this->belongsTo('App\User', 'updated_by');
// 	}


// 	public function likes()
// 	{
// 		return $this->belongsToMany('App\User', 'product_user_likes');
// 	}


// 	public function scopeAvailable($query)
// 	{
// 		return $query->wherePublished(true)->whereArchived(false)->where('quantity', '>', 0);
// 	}
// }
