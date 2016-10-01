<?php
namespace AllianceTools\Fleet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @Bind("fleet")
 */
class EloquentFleet extends Model implements FleetInterface
{
    /**
     * @var string
     */
    protected $table = 'fleet';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('AllianceTools\User\User');
    }

    /**
     * @return HasMany
     */
    public function members()
    {
        return $this->hasMany('AllianceTools\Member\EloquentMember', 'fleet_id');
    }
}