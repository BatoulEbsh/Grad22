<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class NotificationController extends Controller
{
    use ReturnResponse;
    use Helper;
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_key = $request['token'];
        Auth::user()->save();
        return $this->returnSuccessMessage('user token updated successfully');
    }
    #[NoReturn] public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        $serverKey = 'server key goes here';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        dd($result);

    }
}
