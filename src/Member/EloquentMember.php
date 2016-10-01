<?php
namespace AllianceTools\Member;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @Bind("member")
 */
class EloquentMember extends Model implements MemberInterface
{
    /**
     * @var string
     */
    protected $table = 'member';

    /**
     * @var array
     */
    protected $fillable = [
        'location',
        'ship',
        'joined_at'
    ];

    /**
     * @return BelongsTo
     */
    public function fleet()
    {
        return $this->belongsTo('AllianceTools\Fleet\EloquentFleet', 'fleet_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function character()
    {
        return $this->belongsTo('AllianceTools\Character\Character', 'character_id', 'id');
    }

    /**
     * @return string
     */
    public function getHumanJoinedAtAttribute()
    {
        return (new Carbon($this->joined_at))->diffForHumans();
    }
}