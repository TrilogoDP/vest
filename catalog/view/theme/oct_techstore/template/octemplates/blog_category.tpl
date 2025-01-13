<?php echo $header; ?>
<div class="container">
	<?php echo $content_top; ?>
	<div class="wrap df fdc">
		<div class="breadcrumb-box">
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
					<?php if($count == 0) { ?>
						<li>
							<a href="<?php echo $breadcrumb['href']; ?>">
								<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
								</svg>
								
							</a>
						</li>
						<?php } elseif($count+1<count($breadcrumbs)) { ?>
						<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
						<?php } else { ?>
						<li><span><?php echo $breadcrumb['text']; ?></span></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
		<?php
			$count_breadcrumb = 0; 
			$bc = '';
			foreach ($breadcrumbs as $breadcrumb) { 
				$count_breadcrumb = $count_breadcrumb + 1; 
				$bc .= '{
				"@type": "ListItem",
				"position": '.$count_breadcrumb.',
				"item": {
				"@id": "'.$breadcrumb['href'].'",
				"name": "'.$breadcrumb['text'].'"
				}},';
			}
			$bc = rtrim($bc,',');
		?>
		<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "BreadcrumbList",
			"itemListElement": [<?php echo $bc; ?>]}
		</script>
		
			<h1 class="title_module"><?php echo $heading_title; ?></h1>
		
			
		<div class="content_blog">
			<?php echo $column_left; ?>
			<?php if ($column_left && $column_right) { ?>
				<?php $class = 'col-sm-6'; ?>
				<?php } elseif ($column_left || $column_right) { ?>
				<?php $class = 'col-sm-9'; ?>
				<?php } else { ?>
				<?php $class = 'col-sm-12'; ?>
			<?php } ?>
			<div id="content" class="<?php echo $class; ?>">
				<?php if ($articles) { ?>
					<div class="news-row">
						<?php foreach ($articles as $article) { ?>
							<div class="blog-item">
								
									<div class="image">
										<div class="date_label"><?php echo $article['date_added']; ?></div>
										<a href="<?php echo $article['href']; ?>">
											<img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>" title="<?php echo $article['name']; ?>" class="img-responsive" />
										</a>
									</div>
									
									<div class="caption">
										<h4><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h4>
										<p><?php echo $article['description']; ?></p>
										
										<div class="pull">
											<div class="badge-box">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M20 0H4C2.93913 0 1.92172 0.421427 1.17157 1.17157C0.421427 1.92172 0 2.93913 0 4L0 16C0 17.0609 0.421427 18.0783 1.17157 18.8284C1.92172 19.5786 2.93913 20 4 20H6.9L11.351 23.763C11.5316 23.9158 11.7605 23.9997 11.997 23.9997C12.2335 23.9997 12.4624 23.9158 12.643 23.763L17.1 20H20C21.0609 20 22.0783 19.5786 22.8284 18.8284C23.5786 18.0783 24 17.0609 24 16V4C24 2.93913 23.5786 1.92172 22.8284 1.17157C22.0783 0.421427 21.0609 0 20 0V0ZM7 5H12C12.2652 5 12.5196 5.10536 12.7071 5.29289C12.8946 5.48043 13 5.73478 13 6C13 6.26522 12.8946 6.51957 12.7071 6.70711C12.5196 6.89464 12.2652 7 12 7H7C6.73478 7 6.48043 6.89464 6.29289 6.70711C6.10536 6.51957 6 6.26522 6 6C6 5.73478 6.10536 5.48043 6.29289 5.29289C6.48043 5.10536 6.73478 5 7 5ZM17 15H7C6.73478 15 6.48043 14.8946 6.29289 14.7071C6.10536 14.5196 6 14.2652 6 14C6 13.7348 6.10536 13.4804 6.29289 13.2929C6.48043 13.1054 6.73478 13 7 13H17C17.2652 13 17.5196 13.1054 17.7071 13.2929C17.8946 13.4804 18 13.7348 18 14C18 14.2652 17.8946 14.5196 17.7071 14.7071C17.5196 14.8946 17.2652 15 17 15ZM17 11H7C6.73478 11 6.48043 10.8946 6.29289 10.7071C6.10536 10.5196 6 10.2652 6 10C6 9.73478 6.10536 9.48043 6.29289 9.29289C6.48043 9.10536 6.73478 9 7 9H17C17.2652 9 17.5196 9.10536 17.7071 9.29289C17.8946 9.48043 18 9.73478 18 10C18 10.2652 17.8946 10.5196 17.7071 10.7071C17.5196 10.8946 17.2652 11 17 11Z" fill="#6CBBB0"/>
												</svg>
												<span class="badge"><?php echo $article['comments']; ?></span>
											</div>
											<div class="badge-box">
												<svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M23.2668 7.41971C21.7164 4.89465 18.1897 0.658203 12 0.658203C5.81027 0.658203 2.28358 4.89465 0.733158 7.41971C-0.244386 9.00083 -0.244386 10.9986 0.733158 12.5798C2.28358 15.1049 5.81027 19.3413 12 19.3413C18.1897 19.3413 21.7164 15.1049 23.2668 12.5798C24.2443 10.9986 24.2443 9.00083 23.2668 7.41971ZM12 15.9975C8.68752 15.9975 6.0022 13.3122 6.0022 9.99974C6.0022 6.68728 8.68752 4.00196 12 4.00196C15.3124 4.00196 17.9978 6.68728 17.9978 9.99974C17.9944 13.3108 15.3111 15.9942 12 15.9975Z" fill="#6CBBB0"/>
												</svg>
												<span class="badge"><?php echo $article['viewed']; ?></span>
											</div>
										</div>
									</div>
										
							</div>
						<?php } ?>
					</div>
					<div class="pagination-wrap">
						<?php echo $pagination; ?>
					</div>
				<?php } ?>
				<?php if ($thumb || $description) { ?>
					<div class="row">
						<div class="col-sm-12 cat-desc-box">
							<?php if ($thumb) { ?>
								<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" />
							<?php } ?>
							<?php if ($description) { ?>
								<?php echo $description; ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php if (!$articles) { ?>
					<p class="text-left empty-text"><?php echo $text_empty; ?></p>
					<div class="buttons">
						<div class="text-left"><a href="<?php echo $continue; ?>" class="oct-button"><?php echo $button_continue; ?></a></div>
					</div>
				<?php } ?>
			</div>
			<?php echo $column_right; ?>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>