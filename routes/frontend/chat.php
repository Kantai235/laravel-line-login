<?php

use App\Domains\Chat\Http\Controllers\Frontend\ChatController;
use App\Domains\Chat\Http\Controllers\Frontend\WebhookController;
use Tabuna\Breadcrumbs\Trail;

/*
 * Chat Controllers
 * All route names are prefixed with 'frontend.chat'.
 */
Route::group([
    'prefix' => 'chat',
    'as' => 'chat.',
    'middleware' => ['auth', 'password.expires', config('boilerplate.access.middleware.verified')]
], function () {
    Route::get('/', [ChatController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Chat'), route('frontend.chat.index'));
        });

    Route::get('webhook', [WebhookController::class, 'index'])->name('webhook');
});
