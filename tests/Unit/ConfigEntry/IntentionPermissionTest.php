<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionPermission;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionAction;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPPermission;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTRequirement;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IntentionPermissionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new IntentionPermission();
        self::assertSame(IntentionAction::UNDEFINED, $p->getAction());
        self::assertNull($p->getHTTP());
        self::assertNull($p->getJWT());
    }

    public function testConstructorWithParams(): void
    {
        $http = new IntentionHTTPPermission(PathPrefix: '/api');
        $jwt = new IntentionJWTRequirement(Name: 'jwt');
        $p = new IntentionPermission(
            Action: IntentionAction::Allow,
            HTTP: $http,
            JWT: $jwt,
        );
        self::assertSame(IntentionAction::Allow, $p->getAction());
        self::assertSame($http, $p->getHTTP());
        self::assertSame($jwt, $p->getJWT());
    }

    public function testFluentSetters(): void
    {
        $http = new IntentionHTTPPermission(PathPrefix: '/v1');
        $p = new IntentionPermission();
        $result = $p->setAction(IntentionAction::Deny)
            ->setHTTP($http);
        self::assertSame($p, $result);
        self::assertSame(IntentionAction::Deny, $p->getAction());
        self::assertSame($http, $p->getHTTP());
    }

    public function testConstructorWithEnumAsString(): void
    {
        $p = new IntentionPermission(Action: 'allow');
        self::assertSame(IntentionAction::Allow, $p->getAction());
    }
}
