<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PeeringStreamStatus extends AbstractType
{
    /** @var array<string> */
    public array $ImportedServices;
    /** @var array<string> */
    public array $ExportedServices;
    public string $LastHeartbeat;
    public string $LastReceive;
    public string $LastSend;

    /**
     * @param array<string> $ImportedServices
     * @param array<string> $ExportedServices
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        array $ImportedServices = [],
        array $ExportedServices = [],
        string $LastHeartbeat = '',
        string $LastReceive = '',
        string $LastSend = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->ImportedServices = $ImportedServices;
        $this->ExportedServices = $ExportedServices;
        $this->LastHeartbeat = $LastHeartbeat;
        $this->LastReceive = $LastReceive;
        $this->LastSend = $LastSend;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ImportedServices = $this->ImportedServices;
        $out->ExportedServices = $this->ExportedServices;
        $out->LastHeartbeat = $this->LastHeartbeat;
        $out->LastReceive = $this->LastReceive;
        $out->LastSend = $this->LastSend;
        return $out;
    }
}
