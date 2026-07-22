<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PeeringGenerateTokenRequest extends AbstractType
{
    public string $PeerName;
    public string $Partition;
    /** @var null|array<string,string> */
    public null|array $Meta;
    /** @var array<string> */
    public array $ServerExternalAddresses;

    /**
     * @param null|array<string,string> $Meta
     * @param array<string> $ServerExternalAddresses
     */
    public function __construct(
        string $PeerName = '',
        string $Partition = '',
        null|array $Meta = null,
        array $ServerExternalAddresses = [],
    ) {
        $this->PeerName = $PeerName;
        $this->Partition = $Partition;
        $this->Meta = $Meta;
        $this->ServerExternalAddresses = $ServerExternalAddresses;
    }
}
