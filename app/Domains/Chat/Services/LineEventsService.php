<?php

namespace App\Domains\Auth\Services;

use App\Domains\Chat\Models\LineEvents;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class LineEventsService.
 */
class LineEventsService extends BaseService
{
    /**
     * LineEventsService constructor.
     *
     * @param LineEvents $event
     */
    public function __construct(LineEvents $event)
    {
        $this->model = $event;
    }

    /**
     * @param $type
     * @param bool|int $perPage
     *
     * @return mixed
     */
    public function getByType($type, $perPage = false)
    {
        if (is_numeric($perPage)) {
            return $this->model::byType($type)->paginate($perPage);
        }

        return $this->model::byType($type)->get();
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws GeneralException
     */
    public function registerEvent(array $data = []): LineEvents
    {
        DB::beginTransaction();

        try {
            $event = $this->createEvent($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating this event.'));
        }

        DB::commit();

        return $event;
    }

    /**
     * @param array $data
     *
     * @return LineEvents
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): LineEvents
    {
        DB::beginTransaction();

        try {
            $event = $this->createEvent([
                'destination' => $data['destination'],
                'type' => $data['type'],
                'response' => $data['response'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating this event. Please try again.'));
        }

        // event(new LineEventsCreated($event));

        DB::commit();

        return $event;
    }

    /**
     * @param LineEvents $event
     * @param array $data
     *
     * @return LineEvents
     * @throws \Throwable
     */
    public function update(LineEvents $event, array $data = []): LineEvents
    {
        DB::beginTransaction();

        try {
            $event->update([
                'destination' => $data['destination'],
                'type' => $data['type'],
                'response' => $data['response'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem updating this event. Please try again.'));
        }

        // event(new LineEventsUpdated($event));

        DB::commit();

        return $event;
    }

    /**
     * @param LineEvents $event
     *
     * @return LineEvents
     * @throws GeneralException
     */
    public function delete(LineEvents $event): LineEvents
    {
        if ($this->deleteById($event->id)) {
            // event(new LineEventsDeleted($event));

            return $event;
        }

        throw new GeneralException('There was a problem deleting this event. Please try again.');
    }

    /**
     * @param LineEvents $event
     *
     * @return LineEvents
     * @throws GeneralException
     */
    public function restore(LineEvents $event): LineEvents
    {
        if ($event->restore()) {
            // event(new LineEventsRestored($event));

            return $event;
        }

        throw new GeneralException(__('There was a problem restoring this event. Please try again.'));
    }

    /**
     * @param LineEvents $event
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(LineEvents $event): bool
    {
        if ($event->forceDelete()) {
            // event(new LineEventsDestroyed($event));

            return true;
        }

        throw new GeneralException(__('There was a problem permanently deleting this event. Please try again.'));
    }

    /**
     * @param array $data
     *
     * @return LineEvents
     */
    protected function createEvent(array $data = []): LineEvents
    {
        return $this->model::create([
            'destination' => $data['destination'],
            'type' => $data['type'],
            'response' => json_encode($data['response']),
        ]);
    }
}
