<div class ="views/output/form">
    <section class="title">
        <h1>轉帳</h1>
    </section>
    <section class="item">
        <?php form_open()?>
            <table align="1">
                <ul>
                    <li>
                        <label for="name">轉帳人名稱</label>
                        <select name="name_output">
                            <?php foreach ($output as $name): ?>
                                <option><?php echo $name->name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    <li>
                        <label for="money">轉帳總金額</label>
                        <input type="text" name="money">
                    </li>
                    <li>
                        <label for="name">受款人名稱</label>
                        <select name="name_input">
                            <?php foreach ($output as $name): ?>
                                <option><?php echo $name->name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    <li>
                        <button>轉帳</button>
                    </li>
                </ul>
            </table>
        <?php form_close()?>
    </section>
</div>