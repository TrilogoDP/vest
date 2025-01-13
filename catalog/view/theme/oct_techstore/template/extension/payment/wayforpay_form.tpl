<form action="<?php echo $action ?>" method="post" id="payments">
    <?php
    foreach ($fields as $k => $v) {
        if(is_array($v)){
            foreach ($v as $vv) {
                echo "<input type=\"hidden\" name=\"{$k}[]\" value=\"{$vv}\" />";
            }
        } else {
            echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />";
        }

    }
 ?>
</form>