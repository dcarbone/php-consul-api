<?php namespace DCarbone\SimpleConsulPHP\Base;

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
 * Class QueryParameters
 * @package DCarbone\SimpleConsulPHP\Base
 */
class QueryParameters extends AbstractCollection
{
    /**
     * @return string
     */
    public function queryString()
    {
        $params = '';
        foreach($this as $k=>$v)
        {
            switch(gettype($k))
            {
                case 'integer':
                    $params = sprintf('%s%s&', $params, $v);
                    break;
                case 'string':
                    $params = sprintf('%s%s=%s&', $params, $k, $v);
            }
        }
        return rtrim($params, "&");
    }
}