<?php if (count($languages) > 1) { ?>
    <div id="language" class="language">
		<div id="form-language">
			<div class="btn-group">
				<button class="btn btn-link dropdown-toggle btn-language" data-toggle="dropdown">
					<span class="hidden-xs hidden-sm hidden-md"><?php echo $text_language; ?></span>
				</button> 
             	<?php foreach ($languages as $language) { ?>
		 			<button class="btn btn-link btn-block language-select" type="button">
		 				<a href="<?php echo $language['code']; ?>" class="current-link">
		 					<?php echo $language['code2']; ?>
		 				</a>
		 			</button>
             	<?php } ?>
	         </div>
		</div>
	</div>
<?php } ?>