
<section class="title">
    <h4>帳戶管理</h4>
<!--        <h4>--><?//= $this->mothod == "create" ? "新增帳戶":"編輯帳戶" ?><!--</h4>-->
</section>
<section class="item">
    <div class="content">
        <?= form_open() ?>
        <ul>
            <li>
                <label>請輸入帳戶名稱</label>
            </li>
            <div class="inout">
                <input type="text" name="account_name" value="<?= $account["account_name"] ?>">
            </div>
        </ul>
        <ul>
            <div class="buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
            </div>
        </ul>
        <?= form_close() ?>
        <!--buttons分為save、save_exit、cancel三種-->
    </div>
</section>
