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
     * @var PendingRequest
     */
    protected $client;

    /**
     * @var string
     */
    protected $root = 'https://api.line.me/v2/bot/';

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
        $this->client = Http::withToken(config('line.channel.access_token'));
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
        /**
         * 不管怎麼樣，先把請求丟 Log 再說。
         */
        Log::debug(json_encode($request->all()));

        /**
         * 嘗試抓出所有的 Events 資訊。
         */
        $events = $request->all();
        if (isset($events['events']) && is_array($events['events'])) {
            foreach ($events['events'] as $event) {
                /**
                 * 新增 Event 資訊。
                 */
                $this->eventService->store([
                    'destination' => $events['destination'] ?? null,
                    'type' => $event['type'] ?? null,
                    'response' => $event,
                ]);

                /**
                 * 嘗試向 LINE 請求使用者更詳細的資訊。
                 */
                if (isset($event['source']) && isset($event['source']['userId'])) {
                    $url = sprintf('%s/profile/%s', $this->root, $event['source']['userId']);
                    $response = $this->client->get($url);
                    Log::debug(json_encode($response->json()));

                    /**
                     * 新增或更新使用者資訊。
                     */
                    if ($user = $this->userService->findByUserId($event['source']['userId'])) {
                        $this->userService->update($user, $response->json());
                    } else {
                        $this->userService->store($response->json());
                    }
                }

                /**
                 * 嘗試根據使用者的內容來回應資訊。
                 */
                if (
                    isset($event['replyToken']) &&
                    isset($event['message']) &&
                    isset($event['message']['text'])
                ) {
                    $this->replyMessage($event['message']['text'], $event['replyToken']);
                }
            }
        }

        return response()->json(null, 200);
    }

    /**
     * Send reply message
     * https://developers.line.biz/en/reference/messaging-api/#send-reply-message
     *
     * @param string $message
     * @param string $reply_token
     */
    public function replyMessage(string $message, string $reply_token)
    {
        /**
         * Message objects
         * https://developers.line.biz/en/reference/messaging-api/#message-objects
         */
        switch ($message) {
            /**
             * Quick reply
             * https://developers.line.biz/en/reference/messaging-api/#quick-reply
             *
             * Example
             * https://developers.line.biz/en/docs/messaging-api/using-quick-reply/#set-quick-reply-buttons
             */
            case 'Quick reply':
                $response = $this->client->post($this->root . 'message/reply', [
                    'replyToken' => $reply_token,
                    'messages' => [
                        [
                            "text" => "Quick reply example.",
                            "type" => "text",
                            'quickReply' => [
                                'items' => [
                                    [
                                        'type' => 'action',
                                        'action' => [
                                            'label' => 'Google 超連結',
                                            'type' => 'uri',
                                            'uri' => 'https://www.google.com.tw',
                                        ],
                                    ],
                                    [
                                        'type' => 'action',
                                        'action' => [
                                            'label' => '打電話給「玉山國家公園」',
                                            'type' => 'uri',
                                            'uri' => 'tel:+886492773121',
                                        ],
                                    ],
                                    [
                                        'type' => 'action',
                                        'action' => [
                                            'label' => '寄 Email 給乾太',
                                            'type' => 'uri',
                                            'uri' => 'mailto:kantai.developer@gmail.com',
                                        ],
                                    ],
                                    /**
                                     * 您需要了解有關新 LIFF URL 的所有資訊
                                     * https://engineering.linecorp.com/zh-hant/blog/new-liff-url-infomation/
                                     */
                                    [
                                        'type' => 'action',
                                        'action' => [
                                            'label' => 'LIFF URL',
                                            'type' => 'uri',
                                            'uri' => 'https://liff.line.me/1653575653-e9vXldMN',
                                        ],
                                    ],
                                    [
                                        'type' => 'action',
                                        'action' => [
                                            'label' => '打開「玉山國家公園」地圖',
                                            'type' => 'uri',
                                            'uri' => 'https://www.google.com/maps/place/玉山國家公園/@24.0857955,120.974425,8.93z/data=!4m5!3m4!1s0x346edf7afc18cf61:0x900cc892465fcc1b!8m2!3d23.4698853!4d120.957737',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    ],
                ]);
                Log::debug(json_encode($response->json()));
                return;
        }
    }
}
