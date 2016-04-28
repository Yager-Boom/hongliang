<div class ="views/admin/index">
    <section class="title">
        <h1>紀錄</h1>
    </section>
    <section class="item">
        <table border="1">
        <tr>
            <th>編號 &nbsp;</th>
            <th>姓名 &nbsp;</th>
            <th>類別 &nbsp;</th>
            <th>建立日期 &nbsp;</th>
            <th>金額 &nbsp;</th>
            <th>附註</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo date("ymd") ?><?php echo $item->id;?>&nbsp;</td>
                <td><?php echo $item->name;?>&nbsp;</td>
                <td><?php echo $item->type;?>&nbsp;</td>
                <td><?php echo $item->date;?>&nbsp;</td>
                <td><?php echo $item->money;?>&nbsp;</td>
                <td><?php echo $item->detail;?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </section>
    <!--foreach用法: foreach(array_expression as $value)-->
    <!--foreach迴圈在items裡撈值-->
</div>