<?php

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Models\LineUsers;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class LineUsersService.
 */
class LineUsersService extends BaseService
{
    /**
     * LineUsersService constructor.
     *
     * @param LineUsers $user
     */
    public function __construct(LineUsers $user)
    {
        $this->model = $user;
    }

    /**
     * @param string $id
     *
     * @throws GeneralException
     * @return mixed
     */
    public function findByUserId(string $id)
    {
        $user = $this->model
            ->where('user_id', $id)
            ->first();

        if ($user instanceof $this->model) {
            return $user;
        }

        return false;
        // throw new GeneralException(__('That user does not exist.'));
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws GeneralException
     */
    public function registerUser(array $data = []): LineUsers
    {
        DB::beginTransaction();

        try {
            $event = $this->createUser($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating this user.'));
        }

        DB::commit();

        return $event;
    }

    /**
     * @param array $data
     *
     * @return LineUsers
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): LineUsers
    {
        DB::beginTransaction();

        try {
            $user = $this->createUser($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating this user. Please try again.'));
        }

        // event(new LineUsersCreated($user));

        DB::commit();

        return $user;
    }

    /**
     * @param LineUsers $event
     * @param array $data
     *
     * @return LineUsers
     * @throws \Throwable
     */
    public function update(LineUsers $user, array $data = []): LineUsers
    {
        DB::beginTransaction();

        try {
            $user->update($data);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem updating this user. Please try again.'));
        }

        // event(new LineUsersUpdated($user));

        DB::commit();

        return $user;
    }

    /**
     * @param LineUsers $user
     *
     * @return LineUsers
     * @throws GeneralException
     */
    public function delete(LineUsers $user): LineUsers
    {
        if ($this->deleteById($user->id)) {
            // event(new LineUsersDeleted($user));

            return $user;
        }

        throw new GeneralException('There was a problem deleting this user. Please try again.');
    }

    /**
     * @param LineUsers $user
     *
     * @return LineUsers
     * @throws GeneralException
     */
    public function restore(LineUsers $user): LineUsers
    {
        if ($user->restore()) {
            // event(new LineUsersRestored($user));

            return $user;
        }

        throw new GeneralException(__('There was a problem restoring this user. Please try again.'));
    }

    /**
     * @param LineUsers $user
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(LineUsers $user): bool
    {
        if ($user->forceDelete()) {
            // event(new LineUsersDestroyed($user));

            return true;
        }

        throw new GeneralException(__('There was a problem permanently deleting this user. Please try again.'));
    }

    /**
     * @param array $data
     *
     * @return LineUsers
     */
    protected function createUser(array $data = []): LineUsers
    {
        return $this->model::create([
            'user_id' => $data['user_id'],
            'display_name' => $data['display_name'] ?? null,
            'language' => $data['language'] ?? null,
            'picture_url' => $data['picture_url'] ?? null,
            'status_message' => $data['status_message'] ?? null,
        ]);
    }
}
