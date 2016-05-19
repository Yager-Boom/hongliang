<?php defined('BASEPATH') or exit('No direct script access allowed');
class History extends Admin_Controller {

    protected $section = "history";

    protected $validationRules = array(

        "account_name" => array(
            "field" => "account_name",
            "label" => "account_name",
            "rules" => "required",
        ),
        "money" => array(
            "field" => "money",
            "label" => "money",
            "rules" => "required",
        ),
    );

    public function __construct() {
        parent::__construct();
        $this->load->model("mmm_history_m");
    }

    public function index(){
        $history = $this->mmm_history_m->get_all();
        $this->template
            ->set("history",$history)
            ->build("admin/history/index");
    }

}