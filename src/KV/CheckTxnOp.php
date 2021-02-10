<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class CheckTxnOp
 */
class CheckTxnOp extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_CHECK => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => HealthCheck::class,
        ],
    ];

    private const FIELD_CHECK = 'Check';

    /** @var string */
    public string $Verb = '';
    /** @var \DCarbone\PHPConsulAPI\Health\HealthCheck */
    public HealthCheck $Check;

    /**
     * CheckTxnOp constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Check)) {
            $this->Check = new HealthCheck(null);
        }
    }

    /**
     * @return string
     */
    public function getVerb(): string
    {
        return $this->Verb;
    }

    /**
     * @param string $Verb
     * @return \DCarbone\PHPConsulAPI\KV\CheckTxnOp
     */
    public function setVerb(string $Verb): self
    {
        $this->Verb = $Verb;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck
     */
    public function getCheck(): HealthCheck
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\HealthCheck $Check
     * @return \DCarbone\PHPConsulAPI\KV\CheckTxnOp
     */
    public function setCheck(HealthCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }
}
