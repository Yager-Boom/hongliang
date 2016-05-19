<?php defined('BASEPATH') or exit('No direct script access allowed');
class Account extends Admin_Controller {
    protected $section = "account";

    protected $validationRules = array(

        "account_name" => array(
            "field" => "account_name",
            "label" => "account_name",
            "rules" => "required",
        ),
    );
    protected $giftRules =array(
        "money" => array(
            "field" => "money",
            "label" => "money",
            "rules" => "required",
        ),
        "action" => array(
            "field" => "action",
            "label" => "action",
            "rules" => "required",
        ),
    );
    protected $turnRules =array(
        "money" => array(
            "field" => "money",
            "label" => "money",
            "rules" => "required",
        ),
        "account" => array(
            "field" => "account",
            "label" => "account",
            "rules" => "required",
        ),
    );
    //欄位驗證
    public function __construct() {
//帳戶餘額
        parent::__construct();
        $this->load->library("form_validation");
        $this->load->model("mmm_history_m");
        $this->load->model("mmm_account_m");
        $this->load->helper('date');
    }
    public function index() {
        $account = $this->mmm_account_m->get_all();
        $this->template
            ->set("account",$account)
            ->build("admin/account/index");
    }
    public function create(){
        //新增帳戶
        $this->form_validation->set_rules($this->validationRules);
        $time = date("Ymd");
        if ($this->form_validation->run()) {
            $account_name = $this->input->post("account_name");
            $insertData = array(
                "account_name" => $account_name,
                "money_now" => 0
            );
            if ($this->mmm_account_m->insert($insertData)) {
                /*這一段的邏輯可能要新增判斷，名稱是唯一值*/
                $temp =$this->mmm_account_m->get_by("account_name",$account_name);
                $id = $temp->id;
                $insertData_history = array(
                    "account_name" => $account_name,
                    "money_now" => 0,
                    "action" => "carate",
                    "date" => $time,
                    "money" => 0,
                    "account_id" => $id,
                    "remark" => $account_name."建立"
                );
                $this->mmm_history_m->insert($insertData_history);
                $this->session->set_flashdata("success", "新增帳戶成功");
                //session:安裝成功或失敗的提示
                if ($this->input->post("btnAction") == "save")
                //
                    redirect("admin/meal_money_management/edit/" . $id);
                if ($this->input->post("btnAction") == "save_exit")
                    redirect("admin/meal_money_management");
            }
            $this->session->set_flashdata("error", "新增帳戶失敗");
            redirect("admin/meal_money_management/edit/" . $id);
        }
        $account = array();
        foreach($this->validationRules as $key => $value ){
            $account[$value["field"]] = null;
        }
        $this->template
            ->set("account",$account)
            ->build("admin/account/form");
    }
    public function edit($id){
        $id or rediect("admin/meal_money_management");

        $account_data = $this->mmm_account_m->get_by("id",$id);

        $time = date("Ymd");

        $this->form_validation->set_rules($this->validationRules);

        if ($this->form_validation->run()) {
            $account_name = $this->input->post("account_name");
            $updatedData = array(
                "account_name" => $account_name
            );

            if ($this->mmm_account_m->update($id,$updatedData)) {
                /*如果"mmm_account_m"update了WHERE '$id' 的$updatedDATA的資料
                這裡會回傳ID值*/
                $updatedData_history = array(
                    "account_name" => $account_name,
                    "money_now" => $account_data->money_now,
                    "action" => "ChangeName",
                    "date" => $time,
                    "money" => 0,
                    "account_id" => $id,
                    "reamrk" => "原帳戶名稱為".$account_data.$account_name
                );
                $this->mmm_history_m->insert($updatedData_history);

                $this->session->set_flashdata("success", "修改帳戶成功");
                if ($this->input->post("btnAction") == "save")
                    redirect("admin/meal_money_management/edit/" . $id);
                //$id為參數，redirect去後面的rul再接id參數
                if ($this->input->post("btnAction") == "save_exit")
                    redirect("admin/meal_money_management");
            }
            $this->session->set_flashdata("error", "修改帳戶失敗");
            redirect("admin/meal_money_management/edit/" . $id);
        }

        $account["account_name"] = $account_data->account_name;

        $this->template
            ->set("account",$account)
            ->build("admin/account/form");
    }

    public function delete($id){
        $account = $this->mmm_account_m->get_by("id",$id);
        $account or redirect("admin/meal_money_management");
        if($this->account_m->delete_by("id",$id)){
            $time = date("Ymd-h:i,a");
            $insertData_history = array(
                "account_name" => $account->account_name,
                "money_now" => 0,
                "action" => "delete",
                "date" => $time,
                "money" => 0,
                "account_id" => $id,
                "remark" => $account->account_name."帳戶刪除"
            );
            $this->mmm_history_m->insert($insertData_history);
            $this->session
                ->set_flashdata("success", "刪除帳戶成功");
        }else{
            $this->session
                ->set_flashdata("error", "刪除帳戶失敗");
        }
        redirect("admin/meal_money_management");
    }

    public function gift_expenditure($id){

        $id or rediect("admin/meal_money_management");

        $account_data = $this->mmm_account_m->get_by("id",$id);
        $time = date("Ymd-h:i,a");

        $this->form_validation->set_rules($this->giftRules);

        if ($this->form_validation->run()) {

            $money = $this->input->post("money");
            $action = $this->input->post("action");

            if($action == "gift"){
                $result = $account_data->money_now + $money;
            }else{
                $result = $account_data->money_now - $money;
            }

            $updatedData = array(
                "account_name" => $account_data->account_name,
                "money_now" => $result,
            );
//只有account_name、money_now需要根據account做update
            $updatedData_history = array(
                "account_name" => $account_data->account_name,
                "money_now" => $result,
                "action" => $action,
                "date" => $time,
                "money" => $money,
                "account_id" => $id,
                "remark" => $this->input->post("remark")
            );

            if ($this->mmm_account_m->update($id,$updatedData)&&$this->mmm_history_m->insert($updatedData_history)) {
                $this->session->set_flashdata("success", "儲值/支出成功");
                if ($this->input->post("btnAction") == "save")
                    redirect("admin/meal_money_management/gift_expenditure/".$id);
                if ($this->input->post("btnAction") == "save_exit")
                    redirect("admin/meal_money_management");
            }
            $this->session->set_flashdata("error", "修改帳戶失敗");
            redirect("admin/meal_money_management/gift_expenditure/".$id);
        }
        $action_all=array();
        $action_all["gift"] = "gift";
        $action_all["expenditure"] = "expenditure";
//新增陣列action_all
        $this->template
            ->set("action_all",$action_all)
            ->build("admin/account/action");
    }
    public function Turn_out_in($id){
        $id or rediect("admin/meal_money_management");

        $account_data = $this->mmm_account_m->get_by("id",$id);
        $time = date("Ymd-h:i,a");
        $this->form_validation->set_rules($this->turnRules);

        if ($this->form_validation->run()) {
            $money = $this->input->post("money");
            $account = $this->input->post("account");
            $account2_data = $this->mmm_account_m->get_by("id",$account);


            $result = $account_data->money_now - $money;
            $result2 = $account2_data->money_now + $money;

            $updatedData = array(
                "money_now" => $result,
            );

            $updatedData2 = array(
                "money_now" => $result2,
            );

            $updatedData_history = array(
                "account_name" => $account_data->account_name,
                "money_now" => $result,
                "action" => "turn out",
                "date" => $time,
                "money" => $money,
                "account_id" => $id,
                "reamrk" => $this->input->post("reamrk")
            );

            $updatedData2_history = array(
                "account_name" => $account2_data->account_name,
                "money_now" => $result2,
                "action" => "turn in",
                "date" => $time,
                "money" => $money,
                "account_id" => $account,
                "reamrk" => "轉入來源為".$account_data->account_name
            );

            if ($this->mmm_account_m->update($id,$updatedData)
                &&$this->mmm_account_m->update($account,$updatedData2)
                &&$this->mmm_history_m->insert($updatedData_history)
                &&$this->mmm_history_m->insert($updatedData2_history)){

                $this->session->set_flashdata("success", "轉出成功");
                if ($this->input->post("btnAction") == "save")
                    redirect("admin/meal_money_management/Turn_out_in/".$id);
                if ($this->input->post("btnAction") == "save_exit")
                    redirect("admin/meal_money_management");
            }
            $this->session->set_flashdata("error", "轉出失敗");
            redirect("admin/meal_money_management/Turn_out_in/".$id);

        }

        $account_all_temp = $this->mmm_account_m->get_all();
        foreach($account_all_temp as $data){
            $account_all[$data->id] = $data->account_name;
        }
        unset($account_all[$id]);
        $this->template
            ->set("account_all",$account_all)
            ->build("admin/account/turn");
    }
}