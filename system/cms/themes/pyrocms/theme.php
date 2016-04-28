<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Pyrocms extends Theme {

    public $name			= 'CMS 預設後臺佈景主題';
    public $author			= 'HongLiangIT';
    public $author_website	= 'http://hongliangit.com/';
    public $website			= 'http://hongliangit.com/';
    public $description		= 'CMS 預設後臺主題';
    public $version			= '1.0.0';
	public $type			= 'admin';
	public $options 		= array(
        'pyrocms_recent_comments' => array(
                'title' 		=> '最新留言',
                'description'   => '是否在後台首頁呈現最新留言？',
                'default'       => 'yes',
                'type'          => 'radio',
                'options'       => 'yes=是|no=否',
                'is_required'   => true
        ),
																
        'pyrocms_news_feed' => array(
            'title' => '版本更新資訊',
            'description'   => '是否在後台首頁呈現CMS版本更新資訊？',
            'default'       => 'yes',
            'type'          => 'radio',
            'options'       => 'yes=是|no=否',
            'is_required'   => true
        ),
																
        'pyrocms_quick_links' => array(
            'title' => '快速連結',
            'description'   => '是否在後台首頁呈現常用快速連結？',
            'default'       => 'yes',
            'type'          => 'radio',
            'options'       => 'yes=是|no=否',
            'is_required'   => true
        ),

        'pyrocms_analytics_graph' => array(
            'title' => 'Google Analytics 流量分析圖表',
            'description'   => '是否在後台首頁呈現GA 流量分析圖表？',
            'default'       => 'yes',
            'type'          => 'radio',
            'options'       => 'yes=是|no=否',
            'is_required'   => true
        ),
    );
	
	/**
	 * Run() is triggered when the theme is loaded for use
	 *
	 * This should contain the main logic for the theme.
	 *
	 * @access	public
	 * @return	void
	 */
	public function run()
	{
		// only load these items on the dashboard
		if ($this->module == '' && $this->method != 'login' && $this->method != 'help')
		{
			// don't bother fetching the data if it's turned off in the theme
			if ($this->theme_options->pyrocms_analytics_graph == 'yes')		self::get_google_analytics();//self::get_analytics();
			if ($this->theme_options->pyrocms_news_feed == 'yes')			self::get_rss_feed();
			if ($this->theme_options->pyrocms_recent_comments == 'yes')		self::get_recent_comments();
		}
	}

    /**
     * get_google_analytics
     */
	public function get_google_analytics()
	{
        $email = $this->settings->ga_service_account_email;
        $key = $this->settings->ga_service_account_private_key;

		if ( ! $email OR ! $key) {
            return;
        }
		$this->load->library('GoogleAnalytics', array(
			'email' => $email,
			'private_key' => $key
		), 'ga');
        // Not false? Return it
        if ($cached_response = $this->pyrocache->get('ga')) {
            $data['analytic_visits'] = $cached_response['analytic_visits'];
            $data['analytic_views'] = $cached_response['analytic_views'];
        } else {
            try {

                $end_date = date('Y-m-d');
                $start_date = date('Y-m-d', strtotime('-1 month'));

                $this->ga->setDateRange($start_date, $end_date);

                /** @var Google_Service_Analytics_GaData $views */
                $visits = $this->ga->getVisitors()->getRows();
                /** @var Google_Service_Analytics_GaData $views */
                $views = $this->ga->getPageviews()->getRows();

                $flot_data_visits = '[]';
                $flot_data_views = '[]';

                /* build tables */
                if (count($visits))
                {
                    $flot_datas_visits = array();
                    $flot_datas_views = array();

                    foreach ($visits as $i => $visit)
                    {
                        $date = $visit[0];
                        $visit = $visit[1];
                        
                        $year = substr($date, 0, 4);
                        $month = substr($date, 4, 2);
                        $day = substr($date, 6, 2);

                        $utc = mktime(date('h') + 1, null, null, $month, $day, $year) * 1000;

                        $flot_datas_visits[] = '[' . $utc . ',' . $visit . ']';
                        $flot_datas_views[] = '[' . $utc . ',' . $views[$i][1] . ']';
                    }

                    $flot_data_visits = '[' . implode(',', $flot_datas_visits) . ']';
                    $flot_data_views = '[' . implode(',', $flot_datas_views) . ']';
                }

                $data['analytic_visits'] = $flot_data_visits;
                $data['analytic_views'] = $flot_data_views;

            } catch (Exception $e) {
                $data['messages']['notice'] = sprintf(lang('cp:google_analytics_no_connect'), anchor('admin/settings', lang('cp:nav_settings')));
            }
        }

        // make it available in the theme
        $this->template->set($data);
	}

	/**
	 * @deprecated
	 */
	public function get_analytics()
	{
		if ($this->settings->ga_email and $this->settings->ga_password and $this->settings->ga_profile)
		{
			// Not false? Return it
			if ($cached_response = $this->pyrocache->get('analytics'))
			{
				$data['analytic_visits'] = $cached_response['analytic_visits'];
				$data['analytic_views'] = $cached_response['analytic_views'];
			}

			else
			{
				try
				{
					$this->load->library('analytics', array(
						'username' => $this->settings->ga_email,
						'password' => $this->settings->ga_password
					));

					// Set by GA Profile ID if provided, else try and use the current domain
					$this->analytics->setProfileById('ga:'.$this->settings->ga_profile);

					$end_date = date('Y-m-d');
					$start_date = date('Y-m-d', strtotime('-1 month'));

					$this->analytics->setDateRange($start_date, $end_date);

					$visits = $this->analytics->getVisitors();
					$views = $this->analytics->getPageviews();

					/* build tables */
					if (count($visits))
					{
						foreach ($visits as $date => $visit)
						{
							$year = substr($date, 0, 4);
							$month = substr($date, 4, 2);
							$day = substr($date, 6, 2);

							$utc = mktime(date('h') + 1, null, null, $month, $day, $year) * 1000;

							$flot_datas_visits[] = '[' . $utc . ',' . $visit . ']';
							$flot_datas_views[] = '[' . $utc . ',' . $views[$date] . ']';
						}

						$flot_data_visits = '[' . implode(',', $flot_datas_visits) . ']';
						$flot_data_views = '[' . implode(',', $flot_datas_views) . ']';
					}

					$data['analytic_visits'] = $flot_data_visits;
					$data['analytic_views'] = $flot_data_views;

					// Call the model or library with the method provided and the same arguments
					$this->pyrocache->write(array('analytic_visits' => $flot_data_visits, 'analytic_views' => $flot_data_views), 'analytics', 60 * 60 * 6); // 6 hours
				}

				catch (Exception $e)
				{
					$data['messages']['notice'] = sprintf(lang('cp:google_analytics_no_connect'), anchor('admin/settings', lang('cp:nav_settings')));
				}
			}

			// make it available in the theme
			$this->template->set($data);
		}
	}
	
	public function get_rss_feed()
	{
		// Dashboard RSS feed (using SimplePie)
		$this->load->library('simplepie');
		$this->simplepie->set_cache_location($this->config->item('simplepie_cache_dir'));
		$this->simplepie->set_feed_url($this->settings->dashboard_rss);
		$this->simplepie->init();
		$this->simplepie->handle_content_type();

		// Store the feed items
		$data['rss_items'] = $this->simplepie->get_items(0, $this->settings->dashboard_rss_count);
		
		// you know
		$this->template->set($data);
	}
	
	public function get_recent_comments()
	{
		$this->load->library('comments/comments');
		$this->load->model('comments/comment_m');

		$this->load->model('users/user_m');

		$this->lang->load('comments/comments');

		$recent_comments = $this->comment_m->get_recent(5);
		$data['recent_comments'] = $this->comments->process($recent_comments);
		
		$this->template->set($data);
	}
}

/* End of file theme.php */