<?php

namespace App\Domains\Chat\Models\Traits\Scope;

/**
 * Class LineUsersScope.
 */
trait LineUsersScope
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
            $query->where('display_name', 'like', '%' . $term . '%')
                ->orWhere('status_message', 'like', '%' . $term . '%');
        });
    }
}
