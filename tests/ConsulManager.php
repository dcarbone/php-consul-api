<?php namespace DCarbone\PHPConsulAPITests;

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
 * Class ConsulManager
 * @package DCarbone\PHPConsulAPITests
 */
abstract class ConsulManager {

    const PID_FILE = __DIR__ . '/../consul.pid';

    const START_SINGLE_CMD = __DIR__ . '/run_consul.sh';
    const STOP_SINGLE_CMD = __DIR__ . '/stop_consul.sh';

    /**
     * Start a single instance of a consul agent in -dev mode
     */
    public static function startSingle() {
        if (file_exists(self::PID_FILE)) {
            self::stopSingle();
        }

        shell_exec(self::START_SINGLE_CMD);

        if (file_exists(self::PID_FILE)) {
            echo "\nconsul pid: " . file_get_contents(self::PID_FILE) . "\n";
        } else {
            echo "\nunable to locate consul.pid file!\n";
        }

        // sleep to allow consul to setup
        sleep(5);
    }

    /**
     * Stop running instance
     */
    public static function stopSingle() {
        if (file_exists(self::PID_FILE)) {
            shell_exec(self::STOP_SINGLE_CMD);
            if (file_exists(self::PID_FILE)) {
                unlink(self::PID_FILE);
            }
            sleep(2);
        }
    }
}