<section class="title">
    <h4>操作帳戶</h4>
</section>
<section class="item">
    <div class="content">
        <?= form_open() ?>
        <ul>
            <li>
                <label>轉出金額</label>
                <div class="input">
                    <input type="text"  name="money" value="0" />
                </div>
            </li>
            <li>
                <label>轉入帳戶</label>
                <div class="input">
                    <?= form_dropdown("account", array("" => "請選擇帳戶") +$account_all) ?>
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