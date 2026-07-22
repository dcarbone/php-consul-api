<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PeeringRemoteInfo extends AbstractType
{
    public string $Partition;
    public string $Datacenter;
    public null|Locality $Locality;

    public function __construct(
        string $Partition = '',
        string $Datacenter = '',
        null|Locality $Locality = null,
    ) {
        $this->Partition = $Partition;
        $this->Datacenter = $Datacenter;
        $this->Locality = $Locality;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Locality' === $k) {
                $n->Locality = null === $v ? null : Locality::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }
}
