<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

use DCarbone\PHPConsulAPI\Consul;

final class ConfigEntryFactory
{
    public static function decode(\stdClass $decoded): ConfigEntry
    {
        $kind = self::kindFromDecoded($decoded);
        return match ($kind) {
            Consul::ServiceDefaults => ServiceConfigEntry::jsonUnserialize($decoded),
            Consul::ProxyDefaults => ProxyConfigEntry::jsonUnserialize($decoded),
            Consul::ServiceRouter => ServiceRouterConfigEntry::jsonUnserialize($decoded),
            Consul::ServiceSplitter => ServiceSplitterConfigEntry::jsonUnserialize($decoded),
            Consul::ServiceResolver => ServiceResolverConfigEntry::jsonUnserialize($decoded),
            Consul::IngressGateway => IngressGatewayConfigEntry::jsonUnserialize($decoded),
            Consul::TerminatingGateway => TerminatingGatewayConfigEntry::jsonUnserialize($decoded),
            Consul::ServiceIntentions => ServiceIntentionsConfigEntry::jsonUnserialize($decoded),
            Consul::MeshConfig => MeshConfigEntry::jsonUnserialize($decoded),
            default => GenericConfigEntry::jsonUnserialize($decoded),
        };
    }

    public static function kindFromDecoded(\stdClass $decoded): string
    {
        if (isset($decoded->Kind) && is_string($decoded->Kind)) {
            return $decoded->Kind;
        }
        if (isset($decoded->kind) && is_string($decoded->kind)) {
            return $decoded->kind;
        }
        throw new \RuntimeException('Config entry payload did not contain a valid Kind field');
    }
}
