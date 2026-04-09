<?php

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
        self::assertSame('', $d->ID);
        self::assertSame('', $d->getName());
        self::assertSame('', $d->Name);
        self::assertSame('', $d->getSession());
        self::assertSame('', $d->Session);
        self::assertSame('', $d->getToken());
        self::assertSame('', $d->Token);
        self::assertInstanceOf(ServiceQuery::class, $d->getService());
        self::assertInstanceOf(ServiceQuery::class, $d->Service);
        self::assertInstanceOf(QueryDNSOptions::class, $d->getDNS());
        self::assertInstanceOf(QueryDNSOptions::class, $d->DNS);
        self::assertInstanceOf(QueryTemplate::class, $d->getTemplate());
        self::assertInstanceOf(QueryTemplate::class, $d->Template);
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
        self::assertSame('pq-1', $d->ID);
        self::assertSame('my-query', $d->getName());
        self::assertSame('my-query', $d->Name);
        self::assertSame('sess-1', $d->getSession());
        self::assertSame('sess-1', $d->Session);
        self::assertSame('tok-1', $d->getToken());
        self::assertSame('tok-1', $d->Token);
    }

    public function testFluentSetters(): void
    {
        $d = new PreparedQueryDefinition();
        $svc = new ServiceQuery(Service: 'web');
        $dns = new QueryDNSOptions(TTL: '5s');
        $tmpl = new QueryTemplate(Type: 'tmpl');
        $result = $d
            ->setID('i')
            ->setName('n')
            ->setSession('s')
            ->setToken('t')
            ->setService($svc)
            ->setDNS($dns)
            ->setTemplate($tmpl);
        self::assertSame($d, $result);
        self::assertSame('i', $d->ID);
        self::assertSame('n', $d->Name);
        self::assertSame('s', $d->Session);
        self::assertSame('t', $d->Token);
        self::assertSame($svc, $d->Service);
        self::assertSame('web', $d->getService()->getService());
        self::assertSame($dns, $d->DNS);
        self::assertSame($tmpl, $d->Template);
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

