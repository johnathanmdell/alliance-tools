<?php
namespace AllianceTools\Alliance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alliance extends Model
{
    /**
     * @var string
     */
    protected $table = 'alliance';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'short_name'
    ];

    /**
     * @return HasMany
     */
    public function corporations()
    {
        return $this->hasMany('AllianceTools\Corporation\Corporation', 'alliance_id');
    }
}
