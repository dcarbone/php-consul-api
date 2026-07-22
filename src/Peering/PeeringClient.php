<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

class PeeringClient extends AbstractClient
{
    public function Read(string $name, null|QueryOptions $opts = null): PeeringReadResponse
    {
        $ret = new PeeringReadResponse();
        if ('' === $name) {
            $ret->Err = new Error('peering name cannot be empty');
            return $ret;
        }

        $resp = $this->_requireNotFoundOrOK($this->_doGet(sprintf('v1/peering/%s', urlencode($name)), $opts));
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            $ret->setQueryMeta($resp->buildQueryMeta());
            return $ret;
        }

        if (HTTP\StatusNotFound === $resp->Response->getStatusCode()) {
            $ret->setQueryMeta($resp->buildQueryMeta());
            $ret->Peering = null;
            return $ret;
        }

        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Delete(string $name, null|WriteOptions $opts = null): WriteResponse
    {
        $ret = new WriteResponse();
        if ('' === $name) {
            $ret->Err = new Error('peering name cannot be empty');
            return $ret;
        }

        $resp = $this->_requireOK($this->_doDelete(sprintf('v1/peering/%s', urlencode($name)), $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function GenerateToken(
        PeeringGenerateTokenRequest $request,
        null|WriteOptions $opts = null
    ): ValuedWriteStringResponse {
        $ret = new ValuedWriteStringResponse();
        if ('' === $request->PeerName) {
            $ret->Err = new Error('peer name cannot be empty');
            return $ret;
        }

        $resp = $this->_requireOK($this->_doPost('v1/peering/token', $request, $opts));
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        $ret->WriteMeta = $resp->buildWriteMeta();
        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            $ret->Err = $dec->Err;
            return $ret;
        }
        if ($dec->Decoded instanceof \stdClass && isset($dec->Decoded->PeeringToken)) {
            $ret->Value = (string)$dec->Decoded->PeeringToken;
        } else {
            $ret->Value = '';
        }
        return $ret;
    }

    public function Establish(PeeringEstablishRequest $request, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePost('v1/peering/establish', $request, $opts);
    }

    public function List(null|QueryOptions $opts = null): PeeringsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/peerings', $opts));
        $ret = new PeeringsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
