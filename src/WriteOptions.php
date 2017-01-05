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
 * Class WriteOptions
 * @package DCarbone\PHPConsulAPI
 */
class WriteOptions extends AbstractOptions
{
    use ConsulHttpParamContainerTrait;

    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Datacenter' => null,
            'Token' => null
        );
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return (string)$this->_storage['Datacenter'];
    }

    /**
     * @param string $dc
     * @return $this
     */
    public function setDatacenter($dc)
    {
        $this->_storage['Datacenter'] = $dc;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return (string)$this->_storage['Token'];
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->_storage['Token'] = $token;
        return $this;
    }
}