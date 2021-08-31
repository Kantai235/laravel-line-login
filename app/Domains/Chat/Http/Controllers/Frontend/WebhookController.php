<?php

namespace App\Domains\Chat\Http\Controllers\Frontend;

use App\Domains\Chat\Services\LineEventsService;
use App\Domains\Chat\Services\LineUsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

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
        Log::debug(json_encode($request->all()));
        $events = $request->all();
        if (isset($events['events']) && is_array($events['events'])) {
            foreach ($events['events'] as $event) {
                $this->eventService->store([
                    'destination' => $events['destination'] ?? null,
                    'type' => $event['type'] ?? null,
                    'response' => $event,
                ]);

                if (isset($event['source']) && isset($event['source']['userId'])) {
                    $url = sprintf('https://api.line.me/v2/bot/profile/%s', $event['source']['userId']);
                    $headers = ['Authorization' => 'Bearer ' . config('line.channel.access_token')];
                    $response = Http::withHeaders($headers)->get($url);
                    Log::debug(json_encode($response->json()));
                    if ($user = $this->userService->findByUserId($event['source']['userId'])) {
                        $this->userService->update($user, $response->json());
                    } else {
                        $this->userService->store($response->json());
                    }
                }
            }
        }

        return response()->json(null, 200);
    }
}
