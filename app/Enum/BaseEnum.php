<?php

namespace App\Enum;

abstract class BaseEnum
{
    public static function toDropdownArray()
    {
        $class = new \ReflectionClass(static::class);

        return $class->getConstants();
    }
}
