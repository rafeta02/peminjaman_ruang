<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait WaBlastTrait
{
    public function sendNotification($nomor, $message)
    {
        $url = 'https://sipsmart.uns.ac.id/api/default/api-wa';
        $token = md5('risnov.uns.ac.id/kendaraan'. date('Y-m-d'));
        $key = 'mrA23iB';
        $pesan = '*Portal Informasi dan Peminjaman Ruang LPPM.*
'. $message;

        $response = Http::get($url, [
            'nomor' => $nomor,
            'pesan' => $pesan,
            'token' => $token,
            'key' => $key
        ]);

        $result = (string)$response->getBody();
        return json_decode($result);
    }

    // Fungsi lama pake akun trial ramadhan
    function kirimWablas($phone, $msg)
    {
        $link  =  "https://console.wablas.com/api/send-message";
        $data = [
        'phone' => $phone,
        'message' => $msg,
        ];

        $curl = curl_init();
        $token =  "QW6G6OsQEHhXj3eUv2lXD4xGGpDSWQlnHvlDYc0Mf8TscZJ9vaUNYa7N6pzSYbw8";

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
