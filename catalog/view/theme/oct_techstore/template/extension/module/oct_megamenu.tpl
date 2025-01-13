<?php if ($items) { ?>
	<div id="oct-menu-box" class="menu-wrap">
		<nav id="menu" class="navbar">
			<button class="category-btn-header df aic pr cup">
				<div class="hamburger-lines">
					<span class="line line1"></span>
					<span class="line line2"></span>
					<span class="line line3"></span>
				</div> 
				<span>Каталог</span>
			</button>
			<div class="collapse-wrap">
				<?php if ($isMobile) { ?>
					<button class="btn-back">
						<div class="hamburger-lines">
							<span class="line line1"></span>
							<span class="line line2"></span>
							<span class="line line3"></span>
						</div> 
						<span>Каталог</span>
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: #000a0e;">
							<path d="M8.95155 0.191807L9.34736 0.584883C9.47097 0.708591 9.53907 0.873178 9.53907 1.04908C9.53907 1.22489 9.47097 1.38967 9.34736 1.51338L4.86322 5.99732L9.35233 10.4864C9.47594 10.6099 9.54395 10.7747 9.54395 10.9505C9.54395 11.1263 9.47594 11.2912 9.35233 11.4148L8.95896 11.808C8.70316 12.064 8.28647 12.064 8.03066 11.808L2.66662 6.46317C2.54311 6.33966 2.45608 6.17507 2.45608 5.99771V5.99566C2.45608 5.81975 2.54321 5.65517 2.66662 5.53165L8.01613 0.191807C8.13964 0.0680981 8.3092 0.00019455 8.48501 0C8.66091 0 8.82813 0.0680981 8.95155 0.191807Z" fill="#000a0e" fill-opacity="0.2"/>
						</svg>
					</button>	
				<?php } ?>
				<ul class="list-menu">
					<?php foreach ($items as $item) { ?>
						<?php if ($item['children']) { ?>
							<?php if ($item['item_type'] == 2) { ?>
								<?php if ($item['display_type'] == 1) { ?>
								<?php } else { ?>
									<?php foreach ($item['children'] as $children) { ?>
										<?php if ($children['children']) { ?>
											<li class="second-level-li has-child">
												<a class="megamenu-parent-title" href="<?php echo $children['href']; ?>">
													<?php if ($children['top_svg']) { ?>
														<?php echo $children['top_svg']; ?>
													<?php } ?>													
													<?php echo $children['name']; ?>
													<svg class="arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>>
														<path d="M11.8082 1.04845L11.4151 0.652642C11.2914 0.529031 11.1268 0.460933 10.9509 0.460933C10.7751 0.460933 10.6103 0.529031 10.4866 0.652642L6.00268 5.13678L1.51357 0.647666C1.39006 0.524055 1.22528 0.456055 1.04947 0.456055C0.873666 0.456055 0.708786 0.524055 0.585176 0.647666L0.192002 1.04104C-0.0640005 1.29684 -0.0640005 1.71353 0.192002 1.96934L5.53683 7.33338C5.66034 7.45689 5.82493 7.54392 6.00229 7.54392H6.00434C6.18025 7.54392 6.34483 7.45679 6.46835 7.33338L11.8082 1.98387C11.9319 1.86036 11.9998 1.6908 12 1.51499C12 1.33909 11.9319 1.17187 11.8082 1.04845Z" fill="#002C3E" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>/>
														</svg>
													</a> 
													<div class="megamenu-ischild count-<?php echo count($children['children']); ?>">

														<?php if ($isMobile) { ?>
															<button class="btn-back">
																<div class="hamburger-lines">
																	<span class="line line1"></span>
																	<span class="line line2"></span>
																	<span class="line line3"></span>
																</div> 
																<span>Назад</span>
																<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: #000a0e;">
																	<path d="M8.95155 0.191807L9.34736 0.584883C9.47097 0.708591 9.53907 0.873178 9.53907 1.04908C9.53907 1.22489 9.47097 1.38967 9.34736 1.51338L4.86322 5.99732L9.35233 10.4864C9.47594 10.6099 9.54395 10.7747 9.54395 10.9505C9.54395 11.1263 9.47594 11.2912 9.35233 11.4148L8.95896 11.808C8.70316 12.064 8.28647 12.064 8.03066 11.808L2.66662 6.46317C2.54311 6.33966 2.45608 6.17507 2.45608 5.99771V5.99566C2.45608 5.81975 2.54321 5.65517 2.66662 5.53165L8.01613 0.191807C8.13964 0.0680981 8.3092 0.00019455 8.48501 0C8.66091 0 8.82813 0.0680981 8.95155 0.191807Z" fill="#000a0e" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>/>
																	</svg>
																</button>	
															<?php } ?>
															<ul>
																<?php foreach ($children['children'] as $child) { ?>
																	<?php if ($child['children'] || $child['ocfilters']) { ?>
																		<li class="has-child">
																			<a class="megamenu-parent-title" href="<?php echo $child['href']; ?>">
																				<?php echo $child['name']; ?>
																				<?php if ($isMobile) { ?>
																					<svg class="arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: #000a0e;">
																						<path d="M11.8082 1.04845L11.4151 0.652642C11.2914 0.529031 11.1268 0.460933 10.9509 0.460933C10.7751 0.460933 10.6103 0.529031 10.4866 0.652642L6.00268 5.13678L1.51357 0.647666C1.39006 0.524055 1.22528 0.456055 1.04947 0.456055C0.873666 0.456055 0.708786 0.524055 0.585176 0.647666L0.192002 1.04104C-0.0640005 1.29684 -0.0640005 1.71353 0.192002 1.96934L5.53683 7.33338C5.66034 7.45689 5.82493 7.54392 6.00229 7.54392H6.00434C6.18025 7.54392 6.34483 7.45679 6.46835 7.33338L11.8082 1.98387C11.9319 1.86036 11.9998 1.6908 12 1.51499C12 1.33909 11.9319 1.17187 11.8082 1.04845Z" fill="#002C3E" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>/>
																						</svg>
																					<?php } ?>
																				</a>																	
																				<ul>
																					<?php if ($isMobile) { ?>
																						<button class="btn-back">
																							<div class="hamburger-lines">
																								<span class="line line1"></span>
																								<span class="line line2"></span>
																								<span class="line line3"></span>
																							</div> 
																							<span>Назад</span>
																							<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: #000a0e;">
																								<path d="M8.95155 0.191807L9.34736 0.584883C9.47097 0.708591 9.53907 0.873178 9.53907 1.04908C9.53907 1.22489 9.47097 1.38967 9.34736 1.51338L4.86322 5.99732L9.35233 10.4864C9.47594 10.6099 9.54395 10.7747 9.54395 10.9505C9.54395 11.1263 9.47594 11.2912 9.35233 11.4148L8.95896 11.808C8.70316 12.064 8.28647 12.064 8.03066 11.808L2.66662 6.46317C2.54311 6.33966 2.45608 6.17507 2.45608 5.99771V5.99566C2.45608 5.81975 2.54321 5.65517 2.66662 5.53165L8.01613 0.191807C8.13964 0.0680981 8.3092 0.00019455 8.48501 0C8.66091 0 8.82813 0.0680981 8.95155 0.191807Z" fill="#000a0e" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>/>
																								</svg>
																							</button>	
																						<?php } ?>
																						<?php foreach ($child['children'] as $child_l2) { ?>
																							<?php if ($child_l2['children'] || $child_l2['ocfilters']) { ?>
																								<li class="has-child">
																									<a class="megamenu-parent-title" href="<?php echo $child_l2['href']; ?>">
																										<?php if ($child_l2['top_svg']) { ?>
																											<?php echo $child_l2['top_svg']; ?>
																										<?php } ?>
																										<?php echo $child_l2['name']; ?>
																										<svg class="arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
																											<path d="M11.8082 1.04845L11.4151 0.652642C11.2914 0.529031 11.1268 0.460933 10.9509 0.460933C10.7751 0.460933 10.6103 0.529031 10.4866 0.652642L6.00268 5.13678L1.51357 0.647666C1.39006 0.524055 1.22528 0.456055 1.04947 0.456055C0.873666 0.456055 0.708786 0.524055 0.585176 0.647666L0.192002 1.04104C-0.0640005 1.29684 -0.0640005 1.71353 0.192002 1.96934L5.53683 7.33338C5.66034 7.45689 5.82493 7.54392 6.00229 7.54392H6.00434C6.18025 7.54392 6.34483 7.45679 6.46835 7.33338L11.8082 1.98387C11.9319 1.86036 11.9998 1.6908 12 1.51499C12 1.33909 11.9319 1.17187 11.8082 1.04845Z" fill="#002C3E" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>></path>
																										</svg>
																									</a>

																									<ul>
																										<?php foreach ($child_l2['children'] as $child_l3) { ?>
																											<li>
																												<a class="megamenu-parent-title" href="<?php echo $child_l3['href']; ?>">
																													<?php if ($child_l3['top_svg']) { ?>
																														<?php echo $child_l3['top_svg']; ?>
																													<?php } ?>
																													<?php echo $child_l3['name']; ?>
																												</a>
																											</li>
																										<?php } ?>
																									</ul>
																								</li>
																							<?php } else { ?>
																								<li>
																									<a class="megamenu-parent-title" href="<?php echo $child_l2['href']; ?>">
																										<?php if ($child_l2['top_svg']) { ?>
																											<?php echo $child_l2['top_svg']; ?>
																										<?php } ?>
																										<?php echo $child_l2['name']; ?>
																									</a>
																								</li>
																							<?php } ?>	
																						<?php } ?>

																						<?php $count_filters = count($child['ocfilters']); ?>
																						<?php foreach ($child['ocfilters'] as $ocfilter) { ?>
																							<li>
																								<a class="megamenu-parent-title" href="<?php echo $ocfilter['href']; ?>">
																									<?php echo $ocfilter['name']; ?>	
																								</a>
																							</li>
																						<?php } ?>
																						<?php if ($count_filters > 1) { ?>
																						<li>
																							<a class="megamenu-parent-title" href="<?php echo $child['href']; ?>">
																								<?php echo $text_more; ?>
																							</a>
																						</li>
																						<?php } ?>
																					</ul>

																				</li>
																			<?php } else { ?>
																				<li>
																					<a class="megamenu-parent-title" href="<?php echo $child['href']; ?>">
																						<?php echo $child['name']; ?>	
																					</a>
																				</li>
																			<?php } ?>
																		<?php } ?>
																	</ul>

																</div>
															</li>
														<?php } else { ?>
															<li class="second-level-li">
																<a href="<?php echo $children['href']; ?>">
																	<?php if ($children['top_svg']) { ?>
																		<?php echo $children['top_svg']; ?>
																	<?php } ?>
																	<?php echo $children['name']; ?>													
																</a>
															</li>
														<?php } ?>
													<?php } ?>
												<?php } ?>
										<?php } ?>
									<?php } ?>
											<?php if ($item['item_type'] == 1) { ?>
													<?php if ($item['display_type'] == 1) { ?>
														<li class="second-level-li special">
															<a href="<?php echo $item['href']; ?>">
																<?php if ($item['top_svg']) { ?>
																	<?php echo $item['top_svg']; ?>
																<?php } ?>
																<?php echo $item['title']; ?>													
															</a>
														</li>
													<?php } ?>
												<?php } ?>
								<?php } ?>
								</ul>
							</div>
						</nav>
					</div>
					<?php } ?>