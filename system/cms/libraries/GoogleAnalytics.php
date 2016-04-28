<?php
/**
 * Google Analytics Library
 * User: jackiewu
 * Date: 2016/1/22
 * Time: 下午 07:29
 */
require __DIR__ . '/GoogleService.php';

use CMS\Libraries\GoogleService;


/**
 * Class Google_Analytics
 * @package CMS\Libraries
 */
class GoogleAnalytics
{
    /**
     * @var \Google_Service_Analytics
     */
    protected $service;
    protected $profileId;
    private $_email, $_private_key;
    private $_startDate = '7daysAgo';
    private $_endDate = 'today';    // or 'yesterday'

    public function __construct($params = array())
    {
        $this->_email = $params['email'];
        $this->_private_key = $params['private_key'];

        $google_service = new GoogleService();
        $this->service = $google_service->getAnalyticsService($this->_email, $this->_private_key);
        $this->profileId = $this->getProfileId();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getProfileId()
    {
        $accounts = $this->service->management_accounts->listManagementAccounts();
        if (count($accounts) > 0) {
            $items = $accounts->getItems();
            /** @var \Google_Service_Analytics_Account $firstAccount */
            $firstAccount = $items[0];
            $firstAccountId = $firstAccount->getId();

            $properties = $this->service->management_webproperties->listManagementWebproperties($firstAccountId);
            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                /** @var \Google_Service_Analytics_Webproperty $firstProperty */
                $firstProperty = $items[0];
                $firstPropertyId = $firstProperty->getId();

                $profiles = $this->service->management_profiles->listManagementProfiles(
                    $firstAccountId,
                    $firstPropertyId
                );
                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    /** @var \Google_Service_Analytics_Profile $profile */
                    $profile = $items[0];
                    return $profile->getId();
                } else {
                    throw new \Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new \Exception('No properties found for this user.');
            }
        } else {
            throw new \Exception('No accounts found for this user.');
        }
    }

    /**
     * Get Google Analytics data for a view (profile).
     * for examples, metrics:
     * - ga:visits
     * - ga:sessions
     * - ga:pageviews
     * @param string $metrics
     * @param array $optParams
     * @return \Google_Service_Analytics_GaData
     */
    public function getGaResult($metrics = 'ga:sessions,ga:pageviews', $optParams = array())
    {
        return $this->service->data_ga->get(
            'ga:' . $this->profileId,
            $this->_startDate,
            $this->_endDate,
            $metrics,
            $optParams
        );
    }

    /**
     * Get Google Analytics Multi-Channel Funnels data for a view (profile).
     * @param string $metrics
     * @return \Google_Service_Analytics_McfData
     */
    public function getMcfResult($metrics = 'mcf:totalConversions,mcf:totalConversionValue', $optParams = array())
    {
        return $this->service->data_mcf->get(
            'ga:' . $this->profileId,
            $this->_startDate,
            $this->_endDate,
            $metrics,
            $optParams
        );
    }

    /**
     * Get Google Analytics real time data for a view (profile).
     * @param string $metrics
     * @return \Google_Service_Analytics_RealtimeData
     */
    public function getRealtimeResult($metrics = 'rt:activeUsers', $optParams = array())
    {
        return $this->service->data_realtime->get(
            'ga:' . $this->profileId,
            $metrics,
            $optParams
        );
    }

    /**
     * Get Google Analyics Service
     * @return \Google_Service_Analytics
     */
    public function getAnalyticService()
    {
        return $this->service;
    }

    /**
     * Sets the date range for GA data
     *
     * @param string $sStartDate (YYY-MM-DD)
     * @param string $sEndDate   (YYY-MM-DD)
     */
    public function setDateRange($sStartDate, $sEndDate)
    {
        $this->_startDate = $sStartDate;
        $this->_endDate = $sEndDate;
    }

    /**
     * Sets de data range to a given month
     *
     * @param int $iMonth
     * @param int $iYear
     */
    public function setMonth($iMonth, $iYear)
    {
        $this->_startDate = date('Y-m-d', strtotime($iYear . '-' . $iMonth . '-01'));
        $this->_endDate = date('Y-m-d', strtotime($iYear . '-' . $iMonth . '-' . date('t', strtotime($iYear . '-' . $iMonth . '-01'))));
    }


    /**
     * Get visitors for given period
     *
     */
    public function getVisitors()
    {
        return $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:date',
            'sort' => 'ga:date'
        ));
    }

    /**
     * Get pageviews for given period
     *
     */
    public function getPageviews()
    {
        return $this->getGaResult('ga:pageviews', array(
            'dimensions' => 'ga:date',
            'sort' => 'ga:date'
        ));
    }

    /**
     * Get pageviews for given period
     *
     */
    public function getTimeOnSite()
    {
        return $this->getGaResult('ga:timeOnSite', array(
            'dimensions' => 'ga:date',
            'sort' => 'ga:date'
        ));
    }

    /**
     * Get visitors per hour for given period
     *
     */
    public function getVisitsPerHour()
    {
        return $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:hour',
            'sort' => 'ga:hour'
        ));
    }

    /**
     * Get Browsers for given period
     *
     */
    public function getBrowsers()
    {
        $aData = $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:browser,ga:browserVersion',
            'sort' => 'ga:visits'
        ));
        //arsort($aData);
        return $aData;
    }

    /**
     * Get Operating System for given period
     *
     */
    public function getOperatingSystem()
    {
        $aData = $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:operatingSystem',
            'sort' => 'ga:visits'
        ));
        // sort descending by number of visits
        //arsort($aData);
        return $aData;
    }

    /**
     * Get screen resolution for given period
     *
     */
    public function getScreenResolution()
    {
        $aData = $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:screenResolution',
            'sort' => 'ga:visits'
        ));

        // sort descending by number of visits
        //arsort($aData);
        return $aData;
    }

    /**
     * Get referrers for given period
     *
     */
    public function getReferrers()
    {
        $aData = $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:source',
            'sort' => 'ga:source'
        ));

        // sort descending by number of visits
        //arsort($aData);
        return $aData;
    }

    /**
     * Get search words for given period
     *
     */
    public function getSearchWords()
    {
        $aData = $this->getGaResult('ga:visits', array(
            'dimensions' => 'ga:keyword',
            'sort' => 'ga:keyword'
        ));
        // sort descending by number of visits
        //arsort($aData);
        return $aData;
    }


}