<section class="title">
    <h4>操作帳戶</h4>
</section>
<section class="item">
    <div class="content">
        <?= form_open() ?>
        <ul>
            <li>
                <label>選擇動作</label>
                <div class="input">
                    <?= form_dropdown("action", array("" => "請選擇動作") +$action_all) ?>
                </div>
            </li>
            <li>
                <label>輸入金額</label>
                <div class="input">
                    <input type="text"  name="money" value="0" />
                </div>
            </li>
            <li>
                <label>備註</label>
                <div class="input">
                    <input type="text"  name="remark" />
                </div>
            </li>
        </ul>
        <ul>
            <div class="buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
            </div>
        </ul>
        <?= form_close() ?>
    </div>
</section>