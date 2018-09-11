<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    //方法会在用户模型类完成初始化之后进行加载，因此我们对事件的监听需要放在该方法中
    /*
     * 如果我们需要在模型被创建之前进行一些设置，则可以通过监听 creating 方法来做到。
     * 该方法是由 Eloquent 模型触发的一个事件。事件是 Laravel 提供一种简单的监听器实现，
     * 我们可以对事件进行监听和订阅，从而在事件被触发时接收到响应并执行一些指定操作。
     * Eloquent 模型默认提供了多个事件，我们可以通过其提供的事件来监听到模型的创建，
     * 更新，删除，保存等操作。creating 用于监听模型被创建之前的事件，created 用于监听模型被创建之后的事件。
     * 接下来我们要生成的用户激活令牌需要在用户模型创建之前生成，因此需要监听的是 creating 方法。
     */
    public static function boot(){
        parent::boot();
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    public function gravatar($size='100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function statuses(){
        return $this->hasMany(Status::class);
    }

    public function feed(){
        return $this->statuses()
            ->orderBy('created_at','desc');
    }

}
