<?php

namespace App\Services;

class SharedService
{
    public function postOrGetApiData($apiUrl, $postData)
    {
        $apiUrl = url('/') . $apiUrl;        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8000/api/v1/services/list',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_VERBOSE => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return 'cURL Error: ' . curl_error($curl);
        }

        curl_close($curl);
        echo $response;
    }
}