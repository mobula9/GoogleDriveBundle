<?php

namespace Kasifi\GoogleDriveBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NotificationsController extends Controller
{
    /**
     * @Route("/google-drive/notifications", name="kasifi_gdrive_notifications")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function notificationsAction(Request $request)
    {
        if (!$request->headers->get('x-goog-channel-id')) {
            throw new BadRequestHttpException('This URL should be only call by Google Drive only.');
        }

        $notificationHandler = $this->get('kasifi_gdrive.notification_handler');

        $notificationHandler->handleNotification($request);

        return new JsonResponse();
    }
}
