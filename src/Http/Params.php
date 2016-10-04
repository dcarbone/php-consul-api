<?php namespace DCarbone\PHPConsulAPI\Http;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Collection\AbstractCollection;

/**
 * Class Params
 * @package DCarbone\PHPConsulAPI
 */
class Params extends AbstractCollection
{
    use ConsulHttpParamContainerTrait;

    /**
     * @param mixed $param
     * @param mixed $value
     * @return $this
     */
    public function set($param, $value)
    {
        $this[$param] = $value;
        return $this;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_string($offset))
        {
            switch(strtolower($offset))
            {
                case 'datacenter':
                case 'dc':
                    $offset = 'Datacenter';
                    break;

                case 'allowstale':
                case 'stale':
                    $offset = 'AllowStale';
                    break;

                case 'requireconsistent':
                case 'consistent':
                    $offset = 'RequireConsistent';
                    break;

                case 'waitindex':
                case 'index':
                    $offset = 'WaitIndex';
                    break;

                case 'waittime':
                case 'wait':
                    $offset = 'WaitTime';
                    break;
            }
        }

        parent::offsetSet($offset, $value);
    }
}