<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Default extends Theme {

    public $name			= 'CMS 預設佈景主題';
    public $author			= 'HongLiangIT';
    public $author_website	= 'http://hongliangit.com/';
    public $website			= 'http://hongliangit.com/';
    public $description		= 'CMS 預設主題 - 兩欄式、固定寬度、水平選單';
    public $version			= '1.0.0';
	public $options 		= array('show_breadcrumbs' => 	array('title' 		=> '連結導覽',
																'description'   => '請問是否要顯示連結導覽？',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=是|no=否',
																'is_required'   => true),
									'layout' => 			array('title' => '版型',
																'description'   => '要使用何種形式之版型',
																'default'       => '2 column',
																'type'          => 'select',
																'options'       => '2 column=兩欄式|full-width=固定寬度|full-width-home=首頁固定寬度',
																'is_required'   => true),
								   );
}

/* End of file theme.php */
