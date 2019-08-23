<?php

namespace Modules\Analytics\Entities;

use App\Website;
use Greabock\Tentacles\EloquentTentacle;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Google extends Model
{
    use UsesSystemConnection, EloquentTentacle;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'analytics__googles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'view_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'website_id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'null'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public $casts = [
        'id' => 'integer',
        'website_id' => 'integer',
        'view_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Return the website.
     *
     * @return BelongsTo
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
