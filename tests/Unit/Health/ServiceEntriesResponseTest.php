<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse;
use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceEntriesResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new ServiceEntriesResponse();
        self::assertSame([], $r->ServiceEntries);
        self::assertSame([], $r->getValue());
    }

    public function testUnmarshalValue(): void
    {
        $nodeObj = new \stdClass();
        $nodeObj->Node = 'n1';
        $nodeObj->Address = '10.0.0.1';
        $nodeObj->Datacenter = '';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 0;
        $nodeObj->ModifyIndex = 0;
        $nodeObj->Partition = '';
        $nodeObj->PeerName = '';

        $svcObj = new \stdClass();
        $svcObj->ID = 'svc-1';
        $svcObj->Service = 'web';
        $svcObj->Tags = [];
        $svcObj->Meta = new \stdClass();
        $svcObj->Port = 8080;
        $svcObj->Address = '';
        $svcObj->Weights = new \stdClass();
        $svcObj->Weights->Passing = 1;
        $svcObj->Weights->Warning = 1;
        $svcObj->EnableTagOverride = false;
        $svcObj->CreateIndex = 0;
        $svcObj->ModifyIndex = 0;
        $svcObj->Namespace = '';
        $svcObj->Partition = '';
        $svcObj->PeerName = '';

        $entry = new \stdClass();
        $entry->Node = $nodeObj;
        $entry->Service = $svcObj;
        $entry->Checks = [];

        $r = new ServiceEntriesResponse();
        $r->unmarshalValue([$entry]);

        self::assertCount(1, $r->ServiceEntries);
        self::assertCount(1, $r->getValue());
        self::assertInstanceOf(ServiceEntry::class, $r->ServiceEntries[0]);
        self::assertSame('n1', $r->ServiceEntries[0]->Node->getNode());
        self::assertSame('svc-1', $r->ServiceEntries[0]->Service->getID());
    }

    public function testUnmarshalValueResetsArray(): void
    {
        $nodeObj = new \stdClass();
        $nodeObj->Node = 'n';
        $nodeObj->Address = '';
        $nodeObj->Datacenter = '';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 0;
        $nodeObj->ModifyIndex = 0;
        $nodeObj->Partition = '';
        $nodeObj->PeerName = '';

        $svcObj = new \stdClass();
        $svcObj->ID = 's';
        $svcObj->Service = '';
        $svcObj->Tags = [];
        $svcObj->Meta = new \stdClass();
        $svcObj->Port = 0;
        $svcObj->Address = '';
        $svcObj->Weights = new \stdClass();
        $svcObj->Weights->Passing = 1;
        $svcObj->Weights->Warning = 1;
        $svcObj->EnableTagOverride = false;
        $svcObj->CreateIndex = 0;
        $svcObj->ModifyIndex = 0;
        $svcObj->Namespace = '';
        $svcObj->Partition = '';
        $svcObj->PeerName = '';

        $entry = new \stdClass();
        $entry->Node = $nodeObj;
        $entry->Service = $svcObj;
        $entry->Checks = [];

        $r = new ServiceEntriesResponse();
        $r->unmarshalValue([$entry]);
        self::assertCount(1, $r->ServiceEntries);

        $r->unmarshalValue([]);
        self::assertSame([], $r->ServiceEntries);
    }

    public function testUnmarshalValueMultipleEntries(): void
    {
        $entries = [];
        for ($i = 0; $i < 3; $i++) {
            $nodeObj = new \stdClass();
            $nodeObj->Node = "node-{$i}";
            $nodeObj->Address = "10.0.0.{$i}";
            $nodeObj->Datacenter = '';
            $nodeObj->TaggedAddresses = new \stdClass();
            $nodeObj->Meta = new \stdClass();
            $nodeObj->CreateIndex = 0;
            $nodeObj->ModifyIndex = 0;
            $nodeObj->Partition = '';
            $nodeObj->PeerName = '';

            $svcObj = new \stdClass();
            $svcObj->ID = "svc-{$i}";
            $svcObj->Service = "service-{$i}";
            $svcObj->Tags = [];
            $svcObj->Meta = new \stdClass();
            $svcObj->Port = 8080 + $i;
            $svcObj->Address = '';
            $svcObj->Weights = new \stdClass();
            $svcObj->Weights->Passing = 1;
            $svcObj->Weights->Warning = 1;
            $svcObj->EnableTagOverride = false;
            $svcObj->CreateIndex = 0;
            $svcObj->ModifyIndex = 0;
            $svcObj->Namespace = '';
            $svcObj->Partition = '';
            $svcObj->PeerName = '';

            $entry = new \stdClass();
            $entry->Node = $nodeObj;
            $entry->Service = $svcObj;
            $entry->Checks = [];
            $entries[] = $entry;
        }

        $r = new ServiceEntriesResponse();
        $r->unmarshalValue($entries);

        self::assertCount(3, $r->ServiceEntries);
        for ($i = 0; $i < 3; $i++) {
            self::assertInstanceOf(ServiceEntry::class, $r->ServiceEntries[$i]);
            self::assertSame("node-{$i}", $r->ServiceEntries[$i]->Node->getNode());
            self::assertSame("svc-{$i}", $r->ServiceEntries[$i]->Service->getID());
        }
    }
}

