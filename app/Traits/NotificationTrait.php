<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\Notification;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait NotificationTrait
{
    public function sendNotification($receivers, $type, $notificationData = null, $icon = null, $notifiable_type = null, $notifiable_id = null)
    {
        try {

            if (auth('api')->check()) {
                $sender_type = User::class;
                $sender_id = auth('api')->id();
            } elseif (auth('seller')->check()) {
                $sender_type = Seller::class;
                $sender_id = auth('seller')->id();
            } elseif (auth('admin')->check()) {
                $sender_type = Admin::class;
                $sender_id = auth('admin')->id();
            } else {
                $sender_type = null;
                $sender_id = null;
            }
            $registration_ids = [];
            foreach ($receivers as $receiver_type) {
                foreach ($receiver_type as $receiver) {

                    $registration_ids[] = $receiver->device_token;
                     $notification = Notification::make([
                        'title' => $notificationData['title'],
                        'content' => $notificationData['body'],
                        'type' => $type,
                        'sender_type' => $sender_type,
                        'sender_id' => $sender_id,
                        'notifiable_type' => $notifiable_type,
                        'notifiable_id' => $notifiable_id,
                    ]);

                    $notification->receiver()->associate($receiver);
                    $notification->save();
                }
            }
//            $SERVER_API_KEY = "AAAA-stxhE0:APA91bFXVNy7kGgrwNXT32pZllRQi7Gbq9Tp3oWGrbFN7_Ksd9KHPHyAeILUihVp8DJQW4WDm0Mty-dGkXsWzRUmTsvTMRNSjDOlrvFrzv62hHrkqxALTWizPWdJIHKtkojjFTXZg285";
            $SERVER_API_KEY = get_setting('firebase_server_api_key');

            $fields = array(
                'priority' => 'high',
                'registration_ids' => $registration_ids,
                'notification' => [
                    'icon' => url($icon ?? ''),
                    'title' => $notificationData['title'],
                    'body' => $notificationData['body'],
                    'sound' => 'default'
                ],
                "content_available" => true

            );

            $header = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-type: application/json'
            ];
            $dataString = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            return $response;
        } catch (\Exception $exception) {
        }

    }
}
