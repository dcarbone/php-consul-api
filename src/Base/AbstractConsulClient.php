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
 * Class AbstractConsulClient
 * @package DCarbone\SimpleConsulPHP\Base
 */
abstract class AbstractConsulClient
{
    /** @var string */
    private $_url = null;

    /** @var array */
    private $_curlOpts = array();

    /** @var array */
    private static $_defaultCurlOpts = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLINFO_HEADER_OUT => true,
    );

    /**
     * AbstractConsulClient constructor.
     * @param string $url
     * @param array $curlOpts
     */
    public function __construct($url, array $curlOpts = array())
    {
        $this->_url = rtrim(trim($url), "/");
        $this->_curlOpts = $curlOpts + self::$_defaultCurlOpts;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param int $opt
     * @param mixed $value
     * @return static
     */
    public function setCurlOpt($opt, $value)
    {
        if (!is_int($opt))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s::setCurlOpt - First argument must be integer that corresponds with a valid PHP CURL Option, %s seen.',
                get_class($this),
                gettype($opt)
            ));
        }

        // Don't let them override this.
        if (CURLOPT_RETURNTRANSFER === $opt)
            return $this;

        // Don't let them override this.
        if (CURLINFO_HEADER_OUT === $opt)
            return $this;

        $this->_curlOpts[$opt] = $value;

        return $this;
    }

    /**
     * @return static
     */
    public function resetCurlOpt()
    {
        $this->_curlOpts = self::$_defaultCurlOpts;
        return $this;
    }

    /**
     * @param string $uri
     * @param string $method
     * @return string
     * @throws \Exception
     */
    protected function execute($uri, $method = 'get')
    {
        $url = sprintf('%s/%s', $this->_url, ltrim(trim($uri), "/"));
        $ch = curl_init($url);

        $this->_setMethod($method);

        if (!curl_setopt_array($ch, $this->_curlOpts))
        {
            throw new \DomainException(sprintf(
                '%s - Unable to set specified Curl options, please ensure you\'re passing in valid constants.  Specified options: %s',
                get_class($this),
                json_encode($this->_curlOpts)
            ));
        }

        // TODO: At some point, maybe return metadata?

        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (is_string($data))
        {
            if (200 === (int)$info['http_code'])
            {
                $data = @json_decode($data, true);
                $err = json_last_error();

                if (JSON_ERROR_NONE === $err)
                    return $data;

                throw new \DomainException(sprintf(
                    '%s - Unable to parse response as JSON.  Message: %s',
                    get_class($this),
                    PHP_VERSION_ID >= 50500 ? json_last_error_msg() : (string)$err
                ));
            }

            throw new \Exception(sprintf(
                '%s - Error seen while executing "%s": %s.',
                get_class($this),
                $url,
                $data
            ));
        }

        throw new \UnexpectedValueException(sprintf(
            '%s - Invalid response seen executing query "%s".',
            get_class($this),
            $url
        ));
    }

    /**
     * @param string $method
     */
    private function _setMethod($method)
    {
        if (!is_string($method)) {
            throw new \InvalidArgumentException(sprintf('%s - Method must be string', get_class($this), gettype($method)));
        }

        if ('' === ($method = trim($method))) {
            throw new \InvalidArgumentException(sprintf('%s - Method must be non-empty string', get_class($this)));
        }

        switch(strtolower($method))
        {
            case 'get':
                $this->_GET();
                break;
            case 'put':
                $this->_PUT();
                break;
            case 'delete':
                $this->_DELETE();
                break;

            default:
                throw new \UnexpectedValueException(sprintf(
                    '%s - SimpleConsulPHP currently does not support queries made using the "%s" method.',
                    get_class($this),
                    $method
                ));
        }
    }

    private function _GET()
    {
        unset($this->_curlOpts[CURLOPT_CUSTOMREQUEST]);
        unset($this->_curlOpts[CURLOPT_POST]);

        $this->_curlOpts[CURLOPT_HTTPGET] = true;
    }

    private function _PUT()
    {
        unset($this->_curlOpts[CURLOPT_POST]);
        unset($this->_curlOpts[CURLOPT_HTTPGET]);

        $this->_curlOpts[CURLOPT_CUSTOMREQUEST] = 'PUT';
    }

    private function _DELETE()
    {
        unset($this->_curlOpts[CURLOPT_POST]);
        unset($this->_curlOpts[CURLOPT_HTTPGET]);

        $this->_curlOpts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
    }
}