    <table class="table table-bordered table-hover">
 <?php $setting_name = 'deleted_reviews'; ?>       
        <tr>

            <td style="width:20%">

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group" style="width: 100%" >
                    
                    <?php if(isset($ym_review['deleted_reviews']) ){ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[deleted_reviews]"  class="form-control select-type-data" ><?php echo $ym_review['deleted_reviews'] ?></textarea>
                        
                    <?php }else{ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[deleted_reviews]" class="form-control select-type-data" ></textarea>
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'reviews_by_review_id'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  class="form-control select-type-data" ><?php echo $ym_review[$setting_name] ?></textarea>
                        
                    <?php }else{ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]" class="form-control select-type-data" ></textarea>
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'reviews_by_product_id'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  class="form-control select-type-data" ><?php echo $ym_review[$setting_name] ?></textarea>
                        
                    <?php }else{ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]" class="form-control select-type-data" ></textarea>
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'skip_reviews_by_product_id'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  class="form-control select-type-data" ><?php echo $ym_review[$setting_name] ?></textarea>
                        
                    <?php }else{ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]" class="form-control select-type-data" ></textarea>
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'skip_reviews_by_review_id'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  class="form-control select-type-data" ><?php echo $ym_review[$setting_name] ?></textarea>
                        
                    <?php }else{ ?>
                    
                        <textarea name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]" class="form-control select-type-data" ></textarea>
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'min_rating'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="<?php echo $ym_review[$setting_name] ?>" class="form-control select-type-data" type="text" />
                        
                    <?php }else{ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="" class="form-control select-type-data" type="text" />
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'min_date'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="<?php echo $ym_review[$setting_name] ?>" class="form-control select-type-data" type="text" />
                        
                    <?php }else{ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="" class="form-control select-type-data" type="text" />
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'aggregator_name'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="<?php echo $ym_review[$setting_name] ?>" class="form-control select-type-data" type="text" />
                        
                    <?php }else{ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="" class="form-control select-type-data" type="text" />
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'publisher_name'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="<?php echo $ym_review[$setting_name] ?>" class="form-control select-type-data" type="text" />
                        
                    <?php }else{ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="" class="form-control select-type-data" type="text" />
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
        <tr>
<?php $setting_name = 'publisher_favicon'; ?>
            <td>

                <div class="input-group" >
                    
                    <?php echo ${'text_review_'.$setting_name} ?>
                    
                </div>

            </td>

            <td>

                <div class="input-group"  style="width: 100%" >
                    
                    <?php if(isset($ym_review[$setting_name]) ){ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="<?php echo $ym_review[$setting_name] ?>" class="form-control select-type-data" type="text" />
                        
                    <?php }else{ ?>
                    
                        <input name="ocext_feed_generator_google_ym_review[<?php echo $setting_name ?>]"  value="" class="form-control select-type-data" type="text" />
                        
                    <?php } ?>
                    
                </div>

            </td>

        </tr>
        
    </table>
                    
                    <div class="alert alert-info"><?php echo $text_link_on_manual ?></div>