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
    public static function fromBinary($charset, $binaryString)
    {
        $decimalStr = '0';
        for ($i = 0, $lastPos = strlen($binaryString) - 1; $i <= $lastPos; $i++) {
            $decimalStr = bcadd($decimalStr, bcmul(unpack('C', $binaryString[$lastPos - $i])[1], bcpow(256, $i)));
        }

        return self::fromDecimal($charset, $decimalStr);
    }
}