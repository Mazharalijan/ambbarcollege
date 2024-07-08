<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'text_password',
        'status',
        'language_id',
        'level',
        'role_id'
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
    ];

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * get profile image url
     * @return string
     */
    public function getProfileImageUrlAttribute() {
        if( isset($this->attributes['profile_image']) && !empty($this->attributes['profile_image']) ){
            $filepath =  base_path('public/uploads/profile/' . $this->attributes["profile_image"]);
            if(file_exists($filepath)){
                return asset('uploads/profile/'.$this->attributes["profile_image"]);
            }else{
                return asset('uploads/default-user.png');
            }
        }
        return asset('uploads/default-user.png');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function hasRole($role)
    {
        return (!empty($this->role) && $role == $this->role->slug) ? true : false;
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'user_books');
    }

    public function chapters()
    {
        return $this->belongsToMany(Book::class, 'user_chapters');
    }

    public function language(){
        return $this->belongsTo(Language::class,'language_id');
    }
}
