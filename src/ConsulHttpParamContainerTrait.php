<?php namespace DCarbone\PHPConsulAPI;

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

/**
 * Class HttpQueryTrait
 * @package DCarbone\PHPConsulAPI
 */
trait ConsulHttpParamContainerTrait
{
    /**
     * @return array
     */
    public function buildHttpQueryArray()
    {
        $params = array();
        foreach($this as $k => $v)
        {
            if (null !== $v)
            {
                $value = $v;

                switch($k)
                {
                    case 'Datacenter':
                        $key = 'dc';
                        break;
                    case 'AllowStale':
                        $key = 'stale';
                        break;
                    case 'RequireConsistent':
                        $key = 'consistent';
                        break;
                    case 'WaitIndex':
                        $key = 'index';
                        break;
                    case 'WaitTime':
                        $key = 'wait';
                        break;

                    default:
                        $key = strtolower($k);
                }

                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * @return string
     */
    public function buildHttpQueryString()
    {
        return (string)$this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return http_build_query($this->buildHttpQueryArray());
    }
}