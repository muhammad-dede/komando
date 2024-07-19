<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    static public function responseGet($url_api)
    {
        $client = new Client();
        try {

            // Get data from API
            $response = $client->request('GET', $url_api);

            $json_string = $response->getBody();
            $list_data = json_decode($json_string);
            return response()->json($list_data, 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'ERROR', 'message' => $exception->getMessage()], 500);
        }
    }

    static public function responsePost($url_api, $params = [''])
    {
        $client = new Client();
        try {
            // Get data from API
            $response = $client->request('POST', $url_api, [
                'form_params' => $params
            ]);

            $json_string = $response->getBody();
            $list_data = json_decode($json_string);
            return response()->json($list_data, 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'ERROR', 'message' => $exception->getMessage()], 500);
        }
    }
}
