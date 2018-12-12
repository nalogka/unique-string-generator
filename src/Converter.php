<?php

namespace Nalogka\UniqueStringGenerator;

class Converter
{
    /**
     * Кодирование (упаковка) строки с известным набором символов в бинарную строку
     * @param string $charset Набор символов алфавита, используемый в кодируемой строке 
     * @param string $string  Строка для кодирования
     * @return string Бинарная строка
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
     * @param string $charset Алфавит, используемый в востанавливаемой строке
     * @param string $decimalStr Строка в сыром (бинарном, хранимом в слое персистентности) виде. 
     * @param integer $length Длина восстанавливаемой строки. Если восстановленная строка меньше этого аргумента, то она дополняется слева первым символом алфавита.
     * @return string Строка из символов исходного алфавита
     */
    public static function fromBinary($charset, $binaryString, $length)
    {
        $decimalStr = '0';
        for ($i = 0, $lastPos = strlen($binaryString) - 1; $i <= $lastPos; $i++) {
            $decimalStr = bcadd($decimalStr, bcmul(unpack('C', $binaryString[$lastPos - $i])[1], bcpow(256, $i)));
        }

        return self::fromDecimal($charset, $decimalStr, $length);
    }

    /**
     * Кодирование строки с указанным алфавитом в строку, состоящую из цифр [0-9]
     * @param string $charset Набор символов алфавита, используемый в кодируемой строке 
     * @param string $string  Строка для кодирования 
     * @return string Строка из цифр [0-9]
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
     * Восстановление строки с указанным алфавитом из строки, состоящей из цифр [0-9]
     * @param string $charset Алфавит, используемый в востанавливаемой строке
     * @param string $decimalStr Строка из цифр [0-9]
     * @param integer $length Длина восстанавливаемой строки. Если восстановленная строка меньше этого аргумента, то она дополняется слева первым символом алфавита.
     * @return string Строка из символов исходного алфавита
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
}