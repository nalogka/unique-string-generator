<?php

use PHPUnit\Framework\TestCase;
use Nalogka\UniqueStringGenerator\Generator;
use Nalogka\UniqueStringGenerator\Converter;

class UniqueStringGeneratorTest extends TestCase
{
    /**
     * @param $regexp
     * @param $string
     * @param $length
     * @dataProvider stringProvider
     */
    public function testGenerateMethod($regexp, $base, $length): void
    {
        $this->assertRegExp($regexp, Generator::generate($base, $length));
    }

    /**
     * Генерируем провайдер в виде коллекции массивов вида:
     * [ Регулярное выражерние под которое должен подходить результат, Шаблон строки, Количество повторений шаблона ]
     * @return array
     */
    public function stringProvider()
    {
        return [
            ['/^\d{4}$/', Generator::CHARSET_NUM, 4],
            ['/^([A-Z\D]){10}$/', Generator::CHARSET_UP, 10],
            ['/^([a-z\D]){3}$/', Generator::CHARSET_LOW, 3],
            ['/^([a-z0-9]){15}$/', Generator::CHARSET_LOW . Generator::CHARSET_NUM, 15],
            ['/^([A-Za-z0-9]){15}$/', Generator::CHARSET_UP . Generator::CHARSET_LOW . Generator::CHARSET_NUM, 15],
            ['/^([a-z0-9]){16}$/', Generator::CHARSET_16_LOW, 16],
        ];
    }

    /**
     * @dataProvider toDecimalProvider
     */
    public function testToDecimal($base, $input, $expectedOutput)
    {
        $this->assertEquals($expectedOutput, Converter::toDecimal($base, $input));
    }

    public function toDecimalProvider()
    {
        return [
            [Generator::CHARSET_NUM, '0', '0'],
            [Generator::CHARSET_NUM, '10', '10'],
            [Generator::CHARSET_NUM, '564', '564'],
            [Generator::CHARSET_UP, 'A', '0'],
            [Generator::CHARSET_UP, 'Z', '25'],
            [Generator::CHARSET_UP, 'YO', '638'],
            [Generator::CHARSET_LOW, 'a', '0'],
            [Generator::CHARSET_LOW, 'z', '25'],
            [Generator::CHARSET_LOW, 'hi', '190'],
            [Generator::CHARSET_62, '9', '9',],
            [Generator::CHARSET_62, 'a', '10'],
            [Generator::CHARSET_62, 'Z', '61'],
            [Generator::CHARSET_62, '10', '62'],
            [Generator::CHARSET_62, '11', '63'],
            [Generator::CHARSET_16_UP, '0', '0'],
            [Generator::CHARSET_16_UP, 'F', '15'],
            [Generator::CHARSET_16_UP, 'FF', '255'],
            [Generator::CHARSET_16_UP, 'FFFF', '65535'],
        ];
    }
}