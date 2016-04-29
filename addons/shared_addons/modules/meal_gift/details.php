<?php

class Module_meal_gift extends Module
{
    public $version = '2.0.3';
    protected $section="project";
    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'meal_gift',
                'tw' => '員工餐費儲值系統'
            ),
            //專案名稱說明
            'description' => array(
                'en' => 'meal_gift',
                'tw' => '666666666'
            ),
            //附加模組說明
            'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',

            'roles' => array(
                'put_live', 'edit_live', 'delete_live'
            ),

            'shortcuts' => array(
                array(
                    'name' => 'meal_gift:save',
                    'uri' => 'admin/meal_gift/create/save',
                    'class' => 'add',
                ),array(
                    'name' => 'meal_gift:edit',
                    'uri' =>'admin/meal_gift/edit/edit',
                    'class' => 'add',
                ),array(
                    'name' => 'meal_gift:output',
                    'uri' =>'admin/meal_gift/output/output',
                    'class' => 'add',
                )
            ),
            'sections' => array(
                'money_now' => array(
                    'name' => '帳戶餘額',
                    'uri' => 'admin/meal_gift/account',
                ),
                'meal_gift' => array(
                    'name' => '交易查詢',
                    'uri' => 'admin/meal_gift'
                ),
            ),
        );
        return $info;
    }

    public function install()
    {
        $this->dbforge->drop_table('name');
        $this->dbforge->drop_table('meal_gift');
        $this->dbforge->drop_table('money_now');
        $this->install_tables(array(
            'meal_gift' => array(
                'id' => array('type' => 'int', 'constraint' => '11', 'primary' => true,'auto_increment'=>true),
                'date' => array('type' => 'date'),
                'detail' => array('type' => 'text', 'null' => false),
                'name' => array('type' => 'varchar', 'constraint' => '100', 'null' => false),
                'money' => array('type'=>'int','constaint'=>'11','null'=>false),
                'type' => array('type'=>'varchar','constraint'=>'100','null'=>false),
            ),
            'money_now'=>array(
                'id' => array('type' => 'int', 'constraint' => '11', 'primary' => true,'auto_increment'=>true),
                'money_now'=> array('type'  => 'int', 'constraint' => '11','null'=>false),
                'name'=>array('type' => 'varchar','constraint'=>'100','null'=> false)
            ),
            )
        );
        return true;
    }
    public function uninstall()
    {
        $this->dbforge->drop_table('name');
        $this->dbforge->drop_table('meal_gift');
        $this->dbforge->drop_table('money_now');
        return true;
    }
    //反安裝並移除資料表
    public function upgrade($old_version)
    {
        if (version_compare('2.0.3', $old_version, '>')) {
            //版本判斷
        }
        return true;
    }
}