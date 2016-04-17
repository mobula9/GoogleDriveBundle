<?php

namespace Kasifi\GoogleDriveBundle;

use Google_Auth_AssertionCredentials;
use Google_Auth_OAuth2;
use Google_Client;
use Google_Service_Drive;

class DriveConnector
{
    /** @var string */
    private $googleP12FilePath;

    /** @var string */
    private $googleDriveClientEmail;

    /** @var string For Google Apps purpose? */
    private $googleAuthSub;

    /** @var Google_Service_Drive */
    private $service;

    public function __construct($googleP12FilePath, $googleDriveClientEmail, $googleAuthSub)
    {
        $this->googleP12FilePath = $googleP12FilePath;
        $this->googleDriveClientEmail = $googleDriveClientEmail;
        $this->googleAuthSub = $googleAuthSub;
    }

    /**
     * @return Google_Service_Drive
     */
    public function getService()
    {
        if (!$this->service) {
            $privateKey = file_get_contents($this->googleP12FilePath);
            $scopes = ['https://www.googleapis.com/auth/drive'];
            $credentials = new Google_Auth_AssertionCredentials(
                $this->googleDriveClientEmail, $scopes, $privateKey, 'notasecret',
                'http://oauth.net/grant_type/jwt/1.0/bearer', $this->googleAuthSub
            );
            $client = new Google_Client();
            $client->setAssertionCredentials($credentials);
            $auth = $client->getAuth();
            /** @var Google_Auth_OAuth2 $auth */
            if ($auth->isAccessTokenExpired()) {
                $auth->refreshTokenWithAssertion();
            }
            $this->service = new Google_Service_Drive($client);
        }

        return $this->service;
    }
}
