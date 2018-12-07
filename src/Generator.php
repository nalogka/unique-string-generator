<?php

namespace Nalogka\UniqueStringGenerator;


class Generator
{

    const CHARSET_UP  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHARSET_LOW = 'abcdefghijklmnopqrstuvwxyz';
    const CHARSET_NUM  = '0123456789';
    const CHARSET_62  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHARSET_16_UP = '0123456789ABCDEF';
    const CHARSET_16_LOW = '0123456789abcdef';


    /**
     * @param $base
     * @param $length
     * @return mixed
     */
    public static function generate($base, $length)
    {
        $generated = '';

        for ($i = 0; $i < $length; $i++) {
            $generated .= $base[mt_rand(0, strlen($base) - 1)];
        }

        return $generated;
    }
}