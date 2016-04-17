<?php

namespace Kasifi\GoogleDriveBundle;

use Google_Http_Request;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_FileList;
use Psr\Log\LoggerAwareInterface;

class DriveFinder implements LoggerAwareInterface
{
    use Loggable;

    /**
     * @var DriveConnector
     */
    private $driveConnector;

    /**
     * @var string
     */
    private $downloadDirectory;

    public function __construct(DriveConnector $driveConnector, $downloadDirectory)
    {

        $this->driveConnector = $driveConnector;
        $this->downloadDirectory = $downloadDirectory;
    }

    /**
     * @param     $query
     * @param int $max
     *
     * @return Google_Service_Drive_FileList $items
     */
    public function searchResource($query, $max = 200)
    {
        $driveService = $this->driveConnector->getService();
        $items = [];
        $pageToken = null;
        do {
            $list = $driveService->files->listFiles(['q' => $query]);
            /** @var Google_Service_Drive_FileList $responseItems */
            $responseItems = $list->getItems();
            foreach ($responseItems as $item) {
                if (count($items) > $max) {
                    break;
                }
                $items[] = $item;
            }
            if (count($items) < $max) {
                $pageToken = $list->getNextPageToken() ?: null;
            }
        } while ($pageToken !== null && count($items) <= $max);

        return $items;
    }

    /**
     * @param $fileId
     *
     * @return Google_Service_Drive_DriveFile
     */
    public function getFile($fileId)
    {
        return $this->driveConnector->getService()->files->get($fileId);
    }

    /**
     * @param String $folderId ID of the folder to print files from.
     *
     * @param string $mimeType
     *
     * @return Google_Service_Drive_DriveFile[]
     */
    public function getFolderChildren($folderId, $mimeType = 'pdf')
    {
        $pageToken = null;
        $items = [];

        do {
            $list = $this->driveConnector->getService()->files->listFiles([
                'q' => '"' . $folderId . '" in parents and mimeType contains "' . $mimeType . '" and trashed = false',
            ]);

            $items = array_merge($items, $list->getItems());
            $pageToken = $list->getNextPageToken();
        } while ($pageToken);

        return $items;
    }

    /**
     * Download a file's content.
     *
     * @param Google_Service_Drive_DriveFile $driveFile
     *
     * @param null                           $filePath
     *
     * @return string
     */
    public function fileDownload(Google_Service_Drive_DriveFile $driveFile, $filePath = null)
    {
        if (!$filePath) {
            $docDir = $this->downloadDirectory;
            $filePath = $docDir . '/' . sha1($driveFile->getEtag()) . '.pdf';
        }

        if (file_exists($filePath)) {
            return $filePath;
        }

        $request = new Google_Http_Request($driveFile->getDownloadUrl(), 'GET', [], null);
        $httpRequest = $this->driveConnector->getService()->getClient()->getAuth()->authenticatedRequest($request);

        if ($httpRequest->getResponseHttpCode() != 200) {
            $this->error('Error while downloading', ['title' => $driveFile->getTitle()]);

            return false;
        }
        file_put_contents($filePath, (string)$httpRequest->getResponseBody());
        $this->log('File downloaded', ['title' => $driveFile->getTitle()]);

        return $filePath;
    }
}
