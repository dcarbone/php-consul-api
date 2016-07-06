<?php namespace DCarbone\SimpleConsulPHP\Agent;

use DCarbone\SimpleConsulPHP\AbstractResponseModel;

/**
 * Class AgentCheck
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentCheck extends AbstractResponseModel
{
    /** @var array */
    protected static $default = array(
        'Node' => null,
        'CheckID' => null,
        'Name' => null,
        'Status' => null,
        'Notes' => null,
        'Output' => null,
        'ServiceID' => null,
        'ServiceName' => null,
    );

    /**
     * @return string
     */
    public function getNode()
    {
        return $this['Node'];
    }

    /**
     * @return string
     */
    public function getCheckID()
    {
        return $this['CheckID'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this['Name'];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this['Status'];
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this['Nodes'];
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this['Output'];
    }

    /**
     * @return string
     */
    public function getServiceID()
    {
        return $this['ServiceID'];
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this['ServiceName'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCheckID();
    }
}