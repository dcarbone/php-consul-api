<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\QueryOptions;

class TxnClient extends AbstractClient
{
    public function Txn(null|QueryOptions $opts = null, TxnOp ...$txn): TxnAPIResponse
    {
        $ret = new TxnAPIResponse();

        $resp = $this->_doPut('v1/txn', $txn, $opts);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }

        $ret->QueryMeta = $resp->buildQueryMeta();
        $code = $resp->Response->getStatusCode();
        $ret->OK = HTTP\StatusOK === $code;

        if (HTTP\StatusOK === $code || HTTP\StatusConflict === $code) {
            $dec = $this->_decodeBody($resp->Response->getBody());
            if (null !== $dec->Err) {
                $ret->OK = false;
                $ret->Err = $dec->Err;
                return $ret;
            }

            $ret->TxnResponse = TxnResponse::jsonUnserialize($dec->Decoded);
            return $ret;
        }

        if ('' === ($body = $resp->Response->getBody()->getContents())) {
            $ret->Err = new Error('Unable to read response');
            return $ret;
        }

        $ret->Err = new Error('Failed request: ' . $body);
        return $ret;
    }
}
