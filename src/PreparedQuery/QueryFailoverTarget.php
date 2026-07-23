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

    /**
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $Peer = '',
        string $Datacenter = '',
        string $Partition = '',
        string $Namespace = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Peer = $Peer;
        $this->Datacenter = $Datacenter;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
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
        $out->Peer = $this->Peer;
        $out->Datacenter = $this->Datacenter;
        $out->Partition = $this->Partition;
        $out->Namespace = $this->Namespace;
        return $out;
    }
}
