<?php

use PHPUnit\Framework\TestCase;
use Nalogka\UniqueStringGenerator\Generator;
use Nalogka\UniqueStringGenerator\Converter;

class ConverterTest extends TestCase
{
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
            ['ABCD', 'B', '1'],
            ['ABCD', 'DB', '13'],
            ['ABC', 'AAACCCCB', '241'],
        ];
    }

    /**
     * @param $base
     * @param $input
     * @param $expectedOutput
     * @dataProvider fromDecimalProvider
     */
    public function testFromDecimal($base, $input, $expectedOutput)
    {
        $length = strlen($expectedOutput);
        $this->assertEquals($expectedOutput, Converter::fromDecimal($base, $input, $length));
    }

    public function fromDecimalProvider()
    {
        return [
            [Generator::DEC, '10', '10'],
            [Generator::ALPHA_U, '0', 'A'],
            [Generator::ALPHA_U, '25', 'Z'],
            [Generator::ALPHA_U, '26', 'BA'],
            ['ABC', '241', 'AAACCCCB'],
        ];
    }

    /**
     * @param $input
     * @param $expectedOutput
     * @dataProvider toBinaryProvider
     */
    public function testToBinary($base, $input, $expectedOutput)
    {
        $this->assertSame($expectedOutput, unpack('H*', Converter::toBinary($base, $input))[1]);
    }

    public function toBinaryProvider()
    {
        return [
            [Generator::DEC_ALPHA_L_U, '0', /* hex */ '00'],
            [Generator::DEC_ALPHA_L_U, 'M', /* hex */ '30'],
            [Generator::DEC_ALPHA_L_U, 'co', /* hex */ '0300'],
            [Generator::DEC_ALPHA_L_U, 'bUI', /* hex */ 'b2e8'],
            [Generator::DEC_ALPHA_L_U, 'bUI6zOLZTrh', /* hex */ '8ac7230489e7ffff'],
            ['ABCD', 'DB', /* hex */ '0d'],
            ['ABC', 'ACAAB', /* hex */ '37'],
        ];
    }

    /**
     * @param $input
     * @param $expectedOutput
     * @dataProvider fromBinaryProvider
     */
    public function testFromBinary($base, $input, $expectedOutput)
    {
        $length = strlen($expectedOutput);
        $this->assertSame($expectedOutput, Converter::fromBinary($base, pack('H*', $input), $length));
    }

    public function fromBinaryProvider()
    {
        return [
            [Generator::DEC_ALPHA_L_U, /* hex */ '00', '0'],
            [Generator::DEC_ALPHA_L_U, /* hex */ '30', 'M'],
            [Generator::DEC_ALPHA_L_U, /* hex */ '0300', 'co'],
            [Generator::DEC_ALPHA_L_U, /* hex */ 'b2e8', 'bUI'],
            [Generator::DEC_ALPHA_L_U, /* hex */ '8ac7230489e7ffff', 'bUI6zOLZTrh'],
            [Generator::HEX_L, /* hex */ '8ac7230489e7ffff', '8ac7230489e7ffff'],
            ['ABCD', /* hex */ '0d', 'DB'],
            ['ABC', /* hex */ '37', 'ACAAB'],
        ];
    }
}