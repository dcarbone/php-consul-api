<?php namespace DCarbone\PHPConsulAPI;

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
        $params = [];
        foreach($this as $k => $v)
        {
            if (null !== $v)
            {
                if ('Datacenter' === $k)
                    $params['dc'] = $v;
                else if ('AllowStale' === $k)
                    $params['stale'] = $v;
                else if ('RequireConsistent' === $k)
                    $params['consistent'] = $v;
                else if ('WaitIndex' === $k)
                    $params['index'] = $v;
                else if ('WaitTime' === $k)
                    $params['wait'] = $v;
                else
                    $params[strtolower($k)] = $v;
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
