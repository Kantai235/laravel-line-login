<?php

namespace App\Domains\Chat\Http\Controllers\Frontend;

/**
 * Class ChatController.
 */
class ChatController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('frontend.chat.index');
    }
}
