<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicyResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTemplatedPolicyResponseTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new ACLTemplatedPolicyResponse();
        self::assertSame('', $r->getTemplateName());
        self::assertSame('', $r->getSchema());
        self::assertSame('', $r->getTemplate());
        self::assertSame('', $r->getDescription());
    }

    public function testConstructorWithParams(): void
    {
        $r = new ACLTemplatedPolicyResponse(
            TemplateName: 'builtin/service',
            Schema: '{"type":"object"}',
            Template: 'tmpl-body',
            Description: 'A service template',
        );
        self::assertSame('builtin/service', $r->getTemplateName());
        self::assertSame('builtin/service', $r->TemplateName);
        self::assertSame('{"type":"object"}', $r->getSchema());
        self::assertSame('tmpl-body', $r->getTemplate());
        self::assertSame('A service template', $r->getDescription());
    }

    public function testFluentSetters(): void
    {
        $r = new ACLTemplatedPolicyResponse();
        $result = $r->setTemplateName('t')->setSchema('s')->setTemplate('b')->setDescription('d');
        self::assertSame($r, $result);
        self::assertSame('t', $r->getTemplateName());
        self::assertSame('s', $r->getSchema());
        self::assertSame('b', $r->getTemplate());
        self::assertSame('d', $r->getDescription());
    }

    public function testJsonSerialize(): void
    {
        $r = new ACLTemplatedPolicyResponse(TemplateName: 'tn', Schema: 'sc', Template: 'tp', Description: 'ds');
        $out = $r->jsonSerialize();
        self::assertSame('tn', $out->TemplateName);
        self::assertSame('sc', $out->Schema);
        self::assertSame('tp', $out->Template);
        self::assertSame('ds', $out->Description);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->TemplateName = 'builtin/node';
        $d->Schema = 'schema';
        $d->Template = 'template';
        $d->Description = 'desc';
        $r = ACLTemplatedPolicyResponse::jsonUnserialize($d);
        self::assertSame('builtin/node', $r->getTemplateName());
        self::assertSame('schema', $r->getSchema());
        self::assertSame('template', $r->getTemplate());
        self::assertSame('desc', $r->getDescription());
    }
}

