<?php
namespace AllianceTools\Role;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * @var string
     */
    protected $table = 'role';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('AllianceTools\User\User')->withTimestamps();
    }
}
