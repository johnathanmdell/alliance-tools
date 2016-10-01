<?php
namespace AllianceTools\Corporation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Corporation extends Model
{
    /**
     * @var string
     */
    protected $table = 'corporation';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name'
    ];

    /**
     * @return BelongsTo
     */
    public function alliance()
    {
        return $this->belongsTo('AllianceTools\Alliance\Alliance');
    }
}
