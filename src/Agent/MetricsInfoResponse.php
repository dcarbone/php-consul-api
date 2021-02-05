<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractValuedResponse;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\ResponseErrorTrait;

/**
 * Class MetricsInfoResponse
 */
class MetricsInfoResponse extends AbstractValuedResponse implements \ArrayAccess
{
    use ResponseErrorTrait;

    /** @var \DCarbone\PHPConsulAPI\Agent\MetricsInfo|null */
    public $MetricsInfo = null;

    /**
     * MetricsInfoResponse constructor.
     * @param \DCarbone\PHPConsulAPI\Agent\MetricsInfo|null $metricsInfo
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(?MetricsInfo $metricsInfo, ?Error $err)
    {
        $this->MetricsInfo = $metricsInfo;
        parent::__construct($err);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo|null
     */
    public function getValue()
    {
        return $this->MetricsInfo;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return \is_int($offset) && 0 <= $offset && $offset < 2;
    }

    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->getValue();
        }
        if (1 === $offset) {
            return $this->Err;
        }
        throw new \OutOfBoundsException(\sprintf('Offset %s does not exist', \var_export($offset, true)));
    }
}
