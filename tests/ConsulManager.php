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
abstract class ConsulManager {

    const TMP_DIR = __DIR__.'/../tmp';
    const DATA_DIR = self::TMP_DIR.'/consul-data';

    const START_SINGLE_CMD = __DIR__.'/run_consul.sh';
    const STOP_SINGLE_CMD = __DIR__.'/stop_consul.sh';

    const PID_FILE = self::TMP_DIR.'/consul.pid';

    /**
     * Start up single instance of Consul Agent with specified flags
     *
     * @param string|array $flags
     */
    public static function startSingle($flags) {
        if (is_array($flags)) {
            $flags = self::compileFlags($flags);
        }
        if (!is_string($flags)) {
            throw new \InvalidArgumentException(sprintf(
                '$flags must be string or array of flags, %s seen.',
                gettype($flags)
            ));
        }
        if (file_exists(self::PID_FILE)) {
            self::stopSingle();
        }

        if (false === strpos($flags, 'log-level')) {
            $flags .= ' -log-level=debug';
        }

        shell_exec(self::START_SINGLE_CMD.' '.$flags);

        // sleep to allow consul to setup
        sleep(3);
    }

    /**
     * Start a single instance of a consul agent in "-dev" mode
     */
    public static function startSingleDev() {
        self::startSingle('-dev');
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
            sleep(1);
        }
    }

    /**
     * @param string $part
     * @return string
     */
    protected static function trimPart(string $part): string {
        return trim($part, " \t\n\r\0\x0B=");
    }

    /**
     * @param string|int                       $k
     * @param string|int|bool|float|null|array $v
     * @return string
     */
    protected static function compileFlag($k, $v): string {
        $f = '';
        // a few type -> string conversions
        switch (gettype($v)) {
            case 'string':
            case 'integer':
            case 'double':
                $v = (string)$v;
                break;
            case 'boolean':
                $v = $v ? 'true' : 'false';
                break;
            case 'NULL':
                $v = '';
                break;

            case 'array':
                foreach ($v as $vv) {
                    $f .= ' '.self::compileFlag($k, $vv);
                }
                return $f;

            default:
                throw new \InvalidArgumentException(sprintf(
                    '%s::compileFlag - Don\'t know what to do with value of type %s',
                    get_called_class(),
                    gettype($v)
                ));
        }

        // should have a string at this point.
        $v = self::trimPart($v);

        // if key is string, allow some flexibility...
        if (is_string($k)) {
            $k = self::trimPart($k);
            if ('' === $k) {
                if ('' === $v) {
                    // why are you doing this...
                    return '';
                }
                $f .= sprintf(' %s', escapeshellarg($v));
            } else if ('' === $v) {
                $f .= ' -'.escapeshellarg(ltrim($k, '-'));
            } else {
                $f .= sprintf(' -%s=%s', ltrim($k, '-'), escapeshellarg($v));
            }
        } else {
            // if an int key is seen, assume the value is the entire flag
            $f .= ' -'.escapeshellarg(ltrim($v, '-'));
        }

        return $f;
    }

    /**
     * @param array $flags
     * @return string
     */
    protected static function compileFlags(array $flags): string {
        $f = '';
        foreach ($flags as $k => $v) {
            $f .= self::compileFlag($k, $v);
        }
        return $f;
    }
}