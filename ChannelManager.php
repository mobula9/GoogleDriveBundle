<?php

namespace Kasifi\GoogleDriveBundle;

use Doctrine\ORM\EntityManager;
use Exception;
use Google_Service_Drive_Channel;
use Kasifi\GoogleDriveBundle\Entity\NotificationChannel;
use Psr\Log\LoggerAwareInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ChannelManager implements LoggerAwareInterface
{
    use Loggable;

    /**
     * @var DriveModifier
     */
    private $driveModifier;

    private $gdriveOauthHttpsCallbackPrefix;

    /** @var Router */
    private $router;

    /** @var EntityManager */
    private $em;

    public function __construct(
        EntityManager $em,
        Router $router,
        DriveModifier $driveModifier,
        $gdriveOauthHttpsCallbackPrefix
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->driveModifier = $driveModifier;
        $this->gdriveOauthHttpsCallbackPrefix = $gdriveOauthHttpsCallbackPrefix;
    }

    /**
     * @param                     $name
     * @param                     $type
     * @param                     $resourceId
     *
     * @return NotificationChannel
     */
    public function add($name, $type, $resourceId)
    {
        $channel = new NotificationChannel();
        $channel->setName($name);
        $channel->setType($type);
        $channel->setMonitoredResource($resourceId);
        $address = $this->gdriveOauthHttpsCallbackPrefix . $this->router->generate('kasifi_gdrive_notifications');
        $channel->setUrl($address);
        $this->em->persist($channel);
        $this->em->flush();

        return $this->update($channel);
    }

    /**
     * @param NotificationChannel $channel
     *
     * @return NotificationChannel
     */
    public function update(NotificationChannel $channel)
    {
        $delay = 60 * 60 * 24 * 7; // 7 days

        $driveChannel = new Google_Service_Drive_Channel();
        $driveChannel->setId($channel->getId());
        $driveChannel->setType('web_hook');
        $driveChannel->setAddress($channel->getUrl());

        $driveChannel->setExpiration((time() + $delay) * 1000); // default is 1 hour.
        $driveChannel = $this->driveModifier->watch($driveChannel, $channel->getMonitoredResource());
        $channel->setExpiration((new \DateTime())->setTimestamp($driveChannel->getExpiration() / 1000));
        $channel->setResourceId($driveChannel->getResourceId());
        $this->em->persist($channel);
        $this->em->flush();
        $this->log('Update', [
            'resource' => $channel->getMonitoredResource(),
            'id'       => $channel->getId(),
            'address'  => $channel->getUrl(),
            'drive_id' => $driveChannel->getId(),
            'exp'      => $channel->getExpiration()->format('Y-m-d H:i:s'),
        ]);

        return $channel;
    }

    public function remove(NotificationChannel $channel)
    {
        if (!$channel) {
            throw new Exception('Not found in DB.');
        }
        $driveChannel = new Google_Service_Drive_Channel();
        $driveChannel->setId($channel->getId());
        $driveChannel->setResourceId($channel->getResourceId());
        $this->driveModifier->stopWatch($driveChannel);
        $this->em->remove($channel);
        $this->em->flush();
    }
}
