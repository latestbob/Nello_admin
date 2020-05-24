<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 5/23/2020
 * Time: 5:46 AM
 */

namespace App\Traits;

trait FirebaseNotification
{
    public function sendNotification(array $deviceTokens, string $title, string $message,
                                     string $priority = 'high', array $payload = null)
    {
        $data = [
            "registration_ids" => $deviceTokens,
            'priority' => $priority,
            "notification" => [
                "title" => $title,
                "body" => $message
            ],
        ];

        if ($payload !== null) $data['data'] = $payload;

        $headers = [
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        return curl_exec($ch);
    }
}
