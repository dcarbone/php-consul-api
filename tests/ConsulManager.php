<?php namespace DCarbone\PHPConsulAPITests;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class ConsulManager
 * @package DCarbone\PHPConsulAPITests
 */
abstract class ConsulManager
{
    public const TMP_DIR = __DIR__ . '/../tmp';
    public const DATA_DIR = self::TMP_DIR . '/consul-data';

    public const START_SINGLE_CMD = __DIR__ . '/run_consul.sh';
    public const STOP_SINGLE_CMD = __DIR__ . '/stop_consul.sh';

    public const PID_FILE = self::TMP_DIR . '/consul.pid';

    /**
     * Start up single instance of Consul Agent with specified flags
     *
     * @param string $flags
     */
    public static function startSingle(string $flags)
    {
        if (file_exists(self::PID_FILE)) {
            self::stopSingle();
        }

        shell_exec(self::START_SINGLE_CMD . " {$flags}");

        // sleep to allow consul to setup
        sleep(3);
    }

    /**
     * Start a single instance of a consul agent in "-dev" mode
     */
    public static function startSingleDev()
    {
        self::startSingle('-dev');
    }

    /**
     * Stop running instance
     */
    public static function stopSingle()
    {
        if (file_exists(self::PID_FILE)) {
            shell_exec(self::STOP_SINGLE_CMD);
            if (file_exists(self::PID_FILE)) {
                unlink(self::PID_FILE);
            }
            sleep(1);
        }
    }
}