<?php

namespace Database\Seeders;

use App\Domains\Chat\Models\MessageKeywords;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

/**
 * Class MessageKeywordsSeeder.
 */
class MessageKeywordsSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $this->truncate('message_keywords');

        /**
         * Quick reply
         * https://developers.line.biz/en/reference/messaging-api/#quick-reply
         *
         * Example
         * https://developers.line.biz/en/docs/messaging-api/using-quick-reply/#set-quick-reply-buttons
         */
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "Quick",
                "Reply",
                "Quick reply",
            ],
            'response' => [
                "quickReply" => [
                    "items" => [
                        [
                            "type" => "action",
                            "action" => [
                                "type" => "message",
                                "label" => "Message",
                                "text" => "Message action",
                            ],
                        ],
                        [
                            "type" => "action",
                            "action" => [
                                "type" => "message",
                                "label" => "Datetime picker",
                                "text" => "Datetime picker action",
                            ],
                        ],
                        [
                            "type" => "action",
                            "action" => [
                                "type" => "message",
                                "label" => "Camera",
                                "text" => "Camera action",
                            ],
                        ],
                        [
                            "type" => "action",
                            "action" => [
                                "type" => "message",
                                "label" => "Camera roll",
                                "text" => "Camera roll action",
                            ],
                        ],
                        [
                            "type" => "action",
                            "action" => [
                                "type" => "message",
                                "label" => "URI",
                                "text" => "URI action",
                            ],
                        ],
                        [
                            "type" => "action",
                            "action" => [
                                "type" => "message",
                                "label" => "Location",
                                "text" => "Location action",
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        /**
         * Message action
         * https://developers.line.biz/en/reference/messaging-api/#message-action
         */
        $messageActionItems = [];
        for ($i=0; $i < 5; $i++) {
            array_push($messageActionItems, [
                "type" => "action",
                "action" => [
                    "type" => "message",
                    "label" => "Message action $i",
                    "text" => "Message action $i (text)",
                ],
            ]);
        }
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "Message",
                "Message action",
            ],
            'response' => [
                "quickReply" => [
                    "items" => $messageActionItems,
                ],
            ],
        ]);

        /**
         * Datetime picker action
         * https://developers.line.biz/en/reference/messaging-api/#datetime-picker-action
         */
        $datetimeActionItems = [];
        for ($i=0; $i < 5; $i++) {
            array_push($datetimeActionItems, [
                "type" => "action",
                "action" => [
                    "type" => "datetimepicker",
                    "label" => "Select date $i",
                    "uri" => "tel:+88600000000$i",
                    "data" => "storeId=12345",
                    "mode" => "datetime",
                    "initial" => "2021-09-01t00:00",
                    "max" => "2021-12-31t23:59",
                    "min" => "2021-01-01t00:00"
                ],
            ]);
        }
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "Datetime",
                "Datetime picker",
                "Datetime picker action",
            ],
            'response' => [
                "quickReply" => [
                    "items" => $datetimeActionItems,
                ],
            ],
        ]);

        /**
         * Camera action
         * https://developers.line.biz/en/reference/messaging-api/#camera-action
         */
        $cameraActionItems = [];
        for ($i=0; $i < 5; $i++) {
            array_push($cameraActionItems, [
                "type" => "action",
                "action" => [
                    "type" => "camera",
                    "label" => "Camera $i"
                ]
            ]);
        }
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "Camera",
                "Camera action",
            ],
            'response' => [
                "quickReply" => [
                    "items" => $cameraActionItems,
                ],
            ],
        ]);

        /**
         * Camera roll action
         * https://developers.line.biz/en/reference/messaging-api/#camera-roll-action
         */
        $cameraRollActionItems = [];
        for ($i=0; $i < 5; $i++) {
            array_push($cameraRollActionItems, [
                "type" => "action",
                "action" => [
                    "type" => "cameraRoll",
                    "label" => "Camera roll $i",
                ],
            ]);
        }
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "Camera-Roll",
                "Camera-roll action",
            ],
            'response' => [
                "quickReply" => [
                    "items" => $cameraRollActionItems,
                ],
            ],
        ]);

        /**
         * URI action
         * https://developers.line.biz/en/reference/messaging-api/#uri-action
         *
         * 您需要了解有關新 LIFF URL 的所有資訊
         * https://engineering.linecorp.com/zh-hant/blog/new-liff-url-infomation/
         */
        $uriActionItems = [];
        for ($i=0; $i < 5; $i++) {
            array_push($uriActionItems, [
                "type" => "action",
                "action" => [
                    "type" => "uri",
                    "label" => "Google $i",
                    "uri" => "https://google.coom",
                ],
            ]);
        }
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "URI",
                "URI action",
            ],
            'response' => [
                "quickReply" => [
                    "items" => $uriActionItems,
                ],
            ],
        ]);

        /**
         * Location action
         * https://developers.line.biz/en/reference/messaging-api/#location-action
         */
        $locationActionItems = [];
        for ($i=0; $i < 5; $i++) {
            array_push($locationActionItems, [
                "type" => "action",
                "action" => [
                    "type" => "location",
                    "label" => "Location $i",
                ],
            ]);
        }
        MessageKeywords::create([
            'content' => 'Select ...',
            'keywords' => [
                "Location",
                "Location action",
            ],
            'response' => [
                "quickReply" => [
                    "items" => $locationActionItems,
                ],
            ],
        ]);

        $this->enableForeignKeys();
    }
}
