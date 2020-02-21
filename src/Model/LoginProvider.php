<?php

namespace Mrkatz\LoginProviders\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LoginProvider extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'provider_type',
        'verified',
        'nickname',
        'name',
        'email',
        'avatar',
        'meta',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified' => 'boolean',
        'meta'     => 'array',
    ];

    /**
     * Get the related user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
