<?php

namespace App\Utils;

class WordCountUtil
{
    public static function validate(array $kelebihan, array $kekurangan)
    {
        $result = true;

        foreach ($kelebihan as $value) {
            $result &= str_word_count($value) >= config('liquid.word_count');
        }

        foreach ($kekurangan as $value) {
            $result &= str_word_count($value) >= config('liquid.word_count');
        }

        return (bool) $result;
    }
}
