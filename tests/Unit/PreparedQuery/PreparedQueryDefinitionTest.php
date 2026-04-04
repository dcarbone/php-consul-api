<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition;
use DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions;
use DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate;
use DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PreparedQueryDefinitionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $d = new PreparedQueryDefinition();
        self::assertSame('', $d->getID());
        self::assertSame('', $d->getName());
        self::assertSame('', $d->getSession());
        self::assertSame('', $d->getToken());
        self::assertInstanceOf(ServiceQuery::class, $d->getService());
        self::assertInstanceOf(QueryDNSOptions::class, $d->getDNS());
        self::assertInstanceOf(QueryTemplate::class, $d->getTemplate());
    }

    public function testConstructorWithValues(): void
    {
        $d = new PreparedQueryDefinition(
            ID: 'pq-1',
            Name: 'my-query',
            Session: 'sess-1',
            Token: 'tok-1',
        );
        self::assertSame('pq-1', $d->getID());
        self::assertSame('my-query', $d->getName());
        self::assertSame('sess-1', $d->getSession());
        self::assertSame('tok-1', $d->getToken());
    }

    public function testFluentSetters(): void
    {
        $d = new PreparedQueryDefinition();
        $result = $d
            ->setID('i')
            ->setName('n')
            ->setSession('s')
            ->setToken('t')
            ->setService(new ServiceQuery(Service: 'web'))
            ->setDNS(new QueryDNSOptions(TTL: '5s'))
            ->setTemplate(new QueryTemplate(Type: 'tmpl'));
        self::assertSame($d, $result);
        self::assertSame('web', $d->getService()->getService());
    }

    public function testJsonSerialize(): void
    {
        $d = new PreparedQueryDefinition(ID: 'x', Name: 'y');
        $out = $d->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Name);
    }
}

