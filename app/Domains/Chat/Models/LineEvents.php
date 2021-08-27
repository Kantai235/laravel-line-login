<?php

namespace App\Domains\Chat\Models;

use App\Domains\Chat\Models\Traits\Scope\LineEventsScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LineEvents.
 */
class LineEvents extends Model
{
    use SoftDeletes,
        LineEventsScope;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'line_events';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'destination',
        'type',
        'response',
    ];
}
