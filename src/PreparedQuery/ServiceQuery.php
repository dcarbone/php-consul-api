<?php namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class ServiceQuery
 * @package DCarbone\PHPConsulAPI\PreparedQuery
 */
class ServiceQuery extends AbstractModel
{
    /** @var string */
    public $Service = '';
    /** @var string */
    public $Near = '';
    /** @var QueryDatacenterOptions */
    public $Failover = null;
    /** @var bool */
    public $OnlyPassing = false;
    /** @var array */
    public $Tags = [];

    /**
     * ServiceQuery constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->Failover = new QueryDatacenterOptions();
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->Service;
    }

    /**
     * @param string $Service
     * @return ServiceQuery
     */
    public function setService($Service)
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return string
     */
    public function getNear()
    {
        return $this->Near;
    }

    /**
     * @param string $Near
     * @return ServiceQuery
     */
    public function setNear($Near)
    {
        $this->Near = $Near;
        return $this;
    }

    /**
     * @return QueryDatacenterOptions
     */
    public function getFailover()
    {
        return $this->Failover;
    }

    /**
     * @param QueryDatacenterOptions $Failover
     * @return ServiceQuery
     */
    public function setFailover(QueryDatacenterOptions $Failover)
    {
        $this->Failover = $Failover;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isOnlyPassing()
    {
        return $this->OnlyPassing;
    }

    /**
     * @param boolean $OnlyPassing
     * @return ServiceQuery
     */
    public function setOnlyPassing($OnlyPassing)
    {
        $this->OnlyPassing = $OnlyPassing;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->Tags;
    }

    /**
     * @param array $Tags
     * @return ServiceQuery
     */
    public function setTags(array $Tags)
    {
        $this->Tags = [];
        foreach($Tags as $tag)
        {
            $this->addTag($tag);
        }
        return $this;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addTag($tag)
    {
        $this->Tags[] = $tag;
        return $this;
    }
}