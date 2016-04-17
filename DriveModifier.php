<?php

namespace Kasifi\GoogleDriveBundle;

use Google_Service_Drive_Channel;
use Google_Service_Drive_DriveFile;
use Psr\Log\LoggerAwareInterface;

class DriveModifier implements LoggerAwareInterface
{
    use Loggable;

    /**
     * @var DriveConnector
     */
    private $driveConnector;

    /**
     * @var DriveFinder
     */
    private $driveFinder;

    public function __construct(DriveConnector $driveConnector, DriveFinder $driveFinder)
    {
        $this->driveConnector = $driveConnector;
        $this->driveFinder = $driveFinder;
    }

    public function removeFile($id)
    {
        $driveService = $this->driveConnector->getService();
        $driveService->files->delete($id);
    }

    public function moveFile($fileId, $folderId)
    {
        $file = $this->driveFinder->getFile($fileId);
        $file->setParents([['kind' => 'drive#fileLink', 'id' => $folderId]]);
        $updatedFile = $this->driveConnector->getService()->files->update($fileId, $file);
        $this->log('File moved', ['src' => $fileId, 'dest' => $folderId]);

        return $updatedFile;
    }

    public function renameFile($fileId, $title)
    {
        $file = new Google_Service_Drive_DriveFile();
        $file->setTitle($title);
        $updatedFile = $this->driveConnector->getService()->files->patch($fileId, $file, [
            'fields' => 'title',
        ]);// todo : only one RQ !
        $this->log('File renamed', ['src' => $fileId, 'dest' => $title]);

        return $updatedFile;
    }

    public function fileUpload($fileName, $parentId, $localPath)
    {
        $file = new Google_Service_Drive_DriveFile();
        $file->setTitle($fileName);
        $file->setParents([["kind" => "drive#fileLink", "id" => $parentId]]);
        $this->log('File uploaded', ['file' => $fileName]);
        $result = $this->driveConnector->getService()->files->insert($file, [
            'data'       => file_get_contents($localPath),
            'mimeType'   => 'application/octet-stream',
            'uploadType' => 'multipart',
        ]);

        return $result;
    }

    /**
     * @param $name
     * @param $parenId
     *
     * @return \Google_Service_Drive_DriveFile
     */
    public function createFolder($name, $parenId)
    {
        $driveFolder = new \Google_Service_Drive_DriveFile();
        $driveFolder->setTitle($name);
        $driveFolder->setParents([['kind' => 'drive#fileLink', 'id' => $parenId]]);
        $driveFolder->setMimeType('application/vnd.google-apps.folder');
        $driveFolder = $this->driveConnector->getService()->files->insert($driveFolder);

        return $driveFolder;
    }

    public function watch(Google_Service_Drive_Channel $driveChannel, $monitoredResource = null)
    {
        if ($monitoredResource) {
            return $this->driveConnector->getService()->files->watch($monitoredResource, $driveChannel);
        }

        return $this->driveConnector->getService()->changes->watch($driveChannel);
    }

    public function stopWatch(Google_Service_Drive_Channel $driveChannel)
    {
        $this->driveConnector->getService()->channels->stop($driveChannel);
    }
}