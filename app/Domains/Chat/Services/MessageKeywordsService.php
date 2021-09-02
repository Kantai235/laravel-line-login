<?php

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Models\MessageKeywords;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class MessageKeywordsService.
 */
class MessageKeywordsService extends BaseService
{
    /**
     * MessageKeywordsService constructor.
     *
     * @param MessageKeywords $model
     */
    public function __construct(MessageKeywords $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $id
     *
     * @throws GeneralException
     * @return mixed
     */
    public function findByKeywords(string $keyword)
    {
        $model = $this->model
            ->where('name', 'like', '%' . $keyword . '%')
            ->first();

        if ($model instanceof $this->model) {
            return $model;
        }

        return false;
        // throw new GeneralException(__('That model does not exist.'));
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws GeneralException
     */
    public function registerModel(array $data = []): MessageKeywords
    {
        DB::beginTransaction();

        try {
            $model = $this->createModel($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating this model.'));
        }

        DB::commit();

        return $model;
    }

    /**
     * @param array $data
     *
     * @return MessageKeywords
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): MessageKeywords
    {
        DB::beginTransaction();

        try {
            $model = $this->createModel($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating this model. Please try again.'));
        }

        // event(new MessageKeywordsCreated($model));

        DB::commit();

        return $model;
    }

    /**
     * @param MessageKeywords $model
     * @param array $data
     *
     * @return MessageKeywords
     * @throws \Throwable
     */
    public function update(MessageKeywords $model, array $data = []): MessageKeywords
    {
        DB::beginTransaction();

        try {
            $model->update($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem updating this model. Please try again.'));
        }

        // event(new MessageKeywordsUpdated($model));

        DB::commit();

        return $model;
    }

    /**
     * @param MessageKeywords $model
     *
     * @return MessageKeywords
     * @throws GeneralException
     */
    public function delete(MessageKeywords $model): MessageKeywords
    {
        if ($this->deleteById($model->id)) {
            // event(new MessageKeywordsDeleted($model));

            return $model;
        }

        throw new GeneralException('There was a problem deleting this model. Please try again.');
    }

    /**
     * @param MessageKeywords $model
     *
     * @return MessageKeywords
     * @throws GeneralException
     */
    public function restore(MessageKeywords $model): MessageKeywords
    {
        if ($model->restore()) {
            // event(new MessageKeywordsRestored($model));

            return $model;
        }

        throw new GeneralException(__('There was a problem restoring this model. Please try again.'));
    }

    /**
     * @param MessageKeywords $model
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(MessageKeywords $model): bool
    {
        if ($model->forceDelete()) {
            // event(new MessageKeywordsDestroyed($model));

            return true;
        }

        throw new GeneralException(__('There was a problem permanently deleting this model. Please try again.'));
    }

    /**
     * @param array $data
     *
     * @return MessageKeywords
     */
    protected function createModel(array $data = []): MessageKeywords
    {
        return $this->model::create([
            'keywords' => json_encode($data['keywords']),
            'response' => json_encode($data['response']),
        ]);
    }
}
