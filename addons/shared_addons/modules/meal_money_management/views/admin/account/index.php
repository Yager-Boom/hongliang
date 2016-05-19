<div class="one_full">
    <section class="title">
        <tr>
            <h4>帳戶清單</h4>
        </tr>
    </section>
    <section class="item">
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>帳戶名稱</th>
                        <th>餘額</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach( $account as $data ){?>
                    <tr>
                        <th>
                            <?= $data->account_name ?>
                            <a href="<?= site_url("admin/meal_money_management/edit/" . $data->id) ?>" class="button">變更帳戶名稱</a>
                            <a href="<?= site_url("admin/meal_money_management/gift_expenditure/" . $data->id) ?>" class="button">儲值/支出</a>
                            <a href="<?= site_url("admin/meal_money_management/Turn_out_in/" . $data->id) ?>" class="button">轉出</a>
                            <a href="<?= site_url("admin/meal_money_management/delete/" . $data->id) ?>" class="button">刪除</a>
                        </th>
                        <th><?= $data->money_now ?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>