<?php echo $header; ?>

<?php if (version_compare(VERSION, '2.0.0.0', '>=')) { ?>
    <?php echo $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <?php if (!empty($button_apply_allowed)){ ?>
                      <button onclick="ajax_loading_open();$('input[name=no_exit]').val(1);save_configuration_ajax($('form#<?= $extension_name; ?>'));" type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $apply_changes; ?>" class="btn btn-primary"><i class="fa fa-check"></i></button>
                    <?php } ?>

                    <?php if (!empty($button_save_allowed)){ ?>
                      <button onclick="ajax_loading_open();$('input[name=no_exit]').val(0);$('form#<?php echo $form_view['id']; ?>').submit()" type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <?php } ?>
                    
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
                    <h1><?php echo $heading_title; ?></h1>
                    <ul class="breadcrumb">
                        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="container-fluid">
                <?php
                $error_message = '';
                foreach ($_SESSION as $key_session => $value) {
                    if(is_array($value))
                    {
                        foreach ($value as $key => $val) {
                            if($key == 'error' && !empty($val))
                            {
                                $error_message = $val;
                                unset($_SESSION[$key_session]['error']);
                            }
                        }
                    }
                }
                
                if(!empty($_SESSION['error']))
                {
                    $error_message = $_SESSION['error'];
                    unset($_SESSION['error']);
                }
                elseif(!empty($_SESSION['default']['error']))
                {
                    $error_message = $_SESSION['default']['error'];
                    unset($_SESSION['default']['error']);
                }
                ?>

                <?php
                $info_message = '';
                foreach ($_SESSION as $key_session => $value) {
                    if(is_array($value))
                    {
                        foreach ($value as $key => $val) {
                            if($key == 'info' && !empty($val))
                            {
                                $info_message = $val;
                                unset($_SESSION[$key_session]['info']);
                            }
                        }
                    }
                }
                
                if(!empty($_SESSION['info']))
                {
                    $info_message = $_SESSION['info'];
                    unset($_SESSION['info']);
                }
                elseif(!empty($_SESSION['default']['info']))
                {
                    $info_message = $_SESSION['default']['info'];
                    unset($_SESSION['default']['info']);
                }
                ?>

                <?php
                $success_message = '';
                if(!empty($_SESSION['success']))
                {
                    $success_message = $_SESSION['success'];
                    unset($_SESSION['success']);
                }
                elseif(!empty($_SESSION['default']['success']))
                {
                    $success_message = $_SESSION['default']['success'];
                    unset($_SESSION['default']['success']);
                }
                ?>

                <?php if (!empty($error_message)) { ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_message; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>
                <?php if (!empty($info_message)) { ?>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_message; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>
                <?php if (!empty($success_message)) { ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success_message; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
                    </div>
                    <div class="panel-body">
                        <?php echo $form; ?>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var token = '<?php echo $token; ?>';
            var text_none = '<?= !empty($text_none) ? $text_none : 'none'; ?>';
        </script>
<?php } else { ?>
    <div id="content">
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <?php if (!empty($this->session->data['error'])) { ?>
            <div class="warning"><?php echo $this->session->data['error']; unset($this->session->data['error']) ?></div>
        <?php } ?>
        <?php if (!empty($this->session->data['info'])) { ?>
            <div class="info"><?php echo $this->session->data['info']; unset($this->session->data['info']) ?></div>
        <?php } ?>
        <?php if (!empty($this->session->data['success'])) { ?>
            <div class="success"><?php echo $this->session->data['success']; unset($this->session->data['success']) ?></div>
        <?php } ?>
        <div class="box">
            <div class="heading">
                <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
                <div class="buttons">
                    <?php if (!empty($button_apply_allowed)){ ?>
                        <a onclick="$('input[name=no_exit]').val(1);save_configuration_ajax($('form#<?= $extension_name; ?>'));" class="button"><?php echo $apply_changes; ?></a>
                    <?php } ?>

                    <?php if (!empty($button_save_allowed)){ ?>
                        <a onclick="ajax_loading_open();$('input[name=no_exit]').val(0);$('form').submit();" class="button"><?php echo $button_save; ?></a>
                    <?php } ?>
                    <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
                </div>
            </div>
            <div class="content">
                <?php echo $form; ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#tabs a').tabs();
            $('.date').datepicker({dateFormat: 'yy-mm-dd'});
        });
    </script>

    <script type="text/javascript">
        var token = '<?php echo $token; ?>';
        var text_none = '<?= !empty($text_none) ? $text_none : 'none'; ?>';
    </script>

    <script type="text/javascript">
        function image_upload(field, thumb) {
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '<?php echo $text_image_manager; ?>',
                close: function (event, ui) {
                    if ($('#' + field).attr('value')) {
                        $.ajax({
                            url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function(data) {
                                $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                            }
                        });
                    }
                },  
                bgiframe: false,
                width: 800,
                height: 400,
                resizable: false,
                modal: false
            });
        };
    </script>
<?php } ?>

<?php echo $footer; ?>