<?php

namespace App\Domains\Chat\Models;

use App\Domains\Chat\Models\Traits\Scope\MessageKeywordsScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MessageKeywords.
 */
class MessageKeywords extends Model
{
    use SoftDeletes,
        MessageKeywordsScope;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'message_keywords';

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
        'keywords',
        'response',
    ];
}
