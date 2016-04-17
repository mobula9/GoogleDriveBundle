<?php

namespace Kasifi\GoogleDriveBundle;

use Doctrine\ORM\EntityManager;
use Kasifi\GoogleDriveBundle\Entity\NotificationChannel;
use Psr\Log\LoggerAwareInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class NotificationHandler implements LoggerAwareInterface
{
    use Loggable;

    private $gdriveOauthHttpsCallbackPrefix;

    /** @var array */
    private $processors;

    /** @var Router */
    private $router;

    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em, Router $router, $gdriveOauthHttpsCallbackPrefix)
    {
        $this->em = $em;
        $this->router = $router;
        $this->gdriveOauthHttpsCallbackPrefix = $gdriveOauthHttpsCallbackPrefix;
    }

    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     *
     * @param Request $request
     *
     * @return Notification
     * @throws \Exception
     */
    public function handleNotification(Request $request)
    {
        $notification = $this->buildNotificationFromRequest($request);

        if (!$notification->getChannel()) {
            $this->error('No channel linked', ['notification' => $notification->getId()]);

            return;
        }

        if (!count($this->processors)) {
            throw new \Exception('No processor found to handle the incoming notification from Google Drive.');
        }
        foreach ($this->processors as $processor) {
            $processor->processNotification($notification);
        }
    }

    /**
     * @param $request
     *
     * @return Notification
     */
    private function buildNotificationFromRequest($request)
    {
        $channelId = $request->headers->get('x-goog-channel-id');
        $resourceState = $request->headers->get('x-goog-resource-state');
        $messageNumber = $request->headers->get('x-goog-message-number');
        $resourceId = $request->headers->get('x-goog-resource-id');
        $changed = $request->headers->get('x-goog-changed');

        $notification = new Notification();

        // Channel
        $channel = $this->em->getRepository('GoogleDriveBundle:NotificationChannel')->find($channelId);
        if ($channel) {
            $notification->setChannel($channel);
        }

        // Other
        $notification->setResourceId($resourceId);
        $notification->setNumber($messageNumber);
        $notification->setResourceState($resourceState);
        $notification->setChanged($changed);

        $this->log('New notification', [
            'channel' => $channel->getName(),
            'state'   => $resourceState,
            'changed' => $changed,
            'exp'     => $channel ? $channel->getExpiration()->format('Y-m-d H:i:s') : null,
        ]);

        return $notification;
    }
}