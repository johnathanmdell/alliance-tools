<?php
namespace AllianceTools\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    /**
     * @var string
     */
    protected $table = 'service';

    /**
     * @var array
     */
    protected $fillable = [
        'auth_token',
        'related_key',
    ];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('AllianceTools\User\User')
            ->withPivot('auth_key', 'related_key')
            ->withTimestamps();
    }
}
