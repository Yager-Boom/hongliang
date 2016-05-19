<div class="one_full">
    <section class="title">
        <h4>交易紀錄</h4>
    </section>
    <section class="item">
        <div class="content">
            <table>
                <thead>
                <tr>
                    <th>交易帳號</th>
                    <th>動作</th>
                    <th>金額</th>
                    <th>日期</th>
                    <th>餘額</th>
                    <th>備註</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($history as $data){ ?>
                    <tr>
                        <th><?= $data->account_name ?></th>
                        <th><?= $data->action ?></th>
                        <th><?= $data->money ?></th>
                        <th><?= $data->date ?></th>
                        <th><?= $data->money_now ?></th>
                        <th><?= $data->remark ?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>