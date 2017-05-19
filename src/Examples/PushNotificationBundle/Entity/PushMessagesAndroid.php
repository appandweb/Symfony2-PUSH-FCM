<?php

namespace Examples\PushNotificationBundle\Entity;

class PushMessagesAndroid {

    private static $androidAuthKey = "your api key here";
    
    public static function sendMessage($tokenArray,$params) {
        $data = array(
            'registration_ids' => $tokenArray, 
            'data' => $params
        );
        $headers = array(
            "Content-Type:application/json",
            "Authorization:key=" . self::$androidAuthKey
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);

        
        return $result;
    }       
}
