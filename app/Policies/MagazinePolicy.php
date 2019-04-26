<?php

namespace App\Policies;
use Auth;
use Magazine;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MagazinePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Magazine  $magazine
     * @return bool
     */
   
    public function update(User $user, Magazine $magazine)
    {
        return $user->id == $magazine->user_id;
    }
    }
}
