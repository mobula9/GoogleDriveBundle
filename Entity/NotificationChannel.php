<?php

namespace Kasifi\GoogleDriveBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationChannel
 *
 * @ORM\Table(name="notification_channel")
 * @ORM\Entity()
 */
class NotificationChannel
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=511)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", unique=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="expiration", type="datetime", nullable=true)
     */
    private $expiration;

    /**
     * @var string
     *
     * @ORM\Column(name="resource_id", type="string", nullable=true)
     */
    private $resourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="monitored_resource", type="string", nullable=true)
     */
    private $monitoredResource;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return NotificationChannel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @param DateTime $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * @return mixed
     */
    public function getMonitoredResource()
    {
        return $this->monitoredResource;
    }

    /**
     * @param mixed $monitoredResource
     */
    public function setMonitoredResource($monitoredResource)
    {
        $this->monitoredResource = $monitoredResource;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}

