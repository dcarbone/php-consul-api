<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Transcoding;

class CheckTxnOp extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_CHECK => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => HealthCheck::class,
        ],
    ];

    private const FIELD_CHECK = 'Check';

    public string $Verb = '';
    public HealthCheck $Check;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Check)) {
            $this->Check = new HealthCheck(null);
        }
    }

    public function getVerb(): string
    {
        return $this->Verb;
    }

    public function setVerb(string $Verb): self
    {
        $this->Verb = $Verb;
        return $this;
    }

    public function getCheck(): HealthCheck
    {
        return $this->Check;
    }

    public function setCheck(HealthCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }
}
