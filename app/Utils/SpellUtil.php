<?php

namespace App\Utils;

class SpellUtil
{
    public $lang;

    public function __construct()
    {
        $this->lang = 'id';
    }

    public function check($words)
    {
        $json = json_decode($this->getTranslate($words), true);

        if (! array_key_exists('spell', $json)) {
            return $words;
        }

        return strip_tags($json['spell']['spell_html_res']);
    }

    private function getTranslate($words)
    {
        try {
            $curl = curl_init();
            $language = $this->lang;

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://translate.google.com/translate_a/t?client=at&sc=1&v=2.0&sl=$language&tl=$language&hl=nl&ie=UTF-8&oe=UTF-8&text=" . urlencode($words),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => "gzip, deflate",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: */*",
                    "Accept-Encoding: gzip, deflate",
                    "cache-control: no-cache,no-cache",
                    "connection: Keep-Alive",
                    "content-length: 0",
                    "host: translate.google.com",
                    "user-agent: AndroidTranslate/2.5.3 2.5.3 (gzip)"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        } catch (\Throwable $th) {
            $th;
        }

        return $response;
    }
}
