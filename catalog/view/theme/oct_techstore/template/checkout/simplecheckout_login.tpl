<div class="simplecheckout-block" id="simplecheckout_login" <?php echo $has_error ? 'data-error="true"' : '' ?>>
    <div class="simplecheckout-block-content text-center">
        <div id="simple_login_header"><img style="cursor:pointer;" data-onclick="close" src="<?php echo $additional_path ?>catalog/view/image/close.png"></div>
        <?php if ($error_login) { ?>
        <div class="alert alert-danger simplecheckout-warning-block"><?php echo $error_login ?></div>
        <?php } ?>
        <fieldset>
            <div class="form-group">
                <input class="form-control" data-onkeydown="detectEnterAndLogin" type="text" name="email" placeholder="<?php echo $entry_email; ?>" value="<?php echo $email; ?>" />
            </div>
            <div class="form-group">
                
                <input class="form-control" data-onkeydown="detectEnterAndLogin" type="password" placeholder="<?php echo $entry_password; ?>" name="password" value="" /></label>
                <a class="pull-right" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
            </div>
            <a id="simplecheckout_button_login" data-onclick="login" class="button btn-primary button_oc btn" style="width:100%; margin-top:15px!important;"><span><?php echo $button_login; ?></span></a>
        </fieldset>
    </div>
</div>