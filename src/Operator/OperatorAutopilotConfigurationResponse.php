<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

/**
 * Class OperatorAutopilotConfigurationResponse
 */
class OperatorAutopilotConfigurationResponse extends AbstractValuedResponse implements \ArrayAccess
{
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration|null */
    public $AutopilotConfiguration = null;

    /**
     * OperatorAutopilotConfigurationResponse constructor.
     * @param array|null $data
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(?array $data, ?Error $err)
    {
        parent::__construct($err);
        if (null !== $data) {
            $this->AutopilotConfiguration = new AutopilotConfiguration($data);
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration|null
     */
    public function getValue()
    {
        return $this->AutopilotConfiguration;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return \is_int($offset) && 0 <= $offset && $offset <= 1;
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\PHPConsulAPI\Error|\DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->AutopilotConfiguration;
        }
        if (1 === $offset) {
            return $this->Err;
        }
        throw new \OutOfRangeException(\sprintf('Offset %v does not exist', \var_export($offset, true)));
    }
}
