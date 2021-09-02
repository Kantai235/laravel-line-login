<?php

namespace App\Domains\Chat\Http\Controllers\Backend\Reply;

use App\Domains\Chat\Http\Requests\Backend\Reply\DeleteReplyRequest;
use App\Domains\Chat\Http\Requests\Backend\Reply\EditReplyRequest;
use App\Domains\Chat\Http\Requests\Backend\Reply\StoreReplyRequest;
use App\Domains\Chat\Http\Requests\Backend\Reply\UpdateReplyRequest;
use App\Domains\Chat\Models\MessageKeywords;
use App\Domains\Chat\Services\MessageKeywordsService;

/**
 * Class ReplyController.
 */
class ReplyController
{
    /**
     * @var MessageKeywordsService
     */
    protected $service;

    /**
     * ReplyController constructor.
     *
     * @param MessageKeywordsService $service
     */
    public function __construct(MessageKeywordsService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.line.reply.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view('backend.line.reply.create');
    }

    /**
     * @param StoreReplyRequest $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreReplyRequest $request)
    {
        $reply = $this->service->store($request->validated());

        return redirect()->route('admin.line.reply.show', $reply)->withFlashSuccess(__('The reply was successfully created.'));
    }

    /**
     * @param MessageKeywords $model
     *
     * @return mixed
     */
    public function show(MessageKeywords $model)
    {
        return view('backend.line.reply.show')
            ->with('model', $model);
    }

    /**
     * @param EditReplyRequest $request
     * @param MessageKeywords $model
     *
     * @return mixed
     */
    public function edit(EditReplyRequest $request, MessageKeywords $model)
    {
        return view('backend.line.reply.edit')
            ->with('model', $model);
    }

    /**
     * @param UpdateReplyRequest $request
     * @param MessageKeywords $model
     *
     * @return mixed
     * @throws \Throwable
     */
    public function update(UpdateReplyRequest $request, MessageKeywords $model)
    {
        $this->service->update($model, $request->validated());

        return redirect()->route('admin.line.reply.show', $model)->withFlashSuccess(__('The reply was successfully updated.'));
    }

    /**
     * @param DeleteReplyRequest $request
     * @param MessageKeywords $model
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(DeleteReplyRequest $request, MessageKeywords $model)
    {
        $this->service->delete($model);

        return redirect()->route('admin.line.reply.deleted')->withFlashSuccess(__('The model was successfully deleted.'));
    }
}
