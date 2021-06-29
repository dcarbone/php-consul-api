<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\HasSettableStringTags;
use DCarbone\PHPConsulAPI\HasStringTags;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class AgentServiceRegistration
 */
class AgentServiceRegistration extends AbstractModel
{
    use HasSettableStringTags;
    use HasStringTags;

    protected const FIELDS = [
        self::FIELD_KIND                => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_ID                  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAME                => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PORT                => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_ADDRESS             => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TAGGED_ADDRESSES    => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServiceAddress::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_ENABLE_TAG_OVERRIDE => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
        self::FIELD_META                => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::MIXED,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_WEIGHTS             => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => AgentWeights::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_CHECK               => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => AgentServiceCheck::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_CHECKS              => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => AgentServiceChecks::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_PROXY               => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => AgentServiceConnectProxyConfig::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_CONNECT             => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => AgentServiceConnect::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_NAMESPACE           => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_KIND                = 'Kind';
    private const FIELD_ID                  = 'ID';
    private const FIELD_NAME                = 'Name';
    private const FIELD_PORT                = 'Port';
    private const FIELD_ADDRESS             = 'Address';
    private const FIELD_TAGGED_ADDRESSES    = 'TaggedAddresses';
    private const FIELD_ENABLE_TAG_OVERRIDE = 'EnableTagOverride';
    private const FIELD_META                = 'Meta';
    private const FIELD_WEIGHTS             = 'Weights';
    private const FIELD_CHECK               = 'Check';
    private const FIELD_CHECKS              = 'Checks';
    private const FIELD_PROXY               = 'Proxy';
    private const FIELD_CONNECT             = 'Connect';
    private const FIELD_NAMESPACE           = 'Namespace';

    /** @var string */
    public string $Kind = '';
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var int */
    public int $Port = 0;
    /** @var string */
    public string $Address = '';
    /** @var \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[] */
    public array $TaggedAddresses = [];
    /** @var bool */
    public bool $EnableTagOverride = false;
    /** @var array */
    public array $Meta = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentWeights|null */
    public ?AgentWeights $Weights = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck|null */
    public ?AgentServiceCheck $Check = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceChecks */
    public AgentServiceChecks $Checks;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null */
    public ?AgentServiceConnectProxyConfig $Proxy = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceConnect|null */
    public ?AgentServiceConnect $Connect = null;
    /** @var string */
    public string $Namespace = '';

    /**
     * AgentServiceRegistration constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Checks)) {
            $this->Checks = new AgentServiceChecks(null);
        }
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->Kind;
    }

    /**
     * @param string $Kind
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setKind(string $Kind): self
    {
        $this->Kind = $Kind;
        return $this;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @param int $Port
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[]|null
     */
    public function getTaggedAddresses(): ?array
    {
        return $this->TaggedAddresses;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[] $TaggedAddresses
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setTaggedAddresses(array $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableTagOverride(): bool
    {
        return $this->EnableTagOverride;
    }

    /**
     * @param bool $EnableTagOverride
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setEnableTagOverride(bool $EnableTagOverride): self
    {
        $this->EnableTagOverride = $EnableTagOverride;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMeta(): ?array
    {
        return $this->Meta;
    }

    /**
     * @param array $Meta
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setMeta(array $Meta): self
    {
        $this->Meta = $Meta;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentWeights|null
     */
    public function getWeights(): ?AgentWeights
    {
        return $this->Weights;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentWeights|null $Weights
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setWeights(?AgentWeights $Weights): self
    {
        $this->Weights = $Weights;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck|null
     */
    public function getCheck(): ?AgentServiceCheck
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck|null $Check
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setCheck(?AgentServiceCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceChecks
     */
    public function getChecks(): AgentServiceChecks
    {
        return $this->Checks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceChecks $Checks
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setChecks(AgentServiceChecks $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null
     */
    public function getProxy(): ?AgentServiceConnectProxyConfig
    {
        return $this->Proxy;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null $Proxy
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setProxy(?AgentServiceConnectProxyConfig $Proxy): self
    {
        $this->Proxy = $Proxy;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnect|null
     */
    public function getConnect(): ?AgentServiceConnect
    {
        return $this->Connect;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceConnect|null $Connect
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setConnect(?AgentServiceConnect $Connect): self
    {
        $this->Connect = $Connect;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->Name;
    }
}
