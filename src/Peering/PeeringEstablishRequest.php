<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PeeringEstablishRequest extends AbstractType
{
    public string $PeerName;
    public string $PeeringToken;
    public string $Partition;
    /** @var null|array<string,string> */
    public null|array $Meta;

    /**
     * @param null|array<string,string> $Meta
     */
    public function __construct(
        string $PeerName = '',
        string $PeeringToken = '',
        string $Partition = '',
        null|array $Meta = null,
    ) {
        $this->PeerName = $PeerName;
        $this->PeeringToken = $PeeringToken;
        $this->Partition = $Partition;
        $this->Meta = $Meta;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->PeerName = $this->PeerName;
        $out->PeeringToken = $this->PeeringToken;
        $out->Partition = $this->Partition;
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        return $out;
    }
}
