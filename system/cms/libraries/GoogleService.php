<?php
/**
 * Google API Client Library for PHP (Service Account)
 * User: jackiewu
 * Date: 2016/1/22
 * Time: 下午 06:42
 */
namespace CMS\Libraries;

require __DIR__ . '/../../../vendor/autoload.php';

use Google_Auth_AssertionCredentials;
use Google_Auth_OAuth2;
use Google_Service_Analytics;

/**
 * Class GoogleService
 * @package CMS\Libraries
 */
class GoogleService
{
    /**
     * Get Request Auth
     * @param string $email
     * @param string $private_key
     * @param mixed $scope
     * @throws \Google_Exception
     */
    private function getRequestAuth($email, $private_key, $scopes)
    {
        $client = new \Google_Client();
        $cred = new Google_Auth_AssertionCredentials(
            $email,
            $scopes,
            $private_key
        );
        $client->setAssertionCredentials($cred);
        /** @var \Google_Auth_OAuth2 $auth */
        $auth = $client->getAuth();
        if ($auth->isAccessTokenExpired()) {
            $auth->refreshTokenWithAssertion($cred);
        }
        return $client;
    }

    /**
     * Get Google Analytics Service
     * @param string $email
     * @param string $private_key
     * @return \Google_Service_Analytics
     */
    public function getAnalyticsService($email, $private_key)
    {
        $client = $this->getRequestAuth(
            $email,
            $private_key,
            array(
                Google_Service_Analytics::ANALYTICS_READONLY
            )
        );
        return new \Google_Service_Analytics($client);
    }

    /**
     * Get Google Drive Service
     * @param string $email
     * @param string $private_key
     * @return \Google_Service_Drive
     */
    public function getDriveService($email, $private_key)
    {
        $client = $this->getRequestAuth(
            $email,
            $private_key,
            'https://www.googleapis.com/auth/drive'
        );
        return new \Google_Service_Drive($client);
    }
}