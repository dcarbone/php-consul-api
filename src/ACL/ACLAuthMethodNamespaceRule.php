<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class ACLAuthMethodNamespaceRule
 */
class ACLAuthMethodNamespaceRule extends AbstractModel
{
    private const FIELD_SELECTOR       = 'Selector';
    private const FIELD_BIND_NAMESPACE = 'BindNamespace';

    /** @var string|null */
    public ?string $Selector = null;
    /** @var string|null */
    public ?string $BindNamespace = null;

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_SELECTOR=> [
            Hydration::FIELD_TYPE    => Hydration::STRING,
            Hydration::FIELD_NULLABLE=> true,
        ],
        self::FIELD_BIND_NAMESPACE=> [
            Hydration::FIELD_TYPE    => Hydration::STRING,
            Hydration::FIELD_NULLABLE=> true,
        ],
    ];

    /**
     * @return string|null
     */
    public function getSelector(): ?string
    {
        return $this->Selector;
    }

    /**
     * @param string|null $Selector
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule
     */
    public function setSelector(?string $Selector): self
    {
        $this->Selector = $Selector;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBindNamespace(): ?string
    {
        return $this->BindNamespace;
    }

    /**
     * @param string|null $BindNamespace
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule
     */
    public function setBindNamespace(?string $BindNamespace): self
    {
        $this->BindNamespace = $BindNamespace;
        return $this;
    }
}
