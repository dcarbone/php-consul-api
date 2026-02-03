<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class OIDCAuthMethodConfig extends AbstractType
{
    /** @var array<string> */
    public array $JWTSupportedAlgs;
    /** @var array<string> */
    public array $BoundAudiences;
    /** @var array<string,string> */
    public array $ClaimMappings;
    /** @var array<string,string> */
    public array $ListClaimMappings;
    public string $OIDCDiscoveryURL;
    public string $OIDCDiscoveryCACert;
    public string $OIDCClientID;
    public string $OIDCClientSecret;
    /** @var array<string> */
    public array $OIDCScopes;
    /** @var array<string> */
    public array $OIDCACRValues;
    /** @var array<string> */
    public array $AllowedRedirectURIs;
    public bool $VerboseOIDCLogging;
    public string $JWKSURL;
    public string $JWKSCACert;
    /** @var array<string> */
    public array $JWTValidationPubKeys;
    public string $BoundIssuer;
    public Time\Duration $ExpirationLeeway;
    public Time\Duration $NotBeforeLeeway;
    public Time\Duration $ClockSkewLeeway;

    /**
     * @param array<string> $JWTSupportedAlgs
     * @param array<string> $BoundAudiences
     * @param array<string,string> $ClaimMappings
     * @param array<string,string> $ListClaimMappings
     * @param array<string> $OIDCScopes
     * @param array<string> $OIDCACRValues
     * @param array<string> $AllowedRedirectURIs
     * @param array<string> $JWTValidationPubKeys
     */
    public function __construct(
        array $JWTSupportedAlgs = [],
        array $BoundAudiences = [],
        array $ClaimMappings = [],
        array $ListClaimMappings = [],
        string $OIDCDiscoveryURL = '',
        string $OIDCDiscoveryCACert = '',
        string $OIDCClientID = '',
        string $OIDCClientSecret = '',
        array $OIDCScopes = [],
        array $OIDCACRValues = [],
        array $AllowedRedirectURIs = [],
        bool $VerboseOIDCLogging = false,
        string $JWKSURL = '',
        string $JWKSCACert = '',
        array $JWTValidationPubKeys = [],
        string $BoundIssuer = '',
        null|int|float|string|\DateInterval|Time\Duration $ExpirationLeeway = null,
        null|int|float|string|\DateInterval|Time\Duration $NotBeforeLeeway = null,
        null|int|float|string|\DateInterval|Time\Duration $ClockSkewLeeway = null,
    ) {
        $this->setJWTSupportedAlgs(...$JWTSupportedAlgs);
        $this->setBoundAudiences(...$BoundAudiences);
        $this->setClaimMappings($ClaimMappings);
        $this->setListClaimMappings($ListClaimMappings);
        $this->OIDCDiscoveryURL = $OIDCDiscoveryURL;
        $this->OIDCDiscoveryCACert = $OIDCDiscoveryCACert;
        $this->OIDCClientID = $OIDCClientID;
        $this->OIDCClientSecret = $OIDCClientSecret;
        $this->setOIDCScopes(...$OIDCScopes);
        $this->setOIDCACRValues(...$OIDCACRValues);
        $this->setAllowedRedirectURIs(...$AllowedRedirectURIs);
        $this->VerboseOIDCLogging = $VerboseOIDCLogging;
        $this->JWKSURL = $JWKSURL;
        $this->JWKSCACert = $JWKSCACert;
        $this->setJWTValidationPubKeys(...$JWTValidationPubKeys);
        $this->BoundIssuer = $BoundIssuer;
        $this->setExpirationLeeway($ExpirationLeeway);
        $this->setNotBeforeLeeway($NotBeforeLeeway);
        $this->setClockSkewLeeway($ClockSkewLeeway);
    }

    /**
     * @return array<string>
     */
    public function getJWTSupportedAlgs(): array
    {
        return $this->JWTSupportedAlgs;
    }

    public function setJWTSupportedAlgs(string ...$JWTSupportedAlgs): self
    {
        $this->JWTSupportedAlgs = $JWTSupportedAlgs;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getBoundAudiences(): array
    {
        return $this->BoundAudiences;
    }

    public function setBoundAudiences(string ...$BoundAudiences): self
    {
        $this->BoundAudiences = $BoundAudiences;
        return $this;
    }

    /**
     * @return array<string,string>
     */
    public function getClaimMappings(): array
    {
        return $this->ClaimMappings;
    }

    public function setClaimMapping(string $k, string $v): self
    {
        $this->ClaimMappings[$k] = $v;
        return $this;
    }

    /**
     * @param array<string,string>|\stdClass|null $ClaimMappings
     */
    public function setClaimMappings(null|array|\stdClass $ClaimMappings): self
    {
        $this->ClaimMappings = [];
        if (null === $ClaimMappings) {
            return $this;
        }
        foreach ($ClaimMappings as $k => $v) {
            $this->setClaimMapping($k, $v);
        }
        return $this;
    }

    /**
     * @return array<string,string>
     */
    public function getListClaimMappings(): array
    {
        return $this->ListClaimMappings;
    }

    public function setListClaimMapping(string $k, string $v): self
    {
        $this->ListClaimMappings[$k] = $v;
        return $this;
    }

    /**
     * @param array<string,string>|\stdClass|null $ListClaimMappings
     */
    public function setListClaimMappings(null|array|\stdClass $ListClaimMappings): self
    {
        $this->ListClaimMappings = [];
        if (null === $ListClaimMappings) {
            return $this;
        }
        foreach ($ListClaimMappings as $k => $v) {
            $this->setListClaimMapping($k, $v);
        }
        return $this;
    }

    public function getOIDCDiscoveryURL(): string
    {
        return $this->OIDCDiscoveryURL;
    }

    public function setOIDCDiscoveryURL(string $OIDCDiscoveryURL): self
    {
        $this->OIDCDiscoveryURL = $OIDCDiscoveryURL;
        return $this;
    }

    public function getOIDCDiscoveryCACert(): string
    {
        return $this->OIDCDiscoveryCACert;
    }

    public function setOIDCDiscoveryCACert(string $OIDCDiscoveryCACert): self
    {
        $this->OIDCDiscoveryCACert = $OIDCDiscoveryCACert;
        return $this;
    }

    public function getOIDCClientID(): string
    {
        return $this->OIDCClientID;
    }

    public function setOIDCClientID(string $OIDCClientID): self
    {
        $this->OIDCClientID = $OIDCClientID;
        return $this;
    }

    public function getOIDCClientSecret(): string
    {
        return $this->OIDCClientSecret;
    }

    public function setOIDCClientSecret(string $OIDCClientSecret): self
    {
        $this->OIDCClientSecret = $OIDCClientSecret;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getOIDCScopes(): array
    {
        return $this->OIDCScopes;
    }

    public function setOIDCScopes(string ...$OIDCScopes): self
    {
        $this->OIDCScopes = $OIDCScopes;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getOIDCACRValues(): array
    {
        return $this->OIDCACRValues;
    }

    public function setOIDCACRValues(string ...$OIDCACRValues): self
    {
        $this->OIDCACRValues = $OIDCACRValues;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getAllowedRedirectURIs(): array
    {
        return $this->AllowedRedirectURIs;
    }

    public function setAllowedRedirectURIs(string ...$AllowedRedirectURIs): self
    {
        $this->AllowedRedirectURIs = $AllowedRedirectURIs;
        return $this;
    }

    public function isVerboseOIDCLogging(): bool
    {
        return $this->VerboseOIDCLogging;
    }

    public function setVerboseOIDCLogging(bool $VerboseOIDCLogging): self
    {
        $this->VerboseOIDCLogging = $VerboseOIDCLogging;
        return $this;
    }

    public function getJWKSURL(): string
    {
        return $this->JWKSURL;
    }

    public function setJWKSURL(string $JWKSURL): self
    {
        $this->JWKSURL = $JWKSURL;
        return $this;
    }

    public function getJWKSCACert(): string
    {
        return $this->JWKSCACert;
    }

    public function setJWKSCACert(string $JWKSCACert): self
    {
        $this->JWKSCACert = $JWKSCACert;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getJWTValidationPubKeys(): array
    {
        return $this->JWTValidationPubKeys;
    }

    public function setJWTValidationPubKeys(string ...$JWTValidationPubKeys): self
    {
        $this->JWTValidationPubKeys = $JWTValidationPubKeys;
        return $this;
    }

    public function getBoundIssuer(): string
    {
        return $this->BoundIssuer;
    }

    public function setBoundIssuer(string $BoundIssuer): self
    {
        $this->BoundIssuer = $BoundIssuer;
        return $this;
    }

    public function getExpirationLeeway(): Time\Duration
    {
        return $this->ExpirationLeeway;
    }

    public function setExpirationLeeway(null|int|float|string|\DateInterval|Time\Duration $ExpirationLeeway): self
    {
        $this->ExpirationLeeway = Time::Duration($ExpirationLeeway);
        return $this;
    }

    public function getNotBeforeLeeway(): Time\Duration
    {
        return $this->NotBeforeLeeway;
    }

    public function setNotBeforeLeeway(null|int|float|string|\DateInterval|Time\Duration $NotBeforeLeeway): self
    {
        $this->NotBeforeLeeway = Time::Duration($NotBeforeLeeway);
        return $this;
    }

    public function getClockSkewLeeway(): Time\Duration
    {
        return $this->ClockSkewLeeway;
    }

    public function setClockSkewLeeway(null|int|float|string|\DateInterval|Time\Duration $ClockSkewLeeway): self
    {
        $this->ClockSkewLeeway = Time::Duration($ClockSkewLeeway);
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('JWTSupportedAlgs' === $k) {
                $n->setJWTSupportedAlgs(...$v);
            } elseif ('BoundAudiences' === $k) {
                $n->setBoundAudiences(...$v);
            } elseif ('ClaimMappings' === $k) {
                $n->setClaimMappings($v);
            } elseif ('ListClaimMappings' === $k) {
                $n->setListClaimMappings($v);
            } elseif ('OIDCScopes' === $k) {
                $n->setOIDCScopes(...$v);
            } elseif ('OIDCACRValues' === $k) {
                $n->setOIDCACRValues(...$v);
            } elseif ('AllowedRedirectURIs' === $k) {
                $n->setAllowedRedirectURIs(...$v);
            } elseif ('JWTValidationPubKeys' === $k) {
                $n->setJWTValidationPubKeys(...$v);
            } elseif ('ExpirationLeeway' === $k) {
                $n->setExpirationLeeway($v);
            } elseif ('NotBeforeLeeway' === $k) {
                $n->setNotBeforeLeeway($v);
            } elseif ('ClockSkewLeeway' === $k) {
                $n->setClockSkewLeeway($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();

        if ([] !== $this->JWTSupportedAlgs) {
            $out->JWTSupportedAlgs = $this->JWTSupportedAlgs;
        }
        if ([] !== $this->BoundAudiences) {
            $out->BoundAudiences = $this->BoundAudiences;
        }
        if ([] !== $this->ClaimMappings) {
            $out->ClaimMappings = $this->ClaimMappings;
        }
        if ([] !== $this->ListClaimMappings) {
            $out->ListClaimMappings = $this->ListClaimMappings;
        }
        if ('' !== $this->OIDCDiscoveryURL) {
            $out->OIDCDiscoveryURL = $this->OIDCDiscoveryURL;
        }
        if ('' !== $this->OIDCDiscoveryCACert) {
            $out->OIDCDiscoveryCACert = $this->OIDCDiscoveryCACert;
        }
        if ('' !== $this->OIDCClientID) {
            $out->OIDCClientID = $this->OIDCClientID;
        }
        if ('' !== $this->OIDCClientSecret) {
            $out->OIDCClientSecret = $this->OIDCClientSecret;
        }
        if ([] !== $this->OIDCScopes) {
            $out->OIDCScopes = $this->OIDCScopes;
        }
        if ([] !== $this->OIDCACRValues) {
            $out->OIDCACRValues = $this->OIDCACRValues;
        }
        if ([] !== $this->AllowedRedirectURIs) {
            $out->AllowedRedirectURIs = $this->AllowedRedirectURIs;
        }
        if ($this->VerboseOIDCLogging) {
            $out->VerboseOIDCLogging = $this->VerboseOIDCLogging;
        }
        if ('' !== $this->JWKSURL) {
            $out->JWKSURL = $this->JWKSURL;
        }
        if ('' !== $this->JWKSCACert) {
            $out->JWKSCACert = $this->JWKSCACert;
        }
        if ([] !== $this->JWTValidationPubKeys) {
            $out->JWTValidationPubKeys = $this->JWTValidationPubKeys;
        }
        if ('' !== $this->BoundIssuer) {
            $out->BoundIssuer = $this->BoundIssuer;
        }
        if (0 !== $this->ExpirationLeeway->Nanoseconds()) {
            $out->ExpirationLeeway = $this->ExpirationLeeway;
        }
        if (0 !== $this->NotBeforeLeeway->Nanoseconds()) {
            $out->NotBeforeLeeway = $this->NotBeforeLeeway;
        }
        if (0 !== $this->ClockSkewLeeway->Nanoseconds()) {
            $out->ClockSkewLeeway = $this->ClockSkewLeeway;
        }
        return $out;
    }
}
