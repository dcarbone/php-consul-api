<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractValuedQueryResponse;
use DCarbone\PHPConsulAPI\PHPLib\UnmarshalledResponseInterface;

class PeeringsResponse extends AbstractValuedQueryResponse implements UnmarshalledResponseInterface
{
    /** @var array<Peering> */
    public array $Peerings = [];

    /**
     * @return array<Peering>
     */
    public function getValue(): array
    {
        return $this->Peerings;
    }

    public function unmarshalValue(mixed $decoded): void
    {
        $this->Peerings = [];
        if (!is_array($decoded)) {
            return;
        }
        foreach ($decoded as $peering) {
            $this->Peerings[] = Peering::jsonUnserialize($peering);
        }
    }
}
