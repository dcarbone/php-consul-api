<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTRequirement;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTClaimVerification;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IntentionJWTRequirementTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new IntentionJWTRequirement();
        self::assertSame('', $r->getName());
        self::assertSame([], $r->getVerifyClaims());
    }

    public function testConstructorWithParams(): void
    {
        $claim = new IntentionJWTClaimVerification(Path: ['iss'], Value: 'val');
        $r = new IntentionJWTRequirement(Name: 'jwt-provider', VerifyClaims: [$claim]);
        self::assertSame('jwt-provider', $r->getName());
        self::assertCount(1, $r->getVerifyClaims());
    }

    public function testFluentSetters(): void
    {
        $r = new IntentionJWTRequirement();
        $result = $r->setName('provider');
        self::assertSame($r, $result);
        self::assertSame('provider', $r->getName());
    }

}
