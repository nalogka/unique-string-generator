<?php

use PHPUnit\Framework\TestCase;
use Nalogka\UniqueStringGenerator\Generator;
use Nalogka\UniqueStringGenerator\Converter;

class GeneratorTest extends TestCase
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
            ['/^([a-z0-9]){15}$/', Generator::ALPHA_L . Generator::DEC, 15],
            ['/^([A-Za-z0-9]){15}$/', Generator::ALPHA_U . Generator::ALPHA_L . Generator::DEC, 15],
            ['/^([a-f0-9]){16}$/', Generator::HEX_L, 16],
        ];
    }
}