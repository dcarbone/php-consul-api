<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PeeringRemoteInfo extends AbstractType
{
    public string $Partition;
    public string $Datacenter;
    public null|Locality $Locality;

    /**
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $Partition = '',
        string $Datacenter = '',
        null|Locality $Locality = null,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Partition = $Partition;
        $this->Datacenter = $Datacenter;
        $this->Locality = $Locality;
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
            if ('Locality' === $k) {
                $n->Locality = null === $v ? null : Locality::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Partition = $this->Partition;
        $out->Datacenter = $this->Datacenter;
        $out->Locality = $this->Locality;
        return $out;
    }
}
