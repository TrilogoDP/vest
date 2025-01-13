<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid csvprice_pro_container">
        <?php if (isset($warning) && !empty($warning)) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
        <?php if (isset($success) && !empty($success)) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
        <?php echo $app_header; ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <form action="<?php echo $action; ?>" method="post" id="form-license" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group form-group-sm">
                                <?php if ($entry_license_key != false) { ?>
                                    <label for="license_key" class="col-sm-5 control-label"><?php echo $entry_license_key; ?></label>
                                    <div class="col-sm-7">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control input-sm" name="license_key" id="license_key" placeholder="License Key">
                                            <span class="input-group-btn"> <button type="submit" form="form-license" data-toggle="tooltip" title="" class="btn btn-primary btn-sm" data-original-title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></button> </span>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <label class="col-sm-5 control-label"><?php echo $text_license_key; ?></label>
                                    <div class="col-sm-7">
                                        <p class="form-control-static">
                                            <a onclick="prompt('<?php echo $text_license_key; ?>', '<?php echo $license_key; ?>'); return false;"><?php echo $text_show; ?></a>
                                        </p>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-5 control-label"><?php echo $text_app_name; ?></label>
                                <div class="col-sm-7">
                                    <p class="form-control-static"><?php echo $app_name; ?></p>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-5 control-label"><?php echo $text_home_page; ?></label>
                                <div class="col-sm-7">
                                    <p class="form-control-static"><?php echo $home_page; ?></p>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-5 control-label"><?php echo $text_support_email; ?></label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">
                                        <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if (isset($show_support) && $show_support) { ?>
                        <div class="col-sm-12 col-md-6">
                            <div id="wrapperSupportRquest">
                                <h4><?php echo $entry_support_request; ?></h4>
                                <p><?php echo $text_support_request; ?></p>
                                <div class="well">
                                    <form method="post" enctype="multipart/form-data" id="formSupportRquest">
                                        <div class="form-group form-group-sm">
                                            <label for="inputEmail"><?php echo $entry_email; ?></label>
                                            <input type="text" id="inputEmail" value="<?php if(isset($email_from)) echo $email_from;?>" class="form-control">
                                        </div>
                                        <div class="form-group form-group-sm" style="border-top: none !important">
                                            <label for="inputMessage"><?php echo $entry_message; ?></label>
                                            <textarea class="form-control" rows="6" id="inputMessage"></textarea>
                                        </div>
                                        <button type="button" onclick="sendSupportRquest();" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-success">
                                            <?php echo $button_send; ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <script>
                                function sendSupportRquest() {
                                    var msg = $('#inputMessage').val(), email = $('#inputEmail').val();
                                    $('#inputMessage').val('');
                                    if (msg == null || msg.length === 0) {
                                        return false;
                                    }
                                    if (email == null || email.length === 0) {
                                        return false;
                                    }
                                    $('.alert-support').remove();
                                    $.ajax({
                                        url: '<?php echo $support; ?>',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: {'msg': msg, 'email': email},
                                        success: function (json) {
                                            if (json['success'] == 'OK') {
                                                $('#wrapperSupportRquest').prepend(
                                                    '<div class="alert alert-success alert-dismissible alert-support"><i class="fa fa-check-circle"></i> ' + json['message'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'
                                                );
                                            } else {
                                                $('#wrapperSupportRquest').prepend(
                                                    '<div class="alert alert-danger alert-dismissible alert-support"><i class="fa fa-check-circle"></i> ' + json['message'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'
                                                );
                                            }
                                        }
                                    });
                                }
                            </script>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
    <?php echo $app_footer; ?>
</div>
<?php echo $footer; ?>

