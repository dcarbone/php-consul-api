<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLAuthMethod;
use DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLAuthMethodTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new ACLAuthMethod();
        self::assertSame('', $m->getName());
        self::assertSame('', $m->getType());
        self::assertSame('', $m->getDisplayName());
        self::assertSame('', $m->getDescription());
        self::assertSame(0, $m->getMaxTokenTTL()->Nanoseconds());
        self::assertSame('', $m->getTokenLocality());
        self::assertSame([], $m->getConfig());
        self::assertSame(0, $m->getCreateIndex());
        self::assertSame(0, $m->getModifyIndex());
        self::assertSame([], $m->getNamespaceRules());
        self::assertSame('', $m->getNamespace());
        self::assertSame('', $m->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $rule = new ACLAuthMethodNamespaceRule(Selector: 'sel', BindNamespace: 'ns');
        $m = new ACLAuthMethod(
            Name: 'auth-1',
            Type: 'kubernetes',
            DisplayName: 'K8s Auth',
            Description: 'desc',
            MaxTokenTTL: '5m',
            TokenLocality: 'local',
            Config: ['Host' => 'https://k8s.example.com'],
            CreateIndex: 1,
            ModifyIndex: 2,
            NamespaceRules: [$rule],
            Namespace: 'ns',
            Partition: 'pt',
        );
        self::assertSame('auth-1', $m->getName());
        self::assertSame('kubernetes', $m->getType());
        self::assertSame('K8s Auth', $m->getDisplayName());
        self::assertCount(1, $m->getNamespaceRules());
        self::assertSame('ns', $m->getNamespace());
    }

    public function testFluentSetters(): void
    {
        $m = new ACLAuthMethod();
        $result = $m->setName('n')->setType('t')->setDisplayName('d')->setDescription('desc')
            ->setMaxTokenTTL('10m')->setTokenLocality('global')
            ->setConfig(['key' => 'val'])->setCreateIndex(1)->setModifyIndex(2)
            ->setNamespace('ns')->setPartition('pt');
        self::assertSame($m, $result);
        self::assertSame('n', $m->getName());
        self::assertSame(['key' => 'val'], $m->getConfig());
    }

    public function testAddNamespaceRule(): void
    {
        $m = new ACLAuthMethod();
        $rule = new ACLAuthMethodNamespaceRule(Selector: 's', BindNamespace: 'bn');
        $m->addNamespaceRule($rule);
        self::assertCount(1, $m->getNamespaceRules());
        self::assertSame($rule, $m->getNamespaceRules()[0]);
    }

    public function testJsonSerialize(): void
    {
        $m = new ACLAuthMethod(Name: 'auth', Type: 'jwt', Config: ['key' => 'val']);
        $out = $m->jsonSerialize();
        self::assertSame('auth', $out->Name);
        self::assertSame(['key' => 'val'], $out->Config);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $m = new ACLAuthMethod(Name: 'auth', Type: 'jwt');
        $out = $m->jsonSerialize();
        self::assertObjectNotHasProperty('DisplayName', $out);
        self::assertObjectNotHasProperty('Description', $out);
        self::assertObjectNotHasProperty('MaxTokenTTL', $out);
        self::assertObjectNotHasProperty('TokenLocality', $out);
        self::assertObjectNotHasProperty('NamespaceRules', $out);
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'auth-u';
        $d->Type = 'kubernetes';
        $d->DisplayName = 'display';
        $d->Description = 'desc';
        $d->MaxTokenTTL = '5m0s';
        $d->TokenLocality = 'local';
        $d->Config = new \stdClass();
        $d->Config->Host = 'https://k8s.example.com';
        $d->CreateIndex = 10;
        $d->ModifyIndex = 20;
        $nsRule = new \stdClass();
        $nsRule->Selector = 'sel';
        $nsRule->BindNamespace = 'bn';
        $d->NamespaceRules = [$nsRule];
        $m = ACLAuthMethod::jsonUnserialize($d);
        self::assertSame('auth-u', $m->getName());
        self::assertSame('kubernetes', $m->getType());
        self::assertCount(1, $m->getNamespaceRules());
        self::assertInstanceOf(ACLAuthMethodNamespaceRule::class, $m->getNamespaceRules()[0]);
    }
}

