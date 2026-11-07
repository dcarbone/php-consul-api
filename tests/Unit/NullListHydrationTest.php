<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\ACL\ACLPolicy;
use DCarbone\PHPConsulAPI\ACL\ACLPolicyListEntry;
use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy;
use DCarbone\PHPConsulAPI\ACL\ACLTokenExpanded;
use DCarbone\PHPConsulAPI\ACL\OIDCAuthMethodConfig;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Catalog\GatewayService;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshDirectionalTLSConfig;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Go encodes nil slices as JSON null; hydration must treat them as empty lists.
 *
 * @internal
 */
final class NullListHydrationTest extends TestCase
{
    /**
     * @return array<string, array{class-string, string}>
     */
    public static function nullListFields(): array
    {
        return [
            'AgentServiceRegistration.Tags' => [AgentServiceRegistration::class, 'Tags'],
            'AgentService.Tags' => [AgentService::class, 'Tags'],
            'GatewayService.Hosts' => [GatewayService::class, 'Hosts'],
            'GatewayTLSConfig.CipherSuites' => [GatewayTLSConfig::class, 'CipherSuites'],
            'MeshDirectionalTLSConfig.CipherSuites' => [MeshDirectionalTLSConfig::class, 'CipherSuites'],
            'HealthCheck.ServiceTags' => [HealthCheck::class, 'ServiceTags'],
            'ACLPolicyListEntry.Datacenters' => [ACLPolicyListEntry::class, 'Datacenters'],
            'ACLServiceIdentity.Datacenters' => [ACLServiceIdentity::class, 'Datacenters'],
            'ACLTemplatedPolicy.Datacenters' => [ACLTemplatedPolicy::class, 'Datacenters'],
            'ACLTokenExpanded.NamespaceDefaultPolicyIDs' => [ACLTokenExpanded::class, 'NamespaceDefaultPolicyIDs'],
            'ACLTokenExpanded.NamespaceDefaultRoleIDs' => [ACLTokenExpanded::class, 'NamespaceDefaultRoleIDs'],
            'ACLPolicy.Datacenters' => [ACLPolicy::class, 'Datacenters'],
            'OIDCAuthMethodConfig.JWTSupportedAlgs' => [OIDCAuthMethodConfig::class, 'JWTSupportedAlgs'],
            'OIDCAuthMethodConfig.BoundAudiences' => [OIDCAuthMethodConfig::class, 'BoundAudiences'],
            'OIDCAuthMethodConfig.OIDCScopes' => [OIDCAuthMethodConfig::class, 'OIDCScopes'],
            'OIDCAuthMethodConfig.OIDCACRValues' => [OIDCAuthMethodConfig::class, 'OIDCACRValues'],
            'OIDCAuthMethodConfig.AllowedRedirectURIs' => [OIDCAuthMethodConfig::class, 'AllowedRedirectURIs'],
            'OIDCAuthMethodConfig.JWTValidationPubKeys' => [OIDCAuthMethodConfig::class, 'JWTValidationPubKeys'],
            'ServiceQuery.Tags' => [ServiceQuery::class, 'Tags'],
            'ServiceQuery.IgnoreCheckIDs' => [ServiceQuery::class, 'IgnoreCheckIDs'],
        ];
    }

    /**
     * @param class-string $class
     */
    #[DataProvider('nullListFields')]
    public function testNullListHydratesAsEmpty(string $class, string $field): void
    {
        $decoded = new \stdClass();
        $decoded->{$field} = null;
        $n = $class::jsonUnserialize($decoded);
        self::assertSame([], $n->{$field});
    }
}
