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
     */
    public function __construct(
        array $ImportedServices = [],
        array $ExportedServices = [],
        string $LastHeartbeat = '',
        string $LastReceive = '',
        string $LastSend = '',
    ) {
        $this->ImportedServices = $ImportedServices;
        $this->ExportedServices = $ExportedServices;
        $this->LastHeartbeat = $LastHeartbeat;
        $this->LastReceive = $LastReceive;
        $this->LastSend = $LastSend;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }
}
