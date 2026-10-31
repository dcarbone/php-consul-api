<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Txn\KVOp;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function DCarbone\PHPConsulAPI\PHPLib\encode_json;

final class EncodeJsonTest extends TestCase
{
    /**
     * @return array<string,array{mixed}>
     */
    public static function plainValues(): array
    {
        $obj = new \stdClass();
        $obj->a = 1;
        $obj->b = ['x', 'y'];
        $obj->c = new \stdClass();
        $obj->d = ['k' => 'v'];
        return [
            'null' => [null],
            'true' => [true],
            'int' => [42],
            'float' => [1.5],
            'string' => ['a "quoted" / string 18446744073709551615'],
            'empty-list' => [[]],
            'list' => [[1, 'two', null]],
            'map' => [['a' => 1, 'b' => [2, 3]]],
            'non-sequential-int-keys' => [[1 => 'a', 2 => 'b']],
            'empty-object' => [new \stdClass()],
            'nested-object' => [$obj],
            'enum' => [KVOp::KVSet],
        ];
    }

    #[DataProvider('plainValues')]
    public function testMatchesJsonEncode(mixed $value): void
    {
        self::assertSame(json_encode($value, JSON_UNESCAPED_SLASHES), encode_json(value: $value, flags: JSON_UNESCAPED_SLASHES));
    }

    public function testBigIntegerEncodedAsNumber(): void
    {
        self::assertSame('18446744073709551615', encode_json(value: BigInteger::of('18446744073709551615')));
    }

    public function testNestedBigIntegers(): void
    {
        $obj = new \stdClass();
        $obj->Index = BigInteger::of('9223372036854775808');
        $obj->List = [BigInteger::of(1), BigInteger::of('18446744073709551615')];
        self::assertSame('{"Index":9223372036854775808,"List":[1,18446744073709551615]}', encode_json(value: $obj));
    }

    public function testJsonSerializableIsExpanded(): void
    {
        $p = new KVPair(Key: 'k', Flags: '18446744073709551615', Value: 'v');
        $out = encode_json(value: $p);
        self::assertStringContainsString('"Flags":18446744073709551615', $out);
        self::assertStringContainsString('"CreateIndex":0', $out);
        $decoded = json_decode($out, false, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
        self::assertSame('18446744073709551615', $decoded->Flags);
    }

    public function testForceObject(): void
    {
        self::assertSame('{"0":"a","1":"b"}', encode_json(value: ['a', 'b'], flags: JSON_FORCE_OBJECT));
    }

    public function testDepthExceeded(): void
    {
        $this->expectException(\JsonException::class);
        encode_json(value: [[[1]]], depth: 2);
    }

    public function testInvalidUtf8Throws(): void
    {
        $this->expectException(\JsonException::class);
        encode_json(value: ["\xB1\x31"]);
    }
}
