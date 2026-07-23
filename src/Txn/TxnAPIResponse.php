<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

use DCarbone\PHPConsulAPI\PHPLib\ErrorField;
use DCarbone\PHPConsulAPI\PHPLib\QueryMetaField;

class TxnAPIResponse
{
    use QueryMetaField;
    use ErrorField;

    public bool $OK = false;
    public null|TxnResponse $TxnResponse = null;

    public function isOK(): bool
    {
        return $this->OK;
    }

    public function setOK(bool $OK): self
    {
        $this->OK = $OK;
        return $this;
    }

    public function getTxnResponse(): null|TxnResponse
    {
        return $this->TxnResponse;
    }

    public function setTxnResponse(null|TxnResponse $TxnResponse): self
    {
        $this->TxnResponse = $TxnResponse;
        return $this;
    }
}
