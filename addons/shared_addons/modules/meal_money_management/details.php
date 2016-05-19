<?php
class Module_Meal_Money_Management extends Module
{
    public $version = "1.0.0" ;

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Meal Money Management',
                'tw' => '吃飯金管理'
            ),
            'description' => array(
                'en' => '123',
                'tw' => '456'
            ),
            'frontend' => true,
            /*前台*/
            'backend' => true,
            /*後台*/
            'skip_xss' => true,
            /*資安*/
            'menu' => 'content',

            /*'roles' => array(
                'put_live', 'edit_live', 'delete_live'
            ),*/

            'sections' => array(
                'account' => array(
                    'name' => "account",
                    'uri' => 'admin/meal_money_management',
                    'shortcuts' => array(
                        array(
                            'name' => 'account',
                            'uri' => 'admin/meal_money_management/create',
                            'class' => 'add',
                        ),
                    ),
                ),
                'history' => array(
                    'name' => 'history',
                    'uri' => 'admin/meal_money_management/history',
                ),
            ),
        );

        return $info;
    }
    public function install()
    {
        $this->dbforge->drop_table("mmm_account");
        $this->dbforge->drop_table("mmm_history");

        $this->install_tables(
            array(
                "mmm_account" => array(
                    "id" => array("type" => "INT", "constraint" => 11, "auto_increment" => true, "primary" => true),
                    "account_name" => array("type" => "VARCHAR", "constraint" => 100, "null" => false),
                    "money_now" => array("type" => "INT", "constraint" => 11, "null" => false)
                ),
                "mmm_history" => array(
                    "id" => array("type" => "INT", "constraint" => 11, "auto_increment" => true, "primary" => true),
                    "account_id" => array("type" => "INT", "constraint" => 11, "null" => false),
                    "account_name" => array("type" => "VARCHAR", "constraint" => 100, "null" => false),
                    "money_now" => array("type" => "INT", "constraint" => 11, "null" => false),
                    "action" => array("type" => "VARCHAR", "constraint" => 100, "null" => false),
                    "date" => array("type" => "date"),
                    "money" => array("type" => "INT", "constraint" => 11, "null" => false),
                    "remark" => array("type" => "VARCHAR", "constraint" => 100)
                )
            )
        );
        return true;
    }
    public function uninstall()
    {
        $this->dbforge->drop_table("mmm_account");
        $this->dbforge->drop_table("mmm_history");
        return true;
    }
    public function upgrade($old_version)
    {
        return true;
    }
}