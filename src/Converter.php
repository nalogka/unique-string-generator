<?php

namespace Nalogka\UniqueStringGenerator;

class Converter
{
    /**
     * Функция приведения строки с известным набором символов к десятичному представлению
     * @param $charset
     * @param $string
     * @return int|string
     */
    public static function toDecimal($charset, $string)
    {
        $base = strlen($charset); // 62

        $result = 0;

        $lastDigit = strlen($string) - 1;
        for ($i = 0; $i <= $lastDigit; $i++) {
            $char = $string[$lastDigit - $i];
            if (false === $position = strpos($charset, $char)) {
                throw new \RuntimeException(
                    sprintf(
                        'В приводимой к десятичному представлению строке присутствует недопустимый символ "%s".',
                        $char
                    )
                );
            }

            $result = bcadd($result, bcmul($position, bcpow($base, $i)));
        }

        return $result;
    }
}