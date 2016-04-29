<?php
class money_now extends Admin_Controller
{
    protected $section ="money_now";
    public $validation_rules = array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => 'required'
        ),
        //名稱驗證
        array(
            'field' => 'money',
            'label' => 'money',
            'rules' => 'is_natural_no_zero'
        ),
        //金額驗證
    );
    public function __construct()
    {
        parent::__construct();
        $this->load->model('meal_gift_m');
        $this->load->model('money_now_m');
        $this->lang->load('meal_gift');
        //load money檔
        $this->form_validation->set_rules($this->validation_rules);
    }
    public function index()
    {
        $meal_gift = $this->meal_gift_m->get_all();
        var_dump($meal_gift);
        //從meal_gift_m中撈出全部資料
        $this->template
            ->set('items', $meal_gift)
            ->build('admin/index');
        //資料type為變數type
        //樣板指向admin的index
    }
    public function create()//新增
    {
        if ($this->form_validation->run())
        {
            $data = array(
                'name' =>$this->input->post('name'),
                'money_now'=>0
            );
            $this->money_now_m->insert($data);
            redirect("admin/meal_gift/edit");
        }
        $this->template
            ->build('admin/form');
    }
    public function edit()//修改
    {
        if ($this->form_validation->run()) {
            $name_chose=$this->input->post('name');
            $money=$this->input->post('money');
            $type=$this->input->post('type');
            $name_change = $this->money_now_m->get_by("name",$name_chose);
            //money_now資料表的name欄位下選擇到的該筆資料
            if ($type=='儲值')
            {
                $result=$name_change->money_now+$money;
            }
            else
            //如果動作為儲值，則money_now+money
            {
                $result=$name_change->money_now-$money;
            };
            //否則money_now-money
            $data_money_now=array(
                'money_now'=>$result
            );
            $data_meal_gift = array(
                'name' => $name_chose,
                'type' => $type,
                'date' => date("Y-m-d H:i:s"),
                'money' => $money,
                'detail' => $this->input->post('detail'),
            );
        $this->meal_gift_m->insert($data_meal_gift);
        $this->money_now_m->update($name_change->id,$data_money_now);
        //insert money_now_m
        redirect("admin/meal_gift");
        }
        $name_all = $this->money_now_m->select("name")->group_by("name")->get_all();
        //從money_now_m裡撈全部
        $this->template
            ->set("names",$name_all)
            //names裡面是name_all
            ->build('edit/form');
        //樣板指向edit的form
    }

    public function output()
    {
        if ($this->form_validation->run())
        {
            $name_chose=$this->input->post('name');
            $money_output =$this->input->post('money');
            $money_now_output = $this->money_now_m->get_by("name",$name_chose);
            $money_now_input = $this->money_now_m->get_by("name",$name_chose);
            $result_output = $money_now_output - $money_output;
            $result_input = $money_now_input + $money_output;
            $data_meal_gift = array(
                'name' => $name_chose,
                'date' => date("Y-m-d H:i:s"),
                'money' => $money_output,
            );
            $this->meal_gift_m->insert($data_meal_gift);
            var_dump($result_input,$result_output);
            redirect("admin/meal_gift");
        }
        $name_all = $this->money_now_m->select("name")->group_by("name")->get_all();
        $this->money_now_m->update($name_chose->id,$result_output);
        $this->money_now_m->update($name_chose->id,$result_input);
        $this->template
            ->set("")
            ->set("")
            ->build('output/form');
    }
    public function account()
    {
       $money_now= $this->money_now_m->get_all();
        $this->template
            ->set("items",$money_now)
            ->build('money_now/index');
    }
}