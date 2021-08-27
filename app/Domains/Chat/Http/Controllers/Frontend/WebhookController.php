<?php

namespace App\Domains\Chat\Http\Controllers\Frontend;

use App\Domains\Auth\Services\LineEventsService;
use App\Domains\Auth\Services\LineUsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Class WebhookController.
 */
class WebhookController
{
    /**
     * @var LineUsersService
     */
    protected $userService;

    /**
     * @var LineEventsService
     */
    protected $eventService;

    /**
     * WebhookController constructor.
     *
     * @param LineUsersService $userService
     * @param LineEventsService $eventService
     */
    public function __construct(LineUsersService $userService, LineEventsService $eventService)
    {
        $this->userService = $userService;
        $this->eventService = $eventService;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $events = $request->all();
        foreach ($events['events'] as $event) {
            $this->eventService->store([
                'destination' => $events['destination'],
                'type' => $event['type'],
                'response' => $event,
            ]);

            if (!$this->userService->findByUserId($event['source']['userId'])) {
                $response = Http::withToken(config('line.channel.access_token'))
                                ->get(sprintf('https://api.line.me/v2/bot/profile/%s', $event['source']['userId']));
                $this->userService->store($response->json());
            }
        }

        return response()->json(null, 200);
    }
}
