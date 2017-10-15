<?php namespace DCarbone\PHPConsulAPI\Health;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Consul;

/**
 * Class HealthChecks
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthChecks implements \Iterator, \ArrayAccess {
    /** @var \DCarbone\PHPConsulAPI\Health\HealthCheck[] */
    private $_checks = [];

    /**
     * HealthChecks constructor.
     * @param \DCarbone\PHPConsulAPI\Health\HealthCheck[] $checks
     */
    public function __construct($checks = []) {
        if (is_array($checks)) {
            foreach (array_filter($checks) as $check) {
                if (is_array($check)) {
                    $this->_checks[] = new HealthCheck($check);
                } else if ($check instanceof HealthCheck) {
                    $this->_checks[] = $check;
                } else {
                    throw new \InvalidArgumentException('HealthChecks only accepts HealthCheck as a child, saw "%s"',
                        is_object($check) ? get_class($check) : gettype($check));
                }
            }
        } else if (null === $checks) {
            // do nothin
        } else {
            throw new \InvalidArgumentException('HealthChecks::__construct only accepts null or array of HealthCheck\'s as values');
        }
    }

    /**
     * @return string
     */
    public function AggregatedStatus(): string {
        $passing = $warning = $critical = $maintenance = false;
        foreach ($this->_checks as $check) {
            if ($check->CheckID === Consul::NodeMaint || 0 === strpos($check->CheckID, Consul::ServiceMaintPrefix)) {
                $maintenance = true;
                continue;
            }
            switch ($check->Status) {
                case Consul::HealthPassing:
                    $passing = true;
                    break;
                case Consul::HealthWarning:
                    $warning = true;
                    break;
                case Consul::HealthCritical:
                    $critical = true;
                    break;

                default:
                    return '';
            }
        }

        if ($maintenance) {
            return Consul::HealthMaint;
        } else if ($critical) {
            return Consul::HealthCritical;
        } else if ($warning) {
            return Consul::HealthWarning;
        } else if ($passing) {
            return Consul::HealthPassing;
        } else {
            return Consul::HealthPassing;
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck
     */
    public function current() {
        return current($this->_checks);
    }

    public function next() {
        return next($this->_checks);
    }

    /**
     * @return int|null|string
     */
    public function key() {
        return key($this->_checks);
    }

    /**
     * @return bool
     */
    public function valid() {
        return null !== key($this->_checks);
    }

    public function rewind() {
        return reset($this->_checks);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset) {
        return is_int($offset) && isset($this->_checks[$offset]);
    }

    /**
     * @param int $offset
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck
     */
    public function offsetGet($offset) {
        if (is_int($offset) && isset($this->_checks[$offset])) {
            return $this->_checks[$offset];
        }
        throw new \OutOfRangeException('Offset '.
            (is_int($offset) ? $offset : gettype($offset)).
            ' does not exist in this list');
    }

    /**
     * @param int $offset
     * @param \DCarbone\PHPConsulAPI\Health\HealthCheck $value
     */
    public function offsetSet($offset, $value) {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException('Offset must be int');
        }
        if (!($value instanceof HealthCheck)) {
            throw new \InvalidArgumentException('Value must be instance of HealthCheck');
        }
        $this->_checks[$offset] = $value;
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset) {
        unset($this->_checks[$offset]);
    }
}