<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ACLTemplatedPolicyResponse extends AbstractType
{
    public string $TemplateName;
    public string $Schema;
    public string $Template;
    public string $Description;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $TemplateName = '',
        string $Schema = '',
        string $Template = '',
        string $Description = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->TemplateName = $TemplateName;
        $this->Schema = $Schema;
        $this->Template = $Template;
        $this->Description = $Description;
    }

    public function getTemplateName(): string
    {
        return $this->TemplateName;
    }

    public function setTemplateName(string $TemplateName): self
    {
        $this->TemplateName = $TemplateName;
        return $this;
    }

    public function getSchema(): string
    {
        return $this->Schema;
    }

    public function setSchema(string $Schema): self
    {
        $this->Schema = $Schema;
        return $this;
    }

    public function getTemplate(): string
    {
        return $this->Template;
    }

    public function setTemplate(string $Template): self
    {
        $this->Template = $Template;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;
        return $this;
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
        $out->TemplateName = $this->TemplateName;
        $out->Schema = $this->Schema;
        $out->Template = $this->Template;
        $out->Description = $this->Description;
        return $out;
    }
}
