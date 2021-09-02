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

        Route::post('/', [UserController::class, 'store'])->name('store');

        Route::group(['prefix' => '{user}'], function () {
            Route::get('edit', [UserController::class, 'edit'])
                ->name('edit')
                ->breadcrumbs(function (Trail $trail, MessageKeywords $model) {
                    $trail->parent('admin.line.reply.show', $model)
                        ->push(__('Edit'), route('admin.line.reply.edit', $model));
                });

            Route::patch('/', [UserController::class, 'update'])->name('update');
            Route::delete('/', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => '{deletedUser}'], function () {
            Route::patch('restore', [DeletedUserController::class, 'update'])->name('restore');
            Route::delete('permanently-delete', [DeletedUserController::class, 'destroy'])->name('permanently-delete');
        });
    });

    Route::group([
        'middleware' => 'permission:admin.access.user.list|admin.access.user.deactivate|admin.access.user.reactivate|admin.access.user.clear-session|admin.access.user.impersonate|admin.access.user.change-password',
    ], function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('User Management'), route('admin.line.reply.index'));
            });

        Route::group(['prefix' => '{user}'], function () {
            Route::get('/', [UserController::class, 'show'])
                ->name('show')
                ->middleware('permission:admin.access.user.list')
                ->breadcrumbs(function (Trail $trail, User $user) {
                    $trail->parent('admin.line.reply.index')
                        ->push($user->name, route('admin.line.reply.show', $user));
                });
        });
    });
});
