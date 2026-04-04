<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTClaimVerification;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IntentionJWTClaimVerificationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $v = new IntentionJWTClaimVerification();
        self::assertSame([], $v->getPath());
        self::assertSame('', $v->getValue());
    }

    public function testConstructorWithParams(): void
    {
        $v = new IntentionJWTClaimVerification(Path: ['iss'], Value: 'my-issuer');
        self::assertSame(['iss'], $v->getPath());
        self::assertSame('my-issuer', $v->getValue());
    }

    public function testFluentSetters(): void
    {
        $v = new IntentionJWTClaimVerification();
        $result = $v->setPath('sub')->setValue('user');
        self::assertSame($v, $result);
        self::assertSame(['sub'], $v->getPath());
        self::assertSame('user', $v->getValue());
    }

}
