<div class ="views/money_now/index">
    <section class="title">
        <h1>帳戶餘額</h1>
    </section>
    <section class="item">
        <table border="1">
            <tr>
                <th>姓名 &nbsp;</th>
                <th>目前餘額 &nbsp;</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item->name;?>&nbsp;</td>
                    <td><?php echo $item->money_now;?>&nbsp;</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
    <!--foreach用法: foreach(array_expression as $value)-->
    <!--foreach迴圈在items裡撈值-->
</div>