<div class ="views/output/form">
    <section class="title">
        <h1>轉帳</h1>
    </section>
    <section class="item">
        <?php form_open()?>
            <table align="1">
                <ul>
                    <li>
                        <label for="name_output">轉帳人名稱</label>
                        <select id="name_output" name="name_output">
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
                        <label for="name_input">受款人名稱</label>
                        <select id="name_input" name="name_input">
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
<script>
    function name_select()
    {
        var a = document.getElementById("name_output"),
            b = document.getElementById("name_input"),
            c =
            [
                <?php foreach ($output as $name): ?>
                <?php echo '"'.$name->name.'",';?>
                <?php endforeach; ?>
            ],
            changeEvent = function (e) {
                //remove all
                var opts = b.getElementsByTagName('option'),
                    opts_len = opts.length;
                for (var i = 0, j = 0; i < opts_len; i++) {
                    var child = opts[j];
                    b.removeChild(child);
                }
                // add all
                for (var i = 0; i < c.length; i++) {
                    var opt = document.createElement('option');
                    opt.value = c[i];
                    opt.innerText = c[i];
                    b.appendChild(opt);
                }
                var remove_target = b.getElementsByTagName('option')[e.target.selectedIndex];
                b.removeChild(remove_target);
            };
        a.addEventListener('change', changeEvent);
        $('#name_output').chosen().change(function (e) {
            changeEvent.apply(this, [e]);
            b.parentNode.removeChild(document.getElementById('name_input_chzn'));
            b.className = '';
            $('#name_input').chosen();
        });
    }
    name_select();
</script>