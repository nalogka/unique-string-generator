<?php

namespace Nalogka\UniqueStringGenerator;

class Converter
{
    /**
     * Приведение строки с известным набором символов к десятичному представлению
     * @param string $charset
     * @param string $string
     * @return string
     */
    public static function toDecimal($charset, $string)
    {
        $base = strlen($charset);

        $decimalStr = '0';

        $lastDigit = strlen($string) - 1;
        for ($i = 0; $i <= $lastDigit; $i++) {
            $char = $string[$lastDigit - $i];
            if (false === $position = strpos($charset, $char)) {
                throw new \RuntimeException(
                    sprintf(
                        'В строке присутствует недопустимый символ "%s".',
                        $char
                    )
                );
            }

            $decimalStr = bcadd($decimalStr, bcmul($position, bcpow($base, $i)));
        }

        return $decimalStr;
    }

    /**
     * Приведение строки из десятичного представления к строке с известным набором символов
     * @param string $charset
     * @param string $string
     * @return int|string
     */
    public static function fromDecimal($charset, $decimalStr, $length)
    {
        $base = strlen($charset);

        $string = '';
        $decimalStr = bcadd('0', $decimalStr);
        
        while ($decimalStr !== '0') {
            $string = $charset[bcmod($decimalStr, $base)] . $string;
            $decimalStr = bcdiv($decimalStr, $base);
        }

        return str_pad($string, $length, $charset[0], STR_PAD_LEFT);
    }

    /**
     * Упаковка строки с известным набором символов в бинарную строку
     * @param string $charset Набор символов алфавита 
     * @param string $string  Строка для преобразования
     * @return string
     */
    public static function toBinary($charset, $string)
    {
        $decimalStr = self::toDecimal($charset, $string);

        if ($decimalStr === '0') {
            return "\x0";
        }
        
        $result = '';
        while ($decimalStr !== '0') {
            $result = pack('C', bcmod($decimalStr, 256)) . $result;
            $decimalStr = bcdiv($decimalStr, 256);
        }

        return $result;
    }

    /**
     * Восстановления строки с известным набором символов из бинарной строки
     * @param string $binaryString
     * @return string
     */
    public static function fromBinary($charset, $binaryString, $length)
    {
        $decimalStr = '0';
        for ($i = 0, $lastPos = strlen($binaryString) - 1; $i <= $lastPos; $i++) {
            $decimalStr = bcadd($decimalStr, bcmul(unpack('C', $binaryString[$lastPos - $i])[1], bcpow(256, $i)));
        }

        return self::fromDecimal($charset, $decimalStr, $length);
    }
}