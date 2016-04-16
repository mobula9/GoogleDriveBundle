<?php

namespace Kasifi\GoogleDriveBundle;

use Doctrine\Common\Collections\ArrayCollection;

interface ProcessorInterface
{
    /**
     * @param Notification $notification
     *
     * @return ArrayCollection
     * @internal param ArrayCollection $data
     *
     */
    public function processNotification(Notification $notification);
}
