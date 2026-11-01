<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use Brick\Math\BigInteger;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function DCarbone\PHPConsulAPI\PHPLib\uint64;

use const DCarbone\PHPConsulAPI\PHPLib\UINT64_MAX;

final class Uint64Test extends TestCase
{
    /**
     * @return array<string,array{int|string|BigInteger,string}>
     */
    public static function validValues(): array
    {
        return [
            'int-zero' => [0, '0'],
            'int-one' => [1, '1'],
            'int-max' => [PHP_INT_MAX, '9223372036854775807'],
            'string-zero' => ['0', '0'],
            'string-leading-zeros' => ['007', '7'],
            'string-int-max-plus-one' => ['9223372036854775808', '9223372036854775808'],
            'string-uint64-max' => ['18446744073709551615', '18446744073709551615'],
            'big-integer' => [BigInteger::of('18446744073709551614'), '18446744073709551614'],
        ];
    }

    #[DataProvider('validValues')]
    public function testValidValues(int|string|BigInteger $in, string $expected): void
    {
        $v = uint64($in);
        self::assertSame($expected, $v->toString());
    }

    public function testBigIntegerInstanceIsReturnedAsIs(): void
    {
        $b = BigInteger::of(42);
        self::assertSame($b, uint64($b));
    }

    public function testMaxConstant(): void
    {
        self::assertSame('18446744073709551615', UINT64_MAX);
    }

    /**
     * @return array<string,array{int|string|BigInteger}>
     */
    public static function invalidValues(): array
    {
        return [
            'negative-int' => [-1],
            'int-min' => [PHP_INT_MIN],
            'negative-string' => ['-1'],
            'uint64-max-plus-one' => ['18446744073709551616'],
            'negative-big-integer' => [BigInteger::of(-5)],
            'empty-string' => [''],
            'non-numeric' => ['abc'],
            'fractional' => ['1.5'],
            'decimal-point' => ['1.0'],
            'exponent' => ['1e3'],
            'huge-exponent' => ['1e1000000000'],
            'fraction' => ['4/2'],
            'too-many-digits' => [str_repeat('9', 1000)],
            'padded-past-max-digits' => ['000000000000000000001'],
            'whitespace' => [' 5'],
        ];
    }

    #[DataProvider('invalidValues')]
    public function testInvalidValues(int|string|BigInteger $in): void
    {
        $this->expectException(\InvalidArgumentException::class);
        uint64($in);
    }
}
