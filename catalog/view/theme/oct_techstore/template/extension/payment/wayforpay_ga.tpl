<?php echo $header; ?>
<div class="container">
	<div class="col-sm-12 content-row">
		<div class="breadcrumb-box">
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div id="content" class="account-content"><?php echo $content_top; ?>
		<h1><div class="order_success"><?php echo $wfp_text_wait; ?></div></h1>

		<?php echo $wayforpay_form; ?>

		<div class="text-center">
			<i class="fa fa-spinner fa-spin" style="font-size:64px; color:#0e8f00;"></i>
		</div>

		<div class="clearfix"></div>
		<?php echo $content_bottom; ?>
	</div>
</div>
</div>

<?php if  (!empty($order_id)) { ?>
	<script>
		window.dataLayer = window.dataLayer || [];
		dataLayer.push({
			'event': 'fakepurchase',
			'fake_id': <?php echo $order_id; ?>
		});


	window.setTimeout(() => {
		$.ajax({
                type: 'GET',
                url: 'index.php?route=extension/payment/wayforpay/confirm',
                success: function () {
                    $('#payments').submit();
                }
            });
	}, 1500);
	</script>

<?php if (!$has_analytics) { ?>
	<script>
		window.dataLayer = window.dataLayer || [];
		
		dataLayer.push({
			'event': 'orderPurchaseSuccess',
			'ecommerce': {
				'currencyCode': '<?php echo $currency_code; ?>',  
				'customer':{
					<?php $i=0; foreach ($google_ecommerce_info['customerData'] as $customerDataKey => $customerDataValue) { ?>
						'<? echo $customerDataKey; ?>': '<? echo $customerDataValue; ?>'
						<? if ($i < count($google_ecommerce_info['customerData'])) { ?>,<?php } $i++; ?>
					<? } ?>
				},
				'purchase': {
					'id': '<? echo $google_ecommerce_info['transactionId'] ?>',                        
					'affiliation': '<? echo $google_ecommerce_info['transactionAffiliation'] ?>',
					'revenue': '<? echo $google_ecommerce_info['transactionTotal'] ?>',  
					'tax':'<? echo $google_ecommerce_info['transactionTax'] ?>',

					<?php if (!empty($google_ecommerce_info['transactionCoupon'])) { ?>
						'coupon':'<? echo $google_ecommerce_info['transactionCoupon'] ?>',
					<?php } ?>

					'shipping': '<? echo $google_ecommerce_info['transactionShipping'] ?>',
					'actionField': {
						'id': '<? echo $google_ecommerce_info['transactionId'] ?>',                        
						'affiliation': '<? echo $google_ecommerce_info['transactionAffiliation'] ?>',
						'revenue': '<? echo $google_ecommerce_info['transactionTotal'] ?>',  
						'tax':'<? echo $google_ecommerce_info['transactionTax'] ?>',

						<?php if (!empty($google_ecommerce_info['transactionCoupon'])) { ?>
							'coupon':'<? echo $google_ecommerce_info['transactionCoupon'] ?>',
						<?php } ?>

						'shipping': '<? echo $google_ecommerce_info['transactionShipping'] ?>'
					},
					'products': [
					<?php $i=0; foreach ($google_ecommerce_info['transactionProducts'] as $transactionProduct) { ?>
						{
							'id' : '<? echo $transactionProduct['id']; ?>',
							'sku': '<? echo $transactionProduct['sku'] ?>',
							'name' : '<? echo $transactionProduct['name'] ?>',
							'brand' : '<? echo $transactionProduct['manufacturer'] ?>',
							'category' : '<? echo $transactionProduct['category'] ?>',
							'price' : '<? echo $transactionProduct['price'] ?>',
							'quantity' : '<? echo $transactionProduct['quantity'] ?>',
							'total' : '<? echo $transactionProduct['total'] ?>'
						}	<? if ($i < count($google_ecommerce_info['transactionProducts'])) { ?>,<?php } $i++; ?>
					<? } ?>

					]					
				}	
			}
		});

	</script>
<?php } ?>
<?php } ?>

<?php echo $footer; ?>