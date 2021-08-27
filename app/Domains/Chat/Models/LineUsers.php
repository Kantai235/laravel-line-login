<?php

namespace App\Domains\Chat\Models;

use App\Domains\Chat\Models\Traits\Scope\LineUsersScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LineEvents.
 */
class LineUsers extends Model
{
    use SoftDeletes,
        LineUsersScope;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'line_users';

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
        'user_id',
        'display_name',
        'language',
        'picture_url',
        'status_message',
    ];
}
