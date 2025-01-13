
function passEcommercePromoClickToDataLayer(promoObj) {
	//	dataLayer.push({ ecommerce: null });
	if (typeof window.google_tag_manager != 'object'){
		console.log('gtm not loaded, skip');
		document.location = promoObj.url;
	}
	dataLayer.push({
		'event': 'promotionClick',
		'ecommerce': {
			'promoClick': {
				'promotions': [
					{
						'id': promoObj.id,     
						'name': promoObj.name,
						'creative': promoObj.creative,
						'position': promoObj.pos
					}]
			}
		},
		'eventTimeout' : 200,
		'eventCallback': function() {
			document.location = promoObj.url;
		}
	});
	dataLayer.push({
		'event' : 'datalayerReady',
		'eventCallback': function() {
			document.location = promoObj.url;
		}	
	});
}

$(document).ready(function(){	
	$('.banner-promo-single > a').click(function(element){
		element.preventDefault();
		let parentDiv = $(this).parents('.banner-promo-single');
		let promoObject = JSON.parse(parentDiv.attr('data-gtm-banner'));
		
		try {
			if (typeof(promoObject) == 'object'){
				console.log(promoObject); 
				passEcommercePromoClickToDataLayer(promoObject);
			}
			}catch(err) {
			console.log(err);
			document.location = $(this).attr('href');
		}
	});
});

function passEcommerceProductClickToDataLayer(productObj) {
	window.dataLayer = window.dataLayer || [];
	if (typeof window.google_tag_manager != 'object'){
		console.log('gtm not loaded, skip');
		document.location = productObj.url;
	}
	console.log('dataLayer.push ' + 'productClick');
	dataLayer.push({
		'event': 'productClick',
		'ecommerce': {
			'click': {
				'actionField': {'list': productObj.list},
				'products': [{
					'name': 	productObj.name,
					'id': 		productObj.id,
					'price': 	productObj.price,
					'brand': 	productObj.brand,
					'category': productObj.category,
					'position': productObj.position
				}]
			}
		},
		'eventTimeout' : 200,
		'eventCallback': function() {
			//	console.log(productObj);
			//	console.log(dataLayer);
			document.location = productObj.url;
		}
	});
	dataLayer.push({
		'event' : 'datalayerReady',
		'eventCallback': function() {
			document.location = productObj.url;
		}	
	});
}

$(document).ready(function(){	
	$('.product-single a').not(".button-cart").click(function(element){
		element.preventDefault();
		
		try {
			let parentDiv = $(this).parents('.product-single');
			let productObject = JSON.parse(parentDiv.attr('data-gtm-product'));

			if (typeof(productObject) == 'object'){				
				console.log(productObject);
				passEcommerceProductClickToDataLayer(productObject);
			}
			}catch(err) {
			console.log(err);
			document.location = $(this).attr('href');
		}
	});
});

function passEcommerceStepsToDataLayer(evt){
	$('#push_ecommerce_info').remove();
	$('body').append('<div id="push_ecommerce_info"></div>');
	
	if (evt && typeof(evt) != 'undefined'){
		$('#push_ecommerce_info').load('index.php?route=hobotix/ecommerce/ecommerceCheckoutSteps&ecommerceEvent='+evt);
	}
	
}

function passEcommerceCheckoutOptionsToDataLayer(step, checkoutOption) {
	window.dataLayer = window.dataLayer || [];
	console.log('dataLayer.push ' + checkoutOption);
	dataLayer.push({
		'event': 'checkoutOption',
		'ecommerce': {
			'checkout_option': {
				'actionField': {'step': step, 'option': checkoutOption}
			}
		}
	});
}

$(document).ready(function(){
	$('input[name=\'payment_method_checked\']').on('change', function(){
		//	console.log('chekout step');	
	});
});

function passEventToDataLayer(evt, eventCategory, eventAction, eventLabel){
	
	window.dataLayer = window.dataLayer || [];
	console.log('dataLayer.push ' + evt);
	
	dataLayer.push({
		event: evt,
		eventCategory: eventCategory,
		eventAction: eventAction,
		eventLabel: eventLabel
	});
	
}

function passEcommerceToDataLayer(evt, product_id, quantity, fbevt){		
	
	var quantity = typeof(quantity) != 'undefined' ? quantity : 1;
	
	var mappingEventToGA = {};
	mappingEventToGA['addToCart'] = 'add';
	mappingEventToGA['removeFromCart'] = 'remove';
	mappingEventToGA['editCart'] = 'edit';
	mappingEventToGA['productDetail'] = 'detail';
	
	$.ajax({
		url: "/index.php?route=product/product/getEcommerceInfo",
		data: "product_id=" + product_id,
		dataType: "json",
		error: function(e){
			console.log(e);
		},
		success: function(json) {
			
			window.dataLayer = window.dataLayer || [];
			console.log('dataLayer.push ' + evt);
			
			var products = [{ 
				'id':		json.product_id,
				'name':		json.name,
				'price':	json.price,
				'brand':	json.brand,
				'category':	json.category,							
				'quantity': quantity
			}];
			switch(evt){
				case 'productDetail':				
				products = [{ 
					'id':		json.product_id,
					'name':		json.name,
					'price':	json.price,
					'brand':	json.brand,
					'category':	json.category,										
				}];
				dataLayer.push({
					'event': evt,
					'ecommerce': {
						'currencyCode': json.currency, 
						'detail': {                             
							'products': products
						}
					}
				});
				
				//Динамический ремаркетинг
				dataLayer.push({
					'event': 'view_item',
					'value': products[0]['price'],
					'items': [{
						'id': products[0]['id'], 
						'google_business_vertical': 'retail'
					}]
				});
				
				break;
				case 'addToCart':
				dataLayer.push({
					'event': evt,
					'ecommerce': {
						'currencyCode': json.currency,  
						'add': {                             
							'products': products
						}
					}
				});	
				
				//Динамический ремаркетинг
				dataLayer.push({
					'event': 'add_to_cart',
					'value': products[0]['price'],
					'items': [{
						'id': products[0]['id'], 
						'google_business_vertical': 'retail'
					}]
				});
				
				break;
				case 'removeFromCart':
				dataLayer.push({
					'event': evt,
					'ecommerce': {
						'currencyCode': json.currency,  
						'remove': {                             
							'products': products
						}
					}
				});	
				break;	
				case 'editCart':
				dataLayer.push({
					'event': evt,
					'ecommerce': {
						'currencyCode': json.currency,  
						'edit': {                             
							'products': products
						}
					}
				});	
				break;
			}
			
			if (fbevt && (typeof fbq !== 'undefined')){
				fbq('track', fbevt, 
					{
						value: 		json.price,
						currency: 	json.currency,
						content_type: 'product',
						content_ids: [parseInt(product_id)]
					});
			}
			
		}
	});
}