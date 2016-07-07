<?php namespace DCarbone\SimpleConsulPHP\Status;

use DCarbone\SimpleConsulPHP\AbstractConsulClient;
use DCarbone\SimpleConsulPHP\QueryOptions;

/**
 * Class StatusClient
 * @package DCarbone\SimpleConsulPHP\Status
 */
class StatusClient extends AbstractConsulClient
{
    /**
     * @param QueryOptions|null $queryOptions
     * @return array|null
     */
    public function leader(QueryOptions $queryOptions = null)
    {
        return $this->execute('get', 'v1/status/leader', $queryOptions);
    }

    /**
     * @param QueryOptions|null $queryOptions
     * @return array|null
     */
    public function peers(QueryOptions $queryOptions = null)
    {
        return $this->execute('get', 'v1/status/peers', $queryOptions);
    }
}