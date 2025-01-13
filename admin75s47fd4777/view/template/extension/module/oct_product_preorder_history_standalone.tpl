<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $history_fast; ?>" data-toggle="tooltip" title="Список для закупки" class="btn btn-warning"><i class="fa fa-bars" aria-hidden="true"></i> Список для закупки</a>
			
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		
		<div class="form-group row">            
            <div class="col-sm-4 text-left pull-left">			
				<input type="text" name="product_name" value="" placeholder="Товар" id="input-product" class="form-control" />
				<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />           
			</div>
			<div class="col-sm-2 text-left pull-left">			
				<input type="text" name="telephone" value="" placeholder="Телефон" class="form-control" />			
			</div>
			<div class="col-sm-2 text-left pull-left">	
				<button class="btn btn-primary" id="button-add-request" onclick="addRequest()"><i class="fa fa-plus"></i></button>
			</div>
		</div>
		
		<div id="history"></div>
	</div>
</div>
<script type="text/javascript">
	
	$('input[name=\'product_name\']').autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_quantity=0&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		select: function(item) {
			$('input[name=\'product_name\']').val(item.label);
			$('input[name=\'product_id\']').val(item.value);
		}
	});
	
	function addRequest(){
		
		let product_id = $('input[name=\'product_id\']').val();
		let telephone = $('input[name=\'telephone\']').val();
		
		if (!product_id.length || !telephone.length){
			$('#button-add-request').removeClass('btn-primary btn-danger').addClass('btn-danger');
			} else {
			
			$('#button-add-request').removeClass('btn-primary btn-danger').addClass('btn-primary');
			
			$.ajax({
				type: 'post',
				url:  'index.php?route=extension/module/oct_product_preorder/add_request&token=<?php echo $token; ?>',				
				data: {
					'product_id' : $('input[name=\'product_id\']').val(),
					'telephone' : $('input[name=\'telephone\']').val(),
				},
				beforeSend: function(){
					$('#history').html("<div class='text-center' style='height:300px; padding-top:150px;'><i class='fa fa-spinner fa-spin' style='font-size:64px;'></i></div>");
				},
				success: function() {
					$('#history').load('index.php?route=extension/module/oct_product_preorder/history&token=<?php echo $token; ?>');
					$('input[name=\'product_name\']').val('');
					$('input[name=\'product_id\']').val('');
				}
			});
		}
	}
	
	
	$('#call_button a:first').tab('show');
	$('#promo a:first').tab('show');
	
	$('#history').delegate('.pagination a', 'click', function(e) {
		e.preventDefault();
		$('#history').load(this.href);
	});
	$('#history').load('index.php?route=extension/module/oct_product_preorder/history&token=<?php echo $token; ?>');
	function delete_selected(request_id) {
		$.ajax({
			type: 'post',
			url:  'index.php?route=extension/module/oct_product_preorder/delete_selected&token=<?php echo $token; ?>&delete=' + request_id,
			dataType: 'json',
			beforeSend: function(){
				$('#history').html("<div class='text-center' style='height:300px; padding-top:150px;'><i class='fa fa-spinner fa-spin' style='font-size:64px;'></i></div>");
			},
			success: function(json) {
				$('#history').load('index.php?route=extension/module/oct_product_preorder/history&token=<?php echo $token; ?>');
			}
		});
	}	
	function delete_all_selected() {
		$.ajax({
			type: 'post',
			url:  'index.php?route=extension/module/oct_product_preorder/delete_all_selected&token=<?php echo $token; ?>',
			data: $('#history input[type=\'checkbox\']:checked'),
			dataType: 'json',
			beforeSend: function(){
				$('#history').html("<div class='text-center' style='height:300px; padding-top:150px;'><i class='fa fa-spinner fa-spin' style='font-size:64px;'></i></div>");
			},
			success: function(json) {
				$('#history').load('index.php?route=extension/module/oct_product_preorder/history&token=<?php echo $token; ?>');
			}
		});
	}
	function update_note(request_id, note) {
		$.ajax({
			type: 'post',
			url:  'index.php?route=extension/module/oct_product_preorder/update_note&token=<?php echo $token; ?>&request_id=' + request_id + '&note=' + note,
			dataType: 'json',
			beforeSend: function(){
				$('#history').html("<div class='text-center' style='height:300px; padding-top:150px;'><i class='fa fa-spinner fa-spin' style='font-size:64px;'></i></div>");
			},
			success: function(json) {
				$('#history').load('index.php?route=extension/module/oct_product_preorder/history&token=<?php echo $token; ?>');
			}
		});
	}
</script>
<?php echo $footer; ?>