<?php echo $header; ?>
<div class="wrap fdc">
  	<div class="breadcrumb-box">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
                <?php if($count == 0) { ?>
                    <li>
                        <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $oct_home_text; ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
                            </svg>
                            Головна
                        </a>
                    </li>
                <?php } elseif($count+1<count($breadcrumbs)) { ?>
                    <li>
                        <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $breadcrumb['text']; ?>">
                            <?php echo $breadcrumb['text']; ?>
                        </a>
                    </li>         
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
  	<?php if ($error_warning) { ?>
  		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  	<?php } ?>
    <div id="content" class="account-content"><?php echo $content_top; ?>
      	<h1 class="title_module"><?php echo $heading_title; ?></h1>
      	<p class="text-center"></p>