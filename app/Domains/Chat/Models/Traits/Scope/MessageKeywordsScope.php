<?php

namespace App\Domains\Chat\Models\Traits\Scope;

/**
 * Class MessageKeywordsScope.
 */
trait MessageKeywordsScope
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
            $query->where('keywords', 'like', '%' . $term . '%')
                ->orWhere('response', 'like', '%' . $term . '%');
        });
    }
}
