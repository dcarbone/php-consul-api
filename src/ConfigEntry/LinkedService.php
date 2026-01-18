<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class LinkedService extends AbstractType
{
    public string $Namespace;
    public string $Name;
    public string $CAFile;
    public string $CertFile;
    public string $KeyFile;
    public string $SNI;

    public function __construct(
        string $Namespace = '',
        string $Name = '',
        string $CAFile = '',
        string $CertFile = '',
        string $KeyFile = '',
        string $SNI = ''
    ) {
        $this->Namespace = $Namespace;
        $this->Name = $Name;
        $this->CAFile = $CAFile;
        $this->CertFile = $CertFile;
        $this->KeyFile = $KeyFile;
        $this->SNI = $SNI;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getCAFile(): string
    {
        return $this->CAFile;
    }

    public function setCAFile(string $CAFile): self
    {
        $this->CAFile = $CAFile;
        return $this;
    }

    public function getCertFile(): string
    {
        return $this->CertFile;
    }

    public function setCertFile(string $CertFile): self
    {
        $this->CertFile = $CertFile;
        return $this;
    }

    public function getKeyFile(): string
    {
        return $this->KeyFile;
    }

    public function setKeyFile(string $KeyFile): self
    {
        $this->KeyFile = $KeyFile;
        return $this;
    }

    public function getSNI(): string
    {
        return $this->SNI;
    }

    public function setSNI(string $SNI): self
    {
        $this->SNI = $SNI;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('ca_file' === $k) {
                $n->CAFile = $v;
            } elseif ('cert_file' === $k) {
                $n->CertFile = $v;
            } elseif ('key_file' === $k) {
                $n->KeyFile = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Name) {
            $out->Name = $this->Name;
        }
        if ('' !== $this->CAFile) {
            $out->CAFile = $this->CAFile;
        }
        if ('' !== $this->CertFile) {
            $out->CertFile = $this->CertFile;
        }
        if ('' !== $this->KeyFile) {
            $out->KeyFile = $this->KeyFile;
        }
        if ('' !== $this->SNI) {
            $out->SNI = $this->SNI;
        }
        return $out;
    }
}
