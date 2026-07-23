<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLAuthMethodListEntry;
use DCarbone\PHPConsulAPI\ACL\ACLAuthMethodListEntryQueryResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLAuthMethodListEntryQueryResponseTest extends TestCase
{
    public function testUnmarshalValueResetsStateOnInvalidDecodedValue(): void
    {
        $entry = new \stdClass();
        $entry->Name = 'method-1';
        $entry->Type = 'jwt';
        $entry->DisplayName = 'Method One';
        $entry->Description = '';
        $entry->MaxTokenTTL = '0s';
        $entry->TokenLocality = 'local';
        $entry->CreateIndex = 1;
        $entry->ModifyIndex = 1;
        $entry->Namespace = '';
        $entry->Partition = '';

        $resp = new ACLAuthMethodListEntryQueryResponse();
        $resp->unmarshalValue([$entry]);
        self::assertCount(1, $resp->getValue());
        self::assertInstanceOf(ACLAuthMethodListEntry::class, $resp->getValue()[0]);

        $resp->unmarshalValue(null);
        self::assertSame([], $resp->getValue());
    }
}

