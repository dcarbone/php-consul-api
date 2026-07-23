<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\PHPConsulAPI\PHPLib\AbstractValuedQueryResponse;
use DCarbone\PHPConsulAPI\PHPLib\UnmarshalledResponseInterface;

class PeeringReadResponse extends AbstractValuedQueryResponse implements UnmarshalledResponseInterface
{
    public null|Peering $Peering = null;

    public function getValue(): null|Peering
    {
        return $this->Peering;
    }

    public function unmarshalValue(mixed $decoded): void
    {
        if (null === $decoded) {
            $this->Peering = null;
            return;
        }
        $this->Peering = Peering::jsonUnserialize($decoded);
    }
}
