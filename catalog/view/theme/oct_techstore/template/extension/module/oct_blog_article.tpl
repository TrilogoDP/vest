<?php if ($position == "column_left" OR $position == "column_right") { ?>
    <div class="wrap oct-col-module">
<?php } else { ?>
    <div class="wrap news-row df fdc">
<?php } ?>
        <div class="header df aic jcc">
            <p class="title_module"><?php echo $heading_title; ?></p>
        </div>
        <div class="blog_list">
            <?php foreach ($articles as $article) { ?>
                <div class="item">
                    <?php if ($article['thumb']) { ?>
                        <div class="image">
                            <a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" class="img-responsive" alt="<?php echo $article['name']; ?>" /></a>
                        </div> 
                    <?php } ?>
                    <div class="detail_article">
                        <div class="date">
                            <span><?php echo $article['date_added']; ?></span>
                        </div>
                        <div class="name">
                            <a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a>
                        </div>
                        <?php if (!$isMobile) { ?>
                            <div class="news-desc">
                                <p><?php echo $article['description']; ?></p>
                            </div>
                            <a href="<?php echo $article['href']; ?>" class="btn_detail">
                                <?php echo $button_readmore; ?>
                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.8082 1.04845L11.4151 0.652642C11.2914 0.529031 11.1268 0.460933 10.9509 0.460933C10.7751 0.460933 10.6103 0.529031 10.4866 0.652642L6.00268 5.13678L1.51357 0.647666C1.39006 0.524055 1.22528 0.456055 1.04947 0.456055C0.873666 0.456055 0.708786 0.524055 0.585176 0.647666L0.192002 1.04104C-0.0640005 1.29684 -0.0640005 1.71353 0.192002 1.96934L5.53683 7.33338C5.66034 7.45689 5.82493 7.54392 6.00229 7.54392H6.00434C6.18025 7.54392 6.34483 7.45679 6.46835 7.33338L11.8082 1.98387C11.9319 1.86036 11.9998 1.6908 12 1.51499C12 1.33909 11.9319 1.17187 11.8082 1.04845Z" fill="#6CBBB0"/>
                                </svg>
                            </a>
                        <?php } ?>
                    </div>
                    <?php if ($isMobile) { ?>
                        <div class="description_wrap">
                             <div class="news-desc">
                                <p><?php echo $article['description']; ?></p>
                            </div>
                            <a href="<?php echo $article['href']; ?>" class="btn_detail">
                                <?php echo $button_readmore; ?>
                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.8082 1.04845L11.4151 0.652642C11.2914 0.529031 11.1268 0.460933 10.9509 0.460933C10.7751 0.460933 10.6103 0.529031 10.4866 0.652642L6.00268 5.13678L1.51357 0.647666C1.39006 0.524055 1.22528 0.456055 1.04947 0.456055C0.873666 0.456055 0.708786 0.524055 0.585176 0.647666L0.192002 1.04104C-0.0640005 1.29684 -0.0640005 1.71353 0.192002 1.96934L5.53683 7.33338C5.66034 7.45689 5.82493 7.54392 6.00229 7.54392H6.00434C6.18025 7.54392 6.34483 7.45679 6.46835 7.33338L11.8082 1.98387C11.9319 1.86036 11.9998 1.6908 12 1.51499C12 1.33909 11.9319 1.17187 11.8082 1.04845Z" fill="#6CBBB0"/>
                                </svg>
                            </a>
                        </div>
                    <?php } ?>
                </div> 
            <?php } ?>        
        </div>
        <a href="<?php echo $link; ?>" class="btn_show_more"><?php echo $button_readmore; ?></a>
    </div>
