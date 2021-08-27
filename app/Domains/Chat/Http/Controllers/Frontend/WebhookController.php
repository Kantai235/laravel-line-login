<?php

namespace App\Domains\Chat\Http\Controllers\Frontend;

use Illuminate\Http\Request;

/**
 * Class WebhookController.
 */
class WebhookController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        activity('line')->log(json_encode($request->json()));

        return response()->json(null, 200);
    }
}
