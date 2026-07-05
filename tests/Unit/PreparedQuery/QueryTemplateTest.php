<?php

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class QueryTemplateTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $t = new QueryTemplate();
        self::assertSame('', $t->getType());
        self::assertSame('', $t->Type);
        self::assertSame('', $t->getRegexp());
        self::assertSame('', $t->Regexp);
    }

    public function testConstructorWithValues(): void
    {
        $t = new QueryTemplate(Type: 'name_prefix_match', Regexp: '^geo-(.+?)$');
        self::assertSame('name_prefix_match', $t->getType());
        self::assertSame('name_prefix_match', $t->Type);
        self::assertSame('^geo-(.+?)$', $t->getRegexp());
        self::assertSame('^geo-(.+?)$', $t->Regexp);
    }

    public function testFluentSetters(): void
    {
        $t = new QueryTemplate();
        $result = $t->setType('t')->setRegexp('r');
        self::assertSame($t, $result);
        self::assertSame('t', $t->Type);
        self::assertSame('r', $t->Regexp);
    }

    public function testJsonSerialize(): void
    {
        $t = new QueryTemplate(Type: 'test');
        $out = $t->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('test', $out->Type);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Type = 'name_prefix_match';
        $decoded->Regexp = '^test-(.+)$';
        $t = QueryTemplate::jsonUnserialize($decoded);
        self::assertSame('name_prefix_match', $t->getType());
        self::assertSame('name_prefix_match', $t->Type);
        self::assertSame('^test-(.+)$', $t->getRegexp());
        self::assertSame('^test-(.+)$', $t->Regexp);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new QueryTemplate(Type: 'x', Regexp: 'y');
        $restored = QueryTemplate::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getType(), $restored->getType());
        self::assertSame($original->getRegexp(), $restored->getRegexp());
    }
}

