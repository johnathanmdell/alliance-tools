<?php
namespace AllianceTools\Character;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    /**
     * @var string
     */
    protected $table = 'character';

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
        'primary',
        'access_token',
        'refresh_token',
        'expires_on'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('AllianceTools\User\User');
    }

    /**
     * @return BelongsTo
     */
    public function corporation()
    {
        return $this->belongsTo('AllianceTools\Corporation\Corporation');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePrimary($query)
    {
        return $query->where('primary', true);
    }
}
