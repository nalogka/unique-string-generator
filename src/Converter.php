<?php

namespace Nalogka\UniqueStringGenerator;

class Converter
{
    const BINARY_SHIFT_SIZE = 1<<8; // 256

    /**
     * Функция приведения строки с известным набором символов к десятичному представлению
     * @param $charset
     * @param $string
     * @return int|string
     */
    public static function toDecimal($charset, $string)
    {
        $base = strlen($charset);

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

    /**
     * Функция приведения строки из десятичного представления к строке с известным набором символов
     * @param $charset
     * @param $string
     * @return int|string
     */
    public static function fromDecimal($charset, $decimal)
    {
        $base = strlen($charset);

        $result = '';

        while ($decimal) {
            $result = $charset[bcmod($decimal, $base)] . $result;
            $decimal = bcdiv($decimal, $base);
        }

        return $result ?: $charset[$decimal];
    }

    /**
     * Функция приведения строки из десятичного представлекния в бинарное
     * @param $string
     * @return string
     */
    public static function decimalToBinary($string)
    {
        bcscale(0);
        $binary = '';
        $string = bcadd(0, $string);

        if ($string === '0') {
            return "\x00";
        }

        while ($string !== '0') {
            $binary = pack('C', bcmod($string, self::BINARY_SHIFT_SIZE)) . $binary; // Берем значение младшего байта в числе и дописываем в бинарном виде в начало результирующей строки
            $string = bcdiv($string, self::BINARY_SHIFT_SIZE); // Сдвигаем исходное число на один байт вправо
        }

        return $binary;
    }

    /**
     * Функция приведения строки из бинарного представления в десятичное
     * @param $string
     * @return string
     */
    public static function binaryToDecimal($string)
    {
        bcscale(0);
        $decimal = '0';
        $i = 0;

        while ($string) {
            $decimal = bcadd($decimal, bcmul(current(unpack('C', substr($string, -1))), bcpow(2, 8 * $i++)));
            $string = substr($string, 0, -1);
        }

        return $decimal;
    }


}