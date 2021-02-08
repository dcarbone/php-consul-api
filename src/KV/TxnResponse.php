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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class TxnResponse
 */
class TxnResponse extends AbstractModel
{
    private const FIELD_RESULTS = 'Results';
    private const FIELD_ERRORS  = 'Errors';

    /** @var \DCarbone\PHPConsulAPI\KV\TxnResults */
    public TxnResults $Results;
    /** @var \DCarbone\PHPConsulAPI\KV\TxnErrors */
    public TxnErrors $Errors;

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_RESULTS => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => TxnResults::class,
        ],
        self::FIELD_ERRORS  => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => TxnErrors::class,
        ],
    ];

    /**
     * TxnResponse constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Results)) {
            $this->Results = new TxnResults();
        }
        if (!isset($this->Errors)) {
            $this->Errors = new TxnErrors();
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\TxnResults
     */
    public function getResults(): TxnResults
    {
        return $this->Results;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\TxnErrors
     */
    public function getErrors(): TxnErrors
    {
        return $this->Errors;
    }
}
