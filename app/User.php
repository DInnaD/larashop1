<?php

namespace App;

use Hash;
use \Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

IS_ACTIVE = 1;
IS_BANNED = 0;
IS_ADMIN = 1;
IS_USER = 0;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'isPausedDiscontId' => 'boolean',
        'isPausedDiscontGlobal' => 'boolean',//for admin UI butt + pausedAll()
        'isPausedSubPrice' => 'boolean',
    ];

//Add Extra Attribute
    protected $attributes = ['isPausedDiscontId', 'isPausedDiscontGlobal', 'isPausedSubPrice,'
    ];
    //Make it Avaliable in the json response
    protected $appends = ['isPausedDiscontId'];
    protected $appends = ['isPausedDiscontGlobal'];
    protected $appends = ['isPausedSubPrice'];

    protected $touches = ['book', 'magazine', 'purchase', 'order',
    ];
  //mailconfirmpromo
    // public function generateToken()
    // {
    //     $this->token = str_random(100);
    //     $this->save();
    // }

    public function edit($fields)
    {
        $this->fill($fields); //name,email
        $this->password = Hash::make($fields['password']);
        $this->save();
    }

    public function shouldShowIsEmpty($varIsEmpty)//?????
    {
        return $this->is_empty($varIsEmpty);
    }

    public function generatePassword($password)
    {
        if($password != null)//is_empty($password)????
        {
            $this->password = Hash::make($password);
            $this->save();
        }
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }
  
//add migration image
    public function uploadAvatar($image)
    {
        if($image == null) { return; }

        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if($this->avatar != null)
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getImage()
    {
        if($this->avatar == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin()
    {
        $this->is_admin = User::IS_ADMIN;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_admin = User::IS_USER;
        $this->save();
    }

    public function toggleStatusAdmin()
    {
        if($this->is_admin == 0)
        {
            return $this->makeAdmin();
        }

        return $this->makeNormal();
    }

    public function ban()
    {
        $this->status_ban = User::IS_BANNED;
        $this->save();
    }

    public function unban()
    {
        $this->status_ban = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleStatusBan()
    {
        if($this->status_ban == 0)
        {
            return $this->unban();
        }

        return $this->ban();
    }

    public function makeVisibleDiscontId()
    {
        $this->status_discont_id = 0;
        $this->save();
    }

    public function makeUnVisibleDiscontId()
    {
        $this->status_discont_id = 1;
        $this->save();
    }

    public function toggleStatusVisibleDiscontId()
    {
        if($this->status_discont_id == 1)
        {
            return $this->makeVisibleDiscontId();
        }

        return $this->makeUnVisibleDiscontId();
    }

    public function makeVisibleGlobal()
    {
        $this->status_discont_global = 0;
        $this->save();
    }

    public function makeUnVisibleGlobal()
    {
        $this->status_discont_global = 1;
        $this->save();
    }

    public function toggleStatusVisibleGlobal()
    {
        if($this->status_discont_global == 1)
        {
            return $this->makeVisibleGlobal();
        }

        return $this->makeUnVisibleGlobal();
    }

    public function getIsPausedDiscontIdAttribute()//status_sub_price magazine->status_draft
    {
        $isPausedDiscontId = false;//1
        if ($this->status_discont_id == 1){
            $isPausedDiscontId  = true;//0
        }
    }

    public function getIsPausedDiscontGlobalAttribute()//status_sub_price magazine->status_draft
    {
        $isPausedDiscontGlobal = false;//1
        if ($this->status_discont_global == 1){
            $isPausedDiscontGlobal  = true;//0
        }
    }

        public function sub()
    {
        
        $this->status_sub_price = 1;
        $this->save();
        
    }

    public function disSub()
    {
        $this->status_sub_price = 0;
        $this->save();
    }

    public function toggleStatusSubPrice()
    {
        if($this->status_sub_price == 0)
        {
            return $this->sub();
        }

        return $this->disSub();
    }

        public function getIsPausedSubPriceAttribute()//status_sub_price magazine->status_draft
    {
        $isPausedSubPrice = false;//1
        if ($this->status_sub_price == 1){
            $isPausedSubPrice  = true;//0
        }
    }
}
