<?php

namespace App\Domains\Chat\Http\Controllers\Frontend;

use App\Domains\Chat\Services\LineEventsService;
use App\Domains\Chat\Services\LineUsersService;
use App\Domains\Chat\Services\MessageKeywordsService;
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
     * @var MessageKeywordsService
     */
    protected $keywordService;

    /**
     * WebhookController constructor.
     *
     * @param LineUsersService $userService
     * @param LineEventsService $eventService
     * @param MessageKeywordsService $keywordService
     */
    public function __construct(
        LineUsersService $userService,
        LineEventsService $eventService,
        MessageKeywordsService $keywordService)
    {
        $this->client = Http::withToken(config('line.channel.access_token'));
        $this->userService = $userService;
        $this->eventService = $eventService;
        $this->keywordService = $keywordService;
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
                    /**
                     * 如果有符合 MessageKeywords 的關鍵字，那就依照 response 來回應
                     */
                    if ($model = $this->keywordService->findByKeywords($event['message']['text'])) {
                        $request = [
                            'replyToken' => $event['replyToken'],
                            'messages' => [
                                'replyToken' => $model->response['quickReply'],
                            ],
                        ];
                        if (isset($model->content)) {
                            $request['messages']['text'] = $model->content;
                            $request['messages']['type'] = 'text';
                        }
                        Log::debug(json_encode($request));
                        $response = $this->client->post($this->root . 'message/reply', $request);
                        Log::debug(json_encode($response->json()));
                    } else {
                        /**
                         * 找不到關鍵字，因此透過寫死判定來回應
                         */
                        $this->replyMessage($event['message']['text'], $event['replyToken']);
                    }
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
                                        /**
                                         * Message action
                                         * https://developers.line.biz/en/reference/messaging-api/#message-action
                                         */
                                        'type' => 'action',
                                        'action' => [
                                            'type' => 'message',
                                            'label' => 'Message action',
                                            'text' => 'Message action (text)',
                                        ],
                                    ],
                                    [
                                        /**
                                         * Datetime picker action
                                         * https://developers.line.biz/en/reference/messaging-api/#datetime-picker-action
                                         */
                                        'type' => 'action',
                                        'action' => [
                                            'type' => 'datetimepicker',
                                            'label' => 'Select date',
                                            'uri' => 'tel:+886492773121',
                                            "data" => "storeId=12345",
                                            "mode" => "datetime",
                                            "initial" => "2017-12-25t00:00",
                                            "max" => "2018-01-24t23:59",
                                            "min" => "2017-12-25t00:00",
                                        ],
                                    ],
                                    [
                                        /**
                                         * Camera action
                                         * https://developers.line.biz/en/reference/messaging-api/#camera-action
                                         */
                                        'type' => 'action',
                                        'action' => [
                                            'type' => 'camera',
                                            'label' => 'Camera',
                                        ],
                                    ],
                                    [
                                        /**
                                         * Camera roll action
                                         * https://developers.line.biz/en/reference/messaging-api/#camera-roll-action
                                         */
                                        'type' => 'action',
                                        'action' => [
                                            'type' => 'cameraRoll',
                                            'label' => 'Camera roll',
                                        ],
                                    ],
                                    [
                                        /**
                                         * URI action
                                         * https://developers.line.biz/en/reference/messaging-api/#uri-action
                                         *
                                         * 您需要了解有關新 LIFF URL 的所有資訊
                                         * https://engineering.linecorp.com/zh-hant/blog/new-liff-url-infomation/
                                         */
                                        'type' => 'action',
                                        'action' => [
                                            'type' => 'uri',
                                            'label' => 'Google',
                                            'uri' => 'https://google.coom',
                                        ],
                                    ],
                                    [
                                        /**
                                         * Location action
                                         * https://developers.line.biz/en/reference/messaging-api/#location-action
                                         */
                                        'type' => 'action',
                                        'action' => [
                                            'type' => 'location',
                                            'label' => 'Location',
                                        ],
                                    ],
                                    // [
                                    //     /**
                                    //      * Rich menu switch action
                                    //      * https://developers.line.biz/en/reference/messaging-api/#richmenu-switch-action
                                    //      */
                                    //     'type' => 'action',
                                    //     'action' => [
                                    //         'type' => 'richmenuswitch',
                                    //         'richMenuAliasId' => 'richmenu-alias-b',
                                    //         'data' => 'richmenu-changed-to-b',
                                    //     ],
                                    // ],
                                ],
                            ],
                        ]
                    ],
                ]);
                Log::debug(json_encode($response->json()));
                return;

            /**
             * Text message
             * https://developers.line.biz/en/reference/messaging-api/#text-message
             */
            case 'Text message':

                $response = $this->client->post($this->root . 'message/reply', [
                    'replyToken' => $reply_token,
                    'messages' => [
                        [
                            // Text message example
                            "type" => "text",
                            "text" => "Hello, world",
                        ],
                        [
                            // Text message example with LINE emoji
                            "type" => "text",
                            "text" => "$ LINE emoji $",
                            "emojis" => [
                              [
                                "index" => 0,
                                "productId" => "5ac1bfd5040ab15980c9b435",
                                "emojiId" => "001"
                              ],
                              [
                                "index" => 13,
                                "productId" => "5ac1bfd5040ab15980c9b435",
                                "emojiId" => "002",
                              ],
                            ],
                        ],
                        [
                            // Text message example with LINE original unicode emoji (deprecated)
                            "type" => "text",
                            "text" => "\uDBC0\uDC84 LINE original emoji",
                        ],
                    ],
                ]);
                Log::debug(json_encode($response->json()));
                return;
        }
    }
}
