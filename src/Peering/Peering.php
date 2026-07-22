<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class Peering extends AbstractType
{
    public string $ID;
    public string $Name;
    public string $Partition;
    public string $DeletedAt;
    /** @var null|array<string,string> */
    public null|array $Meta;
    public string $State;
    public string $PeerID;
    /** @var array<string> */
    public array $PeerCAPems;
    public string $PeerServerName;
    /** @var array<string> */
    public array $PeerServerAddresses;
    public PeeringStreamStatus $StreamStatus;
    public int $CreateIndex;
    public int $ModifyIndex;
    public PeeringRemoteInfo $Remote;

    /**
     * @param null|array<string,string> $Meta
     * @param array<string> $PeerCAPems
     * @param array<string> $PeerServerAddresses
     */
    public function __construct(
        string $ID = '',
        string $Name = '',
        string $Partition = '',
        string $DeletedAt = '',
        null|array $Meta = null,
        string $State = '',
        string $PeerID = '',
        array $PeerCAPems = [],
        string $PeerServerName = '',
        array $PeerServerAddresses = [],
        null|PeeringStreamStatus $StreamStatus = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        null|PeeringRemoteInfo $Remote = null,
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->DeletedAt = $DeletedAt;
        $this->Meta = $Meta;
        $this->State = $State;
        $this->PeerID = $PeerID;
        $this->PeerCAPems = $PeerCAPems;
        $this->PeerServerName = $PeerServerName;
        $this->PeerServerAddresses = $PeerServerAddresses;
        $this->StreamStatus = $StreamStatus ?? new PeeringStreamStatus();
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->Remote = $Remote ?? new PeeringRemoteInfo();
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('StreamStatus' === $k) {
                $n->StreamStatus = PeeringStreamStatus::jsonUnserialize($v);
            } elseif ('Remote' === $k) {
                $n->Remote = PeeringRemoteInfo::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }
}
