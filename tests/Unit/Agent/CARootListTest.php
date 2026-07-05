<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\CARoot;
use DCarbone\PHPConsulAPI\Agent\CARootList;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CARootListTest extends TestCase
{
    public function testConstructorAndSetters(): void
    {
        $root1 = new CARoot(ID: 'id-1');
        $root2 = new CARoot(ID: 'id-2');
        $list = new CARootList(ActiveRootID: 'id-1', TrustDomain: 'consul', Roots: [$root1]);

        self::assertSame('id-1', $list->getActiveRootID());
        self::assertSame('consul', $list->TrustDomain);
        self::assertCount(1, $list->getRoots());

        $result = $list->setActiveRootID('id-2')->setTrustDomain('consul.local')->setRoots($root1, $root2);
        self::assertSame($list, $result);
        self::assertSame('id-2', $list->ActiveRootID);
        self::assertSame('consul.local', $list->getTrustDomain());
        self::assertCount(2, $list->Roots);
    }

    public function testJsonUnserializeAndSerialize(): void
    {
        $obj = new \stdClass();
        $obj->ActiveRootID = 'id-1';
        $obj->TrustDomain = 'consul';
        $obj->Roots = [];
        $root = new \stdClass();
        $root->ID = 'id-1';
        $root->Name = 'root-1';
        $root->RootCert = 'pem';
        $root->Active = true;
        $root->CreateIndex = 1;
        $root->ModifyIndex = 2;
        $obj->Roots[] = $root;

        $list = CARootList::jsonUnserialize($obj);
        self::assertCount(1, $list->Roots);
        self::assertSame('id-1', $list->Roots[0]->ID);

        $out = $list->jsonSerialize();
        self::assertSame('id-1', $out->ActiveRootID);
        self::assertCount(1, $out->Roots);
    }
}
