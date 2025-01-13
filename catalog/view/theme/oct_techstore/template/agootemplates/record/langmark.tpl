<?php if (count($languages) > 1) { ?>
<div id="language" class="language">
	<div id="form-language">
		<div class="btn-group df aic">
 			<?php foreach ($languages as $language) { ?>
 				<button class="<?php if ($language['code'] == $language_code) { ?>language-select<?php } ?> lang-<?php echo $language['code2']; ?>" type="button">
 					<a href="<?php echo $language['url']; ?>"><?php echo $language['code2']; ?></a>
 				</button>
 			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>