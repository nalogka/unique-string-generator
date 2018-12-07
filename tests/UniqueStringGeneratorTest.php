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
     * @throws Exception
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
            ['/^\d{4}$/', Generator::DEC, 4],
            ['/^([A-Z]){10}$/', Generator::ALPHA_U, 10],
            ['/^([a-z]){3}$/', Generator::ALPHA_L, 3],
            ['/^([a-z0-9]){15}$/', Generator::ALPHA_U . Generator::DEC, 15],
            ['/^([A-Za-z0-9]){15}$/', Generator::ALPHA_U . Generator::ALPHA_L . Generator::DEC, 15],
            ['/^([a-f0-9]){16}$/', Generator::HEX_L, 16],
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
            [Generator::DEC, '0', '0'],
            [Generator::DEC, '10', '10'],
            [Generator::DEC, '564', '564'],
            [Generator::ALPHA_U, 'A', '0'],
            [Generator::ALPHA_U, 'Z', '25'],
            [Generator::ALPHA_U, 'YO', '638'],
            [Generator::ALPHA_L, 'a', '0'],
            [Generator::ALPHA_L, 'z', '25'],
            [Generator::ALPHA_L, 'hi', '190'],
            [Generator::DEC_ALPHA_L_U, '9', '9',],
            [Generator::DEC_ALPHA_L_U, 'a', '10'],
            [Generator::DEC_ALPHA_L_U, 'Z', '61'],
            [Generator::DEC_ALPHA_L_U, '10', '62'],
            [Generator::DEC_ALPHA_L_U, '11', '63'],
            [Generator::HEX_U, '0', '0'],
            [Generator::HEX_U, 'F', '15'],
            [Generator::HEX_U, 'FF', '255'],
            [Generator::HEX_U, 'FFFF', '65535'],
        ];
    }
}