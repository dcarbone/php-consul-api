<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class QueryFailoverTarget extends AbstractType
{
    public string $Peer;
    public string $Datacenter;
    public string $Partition;
    public string $Namespace;

    public function __construct(
        string $Peer = '',
        string $Datacenter = '',
        string $Partition = '',
        string $Namespace = '',
    ) {
        $this->Peer = $Peer;
        $this->Datacenter = $Datacenter;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Peer = $this->Peer;
        $out->Datacenter = $this->Datacenter;
        $out->Partition = $this->Partition;
        $out->Namespace = $this->Namespace;
        return $out;
    }
}
