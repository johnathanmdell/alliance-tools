<?php
namespace AllianceTools\User;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @var array
     */
    protected $fillable = [
        'email_address',
        'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return HasMany
     */
    public function characters()
    {
        return $this->hasMany('AllianceTools\Character\Character', 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany('AllianceTools\Service\Service')
            ->withPivot('auth_key', 'related_key')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('AllianceTools\Role\Role')->withTimestamps();
    }
}
