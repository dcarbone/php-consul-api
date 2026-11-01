<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use DCarbone\PHPConsulAPI\ACL\ACLToken;
use DCarbone\PHPConsulAPI\ACL\ACLTokenExpanded;
use DCarbone\PHPConsulAPI\ACL\OIDCAuthMethodConfig;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset;
use DCarbone\PHPConsulAPI\PHPLib\Values;
use PHPUnit\Framework\TestCase;

/**
 * Numeric-string map keys become int keys when cast to array; these must still hydrate via string-typed setters.
 */
final class NumericKeyTest extends TestCase
{
    public function testNodeTaggedAddressesAndMetaFromDecoded(): void
    {
        $n = Node::jsonUnserialize(decoded: json_decode('{"TaggedAddresses":{"123":"10.0.0.1"},"Meta":{"456":"v"}}', false));
        self::assertSame([123 => '10.0.0.1'], $n->getTaggedAddresses());
        self::assertSame('10.0.0.1', $n->getTaggedAddresses()['123']);
        self::assertSame([456 => 'v'], $n->getMeta());
    }

    public function testTaggedAddressesAndMetaFromArray(): void
    {
        $n = new Node();
        $n->setTaggedAddresses(TaggedAddresses: ['123' => '10.0.0.1']);
        $n->setMeta(Meta: ['456' => 'v']);
        self::assertSame([123 => '10.0.0.1'], $n->getTaggedAddresses());
        self::assertSame([456 => 'v'], $n->getMeta());
    }

    public function testAgentServiceRegistrationTaggedAddresses(): void
    {
        $r = new AgentServiceRegistration(TaggedAddresses: ['123' => new ServiceAddress(Address: '10.0.0.1', Port: 80)]);
        self::assertSame('10.0.0.1', $r->getTaggedAddresses()['123']->Address);
        $r->setTaggedAddresses(TaggedAddresses: ['456' => new ServiceAddress(Address: '10.0.0.2', Port: 81)]);
        self::assertSame('10.0.0.2', $r->getTaggedAddresses()['456']->Address);
        $r = AgentServiceRegistration::jsonUnserialize(decoded: json_decode('{"TaggedAddresses":{"789":{"Address":"10.0.0.3","Port":82}}}', false));
        self::assertSame('10.0.0.3', $r->getTaggedAddresses()['789']->Address);
    }

    public function testCatalogServiceKeys(): void
    {
        $s = CatalogService::jsonUnserialize(
            decoded: json_decode(
                '{"TaggedAddresses":{"1":"10.0.0.1"},"NodeMeta":{"2":"m"},"ServiceMeta":{"3":"sm"},"ServiceTaggedAddresses":{"4":{"Address":"10.0.0.4","Port":4}}}',
                false,
            ),
        );
        self::assertSame([1 => '10.0.0.1'], $s->getTaggedAddresses());
        self::assertSame([2 => 'm'], $s->getNodeMeta());
        self::assertSame([3 => 'sm'], $s->getServiceMeta());
        self::assertSame('10.0.0.4', $s->getServiceTaggedAddresses()['4']->Address);

        $s->setServiceTaggedAddresses(ServiceTaggedAddresses: ['5' => new ServiceAddress(Address: '10.0.0.5', Port: 5)]);
        self::assertSame('10.0.0.5', $s->getServiceTaggedAddresses()['5']->Address);
        self::assertSame([1 => '10.0.0.1'], $s->getTaggedAddresses());

        $s->setServiceTaggedAddresses(ServiceTaggedAddresses: null);
        self::assertNull($s->getServiceTaggedAddresses());
        self::assertSame([1 => '10.0.0.1'], $s->getTaggedAddresses());
    }

    public function testServiceResolverConfigEntryKeys(): void
    {
        $e = ServiceResolverConfigEntry::jsonUnserialize(
            decoded: json_decode('{"Kind":"service-resolver","Name":"web","Subsets":{"1":{"Filter":"f"}},"Failover":{"2":{"Service":"s"}}}', false),
        );
        self::assertSame('f', $e->getSubsets()['1']->Filter);
        self::assertSame('s', $e->getFailover()['2']->Service);

        $e->setSubsets(Subsets: ['3' => new ServiceResolverSubset(Filter: 'g')]);
        $e->setFailover(Failover: ['4' => new ServiceResolverFailover(Service: 't')]);
        self::assertSame('g', $e->getSubsets()['3']->Filter);
        self::assertSame('t', $e->getFailover()['4']->Service);
    }

    public function testOIDCAuthMethodConfigClaimMappings(): void
    {
        $c = OIDCAuthMethodConfig::jsonUnserialize(decoded: json_decode('{"ClaimMappings":{"1":"a"},"ListClaimMappings":{"2":"b"}}', false));
        self::assertSame([1 => 'a'], $c->getClaimMappings());
        self::assertSame([2 => 'b'], $c->getListClaimMappings());
    }

    public function testEnvoyExtensionArguments(): void
    {
        $e = EnvoyExtension::jsonUnserialize(decoded: json_decode('{"Name":"x","Arguments":{"1":"a"}}', false));
        self::assertSame([1 => 'a'], $e->getArguments());
    }

    public function testACLTokenNumericTopLevelKey(): void
    {
        $t = ACLToken::jsonUnserialize(decoded: json_decode('{"AccessorID":"a","123":"x"}', false));
        self::assertSame('a', $t->AccessorID);
        $te = ACLTokenExpanded::jsonUnserialize(decoded: json_decode('{"AccessorID":"b","123":"x"}', false));
        self::assertSame('b', $te->AccessorID);
    }

    public function testValuesFromArray(): void
    {
        $v = Values::fromArray(values: ['123' => 'a']);
        self::assertSame('a', $v->get(key: '123'));
    }
}
