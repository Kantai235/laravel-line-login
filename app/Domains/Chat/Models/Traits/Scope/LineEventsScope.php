<?php

namespace App\Domains\Chat\Models\Traits\Scope;

/**
 * Class LineEventsScope.
 */
trait LineEventsScope
{
    /**
     * @param $query
     * @param $term
     *
     * @return mixed
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('response', 'like', '%' . $term . '%');
        });
    }

    /**
     * @param $query
     * @param $type
     *
     * @return mixed
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
