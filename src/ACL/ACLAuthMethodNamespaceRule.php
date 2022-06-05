<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ACLAuthMethodNamespaceRule
 */
class ACLAuthMethodNamespaceRule extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SELECTOR       => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_BIND_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_SELECTOR       = 'Selector';
    private const FIELD_BIND_NAMESPACE = 'BindNamespace';

    /** @var string */
    public string $Selector = '';
    /** @var string */
    public string $BindNamespace = '';

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->Selector;
    }

    /**
     * @param string $Selector
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule
     */
    public function setSelector(string $Selector): self
    {
        $this->Selector = $Selector;
        return $this;
    }

    /**
     * @return string
     */
    public function getBindNamespace(): string
    {
        return $this->BindNamespace;
    }

    /**
     * @param string $BindNamespace
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule
     */
    public function setBindNamespace(string $BindNamespace): self
    {
        $this->BindNamespace = $BindNamespace;
        return $this;
    }
}
