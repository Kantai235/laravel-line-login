<?php

use App\Domains\Chat\Http\Controllers\Backend\Reply\DeletedReplyController;
use App\Domains\Chat\Http\Controllers\Backend\Reply\ReplyController;
use App\Domains\Chat\Models\MessageKeywords;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.line'.
Route::group([
    'prefix' => 'line',
    'as' => 'line.',
    'middleware' => config('boilerplate.access.middleware.confirm'),
], function () {
    // All route names are prefixed with 'admin.line.reply'.
    Route::group([
        'prefix' => 'reply',
        'as' => 'reply.',
    ], function () {
        Route::get('deleted', [DeletedReplyController::class, 'index'])
            ->name('deleted')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.line.reply.index')
                    ->push(__('Deleted Replys'), route('admin.line.reply.deleted'));
            });

        Route::get('create', [ReplyController::class, 'create'])
            ->name('create')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.line.reply.index')
                    ->push(__('Create Reply'), route('admin.line.reply.create'));
            });

        Route::post('/', [ReplyController::class, 'store'])->name('store');

        Route::group(['prefix' => '{reply}'], function () {
            Route::get('edit', [ReplyController::class, 'edit'])
                ->name('edit')
                ->breadcrumbs(function (Trail $trail, MessageKeywords $model) {
                    $trail->parent('admin.line.reply.show', $model)
                        ->push(__('Edit'), route('admin.line.reply.edit', $model));
                });

            Route::patch('/', [ReplyController::class, 'update'])->name('update');
            Route::delete('/', [ReplyController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => '{deletedReply}'], function () {
            Route::patch('restore', [DeletedReplyController::class, 'update'])->name('restore');
            Route::delete('permanently-delete', [DeletedReplyController::class, 'destroy'])->name('permanently-delete');
        });

        Route::get('/', [ReplyController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Reply Management'), route('admin.line.reply.index'));
            });

        Route::group(['prefix' => '{reply}'], function () {
            Route::get('/', [ReplyController::class, 'show'])
                ->name('show')
                ->middleware('permission:admin.access.user.list')
                ->breadcrumbs(function (Trail $trail, MessageKeywords $model) {
                    $trail->parent('admin.line.reply.index')
                        ->push($model->id, route('admin.line.reply.show', $model));
                });
        });
    });
});
