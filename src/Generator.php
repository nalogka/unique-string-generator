<?php

namespace Nalogka\UniqueStringGenerator;


class Generator
{

    const ALPHA_U  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const ALPHA_L = 'abcdefghijklmnopqrstuvwxyz';
    const DEC  = '0123456789';
    const DEC_ALPHA_L_U  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const HEX_U = '0123456789ABCDEF';
    const HEX_L = '0123456789abcdef';


    /**
     * @param $base
     * @param $length
     * @return mixed
     * @throws \Exception
     */
    public static function generate($base, $length)
    {
        $generated = '';

        for ($i = 0; $i < $length; $i++) {
            $generated .= $base[random_int(0, strlen($base) - 1)];
        }

        return $generated;
    }
}