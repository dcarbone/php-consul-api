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
 * Class NullLogger
 * @package DCarbone\PHPConsulAPI
 */
class NullLogger implements ConsulAPILoggerInterface
{
    /**
     * @param string $message
     * @return bool
     */
    public function error($message)
    {
        // do nothing...
        return true;
    }

    /**
     * @param string $message
     * @return bool
     */
    public function warn($message)
    {
        // do nothing...
        return true;
    }

    /**
     * @param string $message
     * @return bool
     */
    public function info($message)
    {
        // do nothing...
        return true;
    }

    /**
     * @param string $message
     * @return bool
     */
    public function debug($message)
    {
        // do nothing...
        return true;
    }
}