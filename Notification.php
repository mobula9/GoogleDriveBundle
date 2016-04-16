<?php

namespace Kasifi\GoogleDriveBundle;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Kasifi\GoogleDriveBundle\Entity\NotificationChannel;

class Notification
{
    private $id;

    /**
     * @var string
     */
    private $resourceId;

    /**
     * @var string
     *
     */
    private $resourceState;

    /**
     * @var string
     *
     */
    private $number;

    /**
     * @var string
     *
     */
    private $changed;

    /**
     * @var NotificationChannel
     *
     */
    private $channel;

    /**
     * @var \DateTime $created
     *
     */
    private $created;

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
     * @return string
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param string $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * @return string
     */
    public function getResourceState()
    {
        return $this->resourceState;
    }

    /**
     * @param string $resourceState
     */
    public function setResourceState($resourceState)
    {
        $this->resourceState = $resourceState;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return NotificationChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param NotificationChannel $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * @param string $changed
     */
    public function setChanged($changed)
    {
        $this->changed = $changed;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}

