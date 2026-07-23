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
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     * @param null|array<string,string> $Meta
     */
    public function __construct(
        null|array $data = null,
        string $PeerName = '',
        string $PeeringToken = '',
        string $Partition = '',
        null|array $Meta = null,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->PeerName = $PeerName;
        $this->PeeringToken = $PeeringToken;
        $this->Partition = $Partition;
        $this->Meta = $Meta;
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
        $out->PeerName = $this->PeerName;
        $out->PeeringToken = $this->PeeringToken;
        $out->Partition = $this->Partition;
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        return $out;
    }
}
