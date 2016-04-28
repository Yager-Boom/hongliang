<div class="views/edit/form">
    <section class="title">
        <h4>儲值</h4>
    </section>
    <section class="item">
        <?php echo form_open() ?>

        <table align="1">
            <ul>
                <li>
                    <label for="name">名稱</label>
                    <select name="name">
                        <?php foreach ($names as $name): ?>
                            <option><?php echo $name->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <label for="class">類別</label>
                    <select name="type">
                        <option value="儲值">儲值</option>
                        <option value="支出">支出</option>
                    </select>
                </li>
                <li>
                    <label for="money">金額</label>
                    <input type="text" name="money" id="money">
                </li>
                <li>
                    <label for="detail">內容</label><textarea rows="1" cols="5" name="detail" id="detail"></textarea>
                </li>
                <li>
                    <button>送出</button>
                </li>
            </ul>
            <?php echo form_close() ?>
        </table>
    </section>
</div>
