// https://codepen.io/martinAnsty/pen/BCotE
Math.easeIn = function (val, min, max, strength) {
	val /= max;
	
	return (max - 1) * Math.pow(val, strength) + min;
};

(function($) {
	
	function setSlider(_, target) {
		var
		that = this,
		$element = $(target),
		min = parseFloat($element.data().rangeMin),
		max = parseFloat($element.data().rangeMax),
		decimals = 0,
		elementMin,
		elementMax,
		controlMin,
		controlMax,
		_options = {
			behaviour: 'drag-tap',
			connect: true,
			range: {
				'min': min,
				'max': max
			}
		};
		
		// Logarithmic scale
		if ($element.data().optionId == 'p' && (max - min) > 100) {
			_options['pips'] = {
				mode: 'range',
				density: 4
			};
			
			var _i = 25, _strength = 3.5;
			
			if ((max - min) < 100) {
				_strength = 2;
			}
			
			for (; _i < 100; _i += 25) {
				_options['range'][_i + '%'] = Math.ceil(Math.easeIn(((max - min) / 100 * _i), min, max, _strength));
			}
			} else {
			_options['pips'] = {
				mode: 'count',
				values: 3,
				density: 4
			};
		}
		
		if (max && min != max) {
			_options['start'] = [ parseFloat($element.data().startMin), parseFloat($element.data().startMax) ];
			} else {
			_options['start'] = parseFloat($element.data().startMin);
		}
		
		// Decimal
		if (/\./.test($element.data().rangeMin) || /\./.test($element.data().rangeMax)) {
			decimals = Math.max(
				$element.data().rangeMin.toString().replace(/^\d+?\./, '').length,
				$element.data().rangeMax.toString().replace(/^\d+?\./, '').length
			);
		}
		
		_options['format'] = {
			to: function (value) {
				return parseFloat(value).toFixed(decimals);
			},
			from: function (value) {
				return parseFloat(value).toFixed(decimals);
			}
		};
		
		noUiSlider.create($element.get(0), _options);
		
		if ($element.data().elementMin && $($element.data().elementMin).length) {
			elementMin = $($element.data().elementMin);
		}
		
		if ($element.data().elementMax && $($element.data().elementMax).length) {
			elementMax = $($element.data().elementMax);
		}
		
		$element.get(0).noUiSlider.on('slide', function(values, handle, noformat) {
			if (typeof values[0] != 'undefined') {
				if (elementMin) {
					elementMin.html(values[0]);
				}
				
				if ($element.data().controlMin && $($element.data().controlMin).length) {
					$($element.data().controlMin).val(noformat[0].toFixed(decimals));
				}
			}
			
			if (typeof values[1] != 'undefined') {
				if (elementMax) {
					elementMax.html(values[1]);
				}
				
				if ($element.data().controlMax && $($element.data().controlMax).length) {
					$($element.data().controlMax).val(noformat[1].toFixed(decimals));
				}
			}
		});
		
		$element.get(0).noUiSlider.on('change', function(_, __, values, tap, positions) {
			that.params.remove.call(that, $element.data().optionId);
			
			if ((positions[1] - positions[0]) < 100) {
				that.params.set.call(that, $element.data().optionId, values[0].toFixed(decimals) + '-' + values[1].toFixed(decimals));
			}
			
			that.update($element);
		});
		
		// webfun
		$('input[name="price[min]"], input[name="price[max]"]').change(function(){
			that.params.remove.call(that, $element.data().optionId);
			
			var min1 = parseInt($.trim($('input[name="price[min]"]').val()),10);
			var max1 = parseInt($.trim($('input[name="price[max]"]').val()),10);
			
			if ((max1 - min1) >= 0) {
				that.params.set.call(that, $element.data().optionId, min1.toFixed(decimals) + '-' + max1.toFixed(decimals));
			}
			
			that.update($element);
		});
		
		// update filter clear selected without reload page
		$(".ocfilter").on('click', '.selected-options .ocfilter-option button', function() {
			var wfOptionId = $(this).attr('data-wf-option-id');
			var wfOptionIdValue = $(this).attr('data-wf-option-id-value');
			
			if (wfOptionId == 'p'){
				$('#min-price-value').val(parseInt($('#scale-price').attr('data-range-min')));
				$('#max-price-value').val(parseInt($('#scale-price').attr('data-range-max')));
				$('#max-price-value').trigger('change');
			}
			
			$('.ocfilter-option label.ocf-selected[data-option-id="' + wfOptionId + '"]').each(function(index, element1) {
				if($(element1).attr('id').indexOf(wfOptionIdValue) + 1) {
					$(element1).trigger('click');
				}
			});
		});
		
		$(".ocfilter").on('click', '.selected-options .wf-ocfilter-cancel-all', function() {			
			
			$('.ocfilter-option label.ocf-selected').each(function(index, element1) {
				$(element1).removeClass('ocf-selected');
				$(element1).find('input').prop('checked', false);
				that.params.remove.call(that, $(element1).attr('data-option-id'));
			});

			if (parseInt($('#min-price-value')) != parseInt($('#scale-price').attr('data-range-min'))){
				$('#min-price-value').val(parseInt($('#scale-price').attr('data-range-min')));			
			}
			
			if (parseInt($('#max-price-value')) != parseInt($('#scale-price').attr('data-range-max'))){
				$('#max-price-value').val(parseInt($('#scale-price').attr('data-range-max')));
				
			}
			
			$('#max-price-value').trigger('change');	

			if ($('#v-cancel-s').length) {
				$('#v-cancel-s').addClass('ocf-selected');
				$('#v-cancel-s').find('input').prop('checked');
			}
			
			that.update($element);
		});
		
		// load more product
		$('.webfun_load_more_product').on('click', function(){
		that.update1($element);
		});
		
		// pagination without reload
		// $('.pagination a').click(function(){
		$(".pagination-row").on('click', '.pagination a', function() {
			if ($(this).parent().hasClass('active')) {
				return false;
			}
			
			$('.pagination a').removeClass('webfun_pag_active');
			$(this).addClass('webfun_pag_active');
			that.update2($element);
			return false;
		});
		// webfun end
		
		if ($element.data().controlMin) {
			that.$element.on('change', $element.data().controlMin, function(e) {
				if (this.value == '') {
					return false;
				}
				
				if (this.value < min || this.value > max) {
					this.value = min;
				}
				
				$element.get(0).noUiSlider.set([this.value, null]);
			});
		}
		
		if ($element.data().controlMax) {
			that.$element.on('change', $element.data().controlMax, function(e) {
				if (this.value == '') {
					return false;
				}
				
				if (this.value < min || this.value > max) {
					this.value = max;
				}
				
				$element.get(0).noUiSlider.set([null, this.value]);
			});
		}
	};
	
	var ocfilter = {
		timers: {},
		values: {},
		options: {},
		init: function(options) {
			this.options = $.extend({}, options);
			
			this.$element = $('#ocfilter');
			
			this.$fields = $('.option-values input', this.$element);
			
			this.$target = $('.ocf-target', this.$element);
			this.$values = $('label', this.$element);
			
			var that = this;
			
			this.$values.each(function() {
				that.values[$(this).attr('id')] = this;
			});
			
			this.$target.on('change', function(e) {
				e.preventDefault();
				
				var
				$element = $(this),
				$buttonTarget = $element.closest('label'),
				$dropdown = $element.closest('.dropdown');
				
				that.options.php.params = $element.val();
				
				if ($element.is(':radio')) {
					$element.closest('.ocf-option-values').find('label.ocf-selected').removeClass('ocf-selected');
				}
				
				$buttonTarget.toggleClass('ocf-selected', $element.prop('checked'));
				
				that.update($buttonTarget);
			});
			
			this.$element.on('click.ocf', '.dropdown-menu', function(e) {
				$(this).closest('.dropdown').one('hide.bs.dropdown', function(e) {
					return false;
				});
			});
			
			this.$element.on('click.ocf', '.disabled, [disabled]', function(e) {
				e.stopPropagation();
				e.preventDefault();
			});
			
			var hovered = false;
			
			// this.$element.on({
			//   'mouseenter': function(e) {
			//     hovered = true;
			//   },
			//   'mouseleave': function(e) {
			//     hovered = false;
			
			//     $('[aria-describedby="' + $(this).attr('id') + '"]').popover('toggle');
			//   }
			// }, '.popover').on('hide.bs.popover', '[aria-describedby^="popover"]', function(e) {
			//   setTimeout(function(element) {
			//     $(element).show();
			//   }, 0, e.target);
			
			//   if (hovered) {
			//     e.preventDefault();
			//   }
			// });
			
			// this.$element.find('.dropdown').on('hide.bs.dropdown', function(e) {
			//   that.$element.find('[aria-describedby^="popover"]').popover('hide');
			// });
			
			// if (this.options.php.manualPrice) {
			//   $('[data-toggle=\'popover-price\']').popover({
			//     content: function() {
			//       return '' +
			//         '<div class="form-inline">' +
			//           '<div class="form-group">' +
			//             '<input name="price[min]" value="' + $('#price-from').text() + '" type="text" class="form-control input-sm" id="min-price-value" />' +
			//           '</div>' +
			//           '<div class="form-group">-</div>' +
			//           '<div class="form-group">' +
			//             '<input name="price[max]" value="' + $('#price-to').text() + '" type="text" class="form-control input-sm" id="max-price-value" />' +
			//           '</div>' +
			//         '</div>';
			//     },
			//     html: true,
			//     delay: { 'show': 700, 'hide': 500 },
			//     placement: 'top',
			//     container: '#ocfilter',
			//     title: 'Указать цену',
			//     trigger: 'hover'
			//   });
			// }
			
			// Set sliders
			$('#ocfilter .scale').each($.proxy(setSlider, this));
			
			webfunUpdateSovmestimost();
		},
		
		update: function(scrollTarget) {
			var
			that = this,
			isSlider = scrollTarget.hasClass('scale'),
			data = {
				path: this.options.php.path,
				option_id: scrollTarget.data().optionId,
				webfun_sort: $.webfunUrlParam('sort'),
				webfun_order: $.webfunUrlParam('order'),
				webfun_limit: $.webfunUrlParam('limit')
			};
			
			if (this.options.php.params) {
				data[this.options.php.index] = this.options.php.params;
			}
			
			this.preload();
			
			$.get('index.php?route=extension/module/ocfilter/callback', data, function(json) {
				
				/* Start update */
				for (var i in json.values) {
					var value = json.values[i],
					target = $(that.values['v-' + i]),
					total = value.t,
					selected = value.s,
					params = value.p;
					
					if (target.length > 0) {
						if (target.is('label')) {
							if (total === 0 && !selected) {
								target.addClass('disabled').removeClass('ocf-selected').find('input').attr('disabled', true).prop('checked', false);
								} else {
								target.removeClass('disabled').find('input').removeAttr('disabled');
							}
							
							$('input', target).val(params);
							
							if (that.options.php.showCounter) {
								$('small', target).text(total);
							}
							} else {
							target.prop('disabled', (total === 0)).val(params);
						}
					}
				}
				
				if (json.total === 0) {
					$('#ocfilter-button button').removeAttr('onclick').addClass('disabled').text(that.options.text.select);
					
					if (typeof scrollTarget != 'undefined' && scrollTarget.hasClass('scale')) {
						$('#ocfilter .scale').removeAttr('disabled');
					}
					} else {
					if (that.options.php.searchButton || isSlider) {
						$('#ocfilter-button button').attr('onclick', 'location = \'' + json.href + '\'').removeClass('disabled').text(json.text_total);
						} else {
						window.location = json.href;
						
						return;
					}
				}
				
				that.$fields.filter('.enabled').removeAttr('disabled');
				
				if (typeof scrollTarget != 'undefined') {
					that.scroll(scrollTarget);
				}
				
				if (isSlider) {
					scrollTarget.removeAttr('disabled');
				}
				
				
				
				// webfun
				// update products
				if (json['webfun_products']) {
					$('#res-products .row').eq(0).html(json['webfun_products']);
				}
				
				// update filter block
				$('.ocfilter-option').each(function(index, element) {
					if ($(element).hasClass('webfun-option-price')) {
						return;
					}
					
					var countAllLabel = parseInt($(element).find('label').length, 10);
					var countDisableLabel = parseInt($(element).find('label.disabled').length, 10);
					var countActiveLabel = countAllLabel - countDisableLabel;
					if (countActiveLabel <= 0) {
						$(element).addClass('webfun_disabled');
						return;
						} else {
						$(element).removeClass('webfun_disabled');
					}
					
					$(element).find('.collapse').addClass('in');
					$(element).find('.collapse hr').addClass('webfun_disabled');
					$(element).find('.collapse-value').addClass('webfun_disabled');
				});
				
				webfunUpdateSovmestimost();
				
				// remove preloader
				$('.webfun_ocfilter_preloader').remove();
				
				// add page to history
				window.history.pushState({}, $('h1').html(), json['href'].replace(/&amp;/g, '&'));
				
				// update sort and limit selects
				if (json['sort']) {
					$('#input-sort').html(json['sort']);
				}
				if (json['limit']) {
					$('#input-limit').html(json['limit']);
				}
				
				// update selected filters
				if (json['selecteds']) {
					$('.webfun_selected_options_wrapper').html(json['selecteds']);
					
					if ($('.ocfilter .selected-options .ocfilter-option button').length == 0) {
						$('.webfun_selected_options_wrapper').html('');
					}
					} else {
					$('.webfun_selected_options_wrapper').html('');
				}
				
				// update pagination
				// if (json['pagination']) {
				$('.pagination-row > div').html(json['pagination']);
				// }
				
				if (json['pagination']) {
					$('.webfun_load_more_product').css('display','inline-block');
					} else {
					$('.webfun_load_more_product').css('display','none');
				}
				
				// update lang href
				var wfCurUrl = window.location.href;
				var wgCurUrlLink = $('.language-select a').attr('href');
				if (wgCurUrlLink.indexOf('ua/ua') + 1) {
					// if ukrainian
					$('#form-language .current-link').attr('href', wfCurUrl);
					$('#form-language a:not(.current-link)').attr('href', wfCurUrl.replace('/ua/', '/'));
					} else {
					// if russian
					$('#form-language .current-link').attr('href', wfCurUrl);
					$('#form-language a:not(.current-link)').attr('href', wfCurUrl.replace('vest.in.ua/', 'vest.in.ua/ua/'));
				}
				
				// update view-hide disabled options 2
				$('.ocfilter .webfun_disabled2').each(function(index12, element2) {
					if ($(element2).find('.ocf-option-values label').not('.disabled').length > 0) {
						$(element2).removeClass('webfun_disabled2');
					}
				});
				
				// update h1
				if (json['heading_title']) {
					$('h1').html(json['heading_title']);
				}
				
				$('#content .hidefilter').remove();
				
				// webfun end
				
				if (!$.isPlainObject(json.sliders) || $.isEmptyObject(json.sliders)) {
					return;
				}
				
				//TRY SCROLLTOP
				$('html, body').animate({scrollTop: $('h1.cat-header').offset().top-80}, 800);
				$('.webfun_load_more_product').attr('data-limit', 2);
				
				for (var option_id in json.sliders) {
					var
					$element = $('.scale[data-option-id="' + option_id + '"]').removeAttr('disabled'),
					slider = $element.get(0).noUiSlider,
					hasParam = that.params.has.call(that, option_id),
					min = parseFloat(json.sliders[option_id]['min']),
					max = parseFloat(json.sliders[option_id]['max']),
					min_value = min,
					max_value = max,
					set = slider.get();
					
					if (!$.isArray(set)) {
						set = [set, set];
					}
					
					if (hasParam) {
						if (set[1] <= max) {
							max_value = set[1];
						}
						
						if (set[0] >= min) {
							min_value = set[0];
						}
					}
					
					if (min != max) {
						slider.destroy();
						
						$element.data({
							startMin: min_value,
							startMax: max_value,
							rangeMin: min,
							rangeMax: max
						});
						
						if ($element.data().controlMin && $($element.data().controlMin).length) {
							$($element.data().controlMin).val(min_value);
						}
						
						if ($element.data().controlMax && $($element.data().controlMax).length) {
							$($element.data().controlMax).val(max_value);
						}
						
						if ($element.data().elementMin && $($element.data().elementMin).length) {
							$($element.data().elementMin).html(min_value);
						}
						
						if ($element.data().elementMax && $($element.data().elementMax).length) {
							$($element.data().elementMax).html(max_value);
						}
						
						setSlider.call(that, 0, $element.get(0));
						
						// webfun
						$('input[name="price[min]"]').removeAttr('disabled');
						$('input[name="price[max]"]').removeAttr('disabled');
						// webfun - end
						} else {
						$element.attr('disabled', 'disabled');
						
						// webfun
						$('input[name="price[min]"]').val(min).attr('disabled', 'disabled');
						$('input[name="price[max]"]').val(max).attr('disabled', 'disabled');
						// webfun - end
					}
				}
				/* End update */
			}, 'json');
		},
		
		update1: function(scrollTarget) {
			// var webfunNewLimit = (isNaN(parseInt($.webfunUrlParam('limit'), 10))) ? parseInt($('.webfun_load_more_product').attr('data-limit'), 10) * 18 : parseInt($.webfunUrlParam('limit'), 10) * parseInt($('.webfun_load_more_product').attr('data-limit'), 10);
			
			var date = new Date();
			var dateTimestamp = parseInt(date.getTime(), 10);
			if (dateTimestamp - parseInt(localStorage.getItem('wf_callback1'), 10) < 1000) {
				return false;
			}
			localStorage.setItem('wf_callback1', dateTimestamp);
			
			var
			that = this,
			isSlider = scrollTarget.hasClass('scale'),
			data = {
				path: this.options.php.path,
				option_id: scrollTarget.data().optionId,
				webfun_sort: $.webfunUrlParam('sort'),
				webfun_order: $.webfunUrlParam('order'),
				// webfun_limit: webfunNewLimit,
				webfun_limit: $.webfunUrlParam('limit'),
				webfun_data_limit: parseInt($('.webfun_load_more_product').attr('data-limit'), 10),
				webfun_page: $.webfunUrlParam('page')
			};
			
			if (this.options.php.params) {
				data[this.options.php.index] = this.options.php.params;
			}
			console.log(this.options.php.index);
			console.log(this.options.php.params);
			
			this.preload();
			
			$.get('index.php?route=extension/module/ocfilter/callback1', data, function(json) {
				/* Start update */
				for (var i in json.values) {
					var value = json.values[i],
					target = $(that.values['v-' + i]),
					total = value.t,
					selected = value.s,
					params = value.p;
					
					if (target.length > 0) {
						if (target.is('label')) {
							if (total === 0 && !selected) {
								target.addClass('disabled').removeClass('ocf-selected').find('input').attr('disabled', true).prop('checked', false);
								} else {
								target.removeClass('disabled').find('input').removeAttr('disabled');
							}
							
							$('input', target).val(params);
							
							if (that.options.php.showCounter) {
								$('small', target).text(total);
							}
							} else {
							target.prop('disabled', (total === 0)).val(params);
						}
					}
				}
				
				// if (json.total === 0) {
				//   $('#ocfilter-button button').removeAttr('onclick').addClass('disabled').text(that.options.text.select);
				
				//   if (typeof scrollTarget != 'undefined' && scrollTarget.hasClass('scale')) {
				//     $('#ocfilter .scale').removeAttr('disabled');
				//   }
				// } else {
				//   if (that.options.php.searchButton || isSlider) {
				//     $('#ocfilter-button button').attr('onclick', 'location = \'' + json.href + '\'').removeClass('disabled').text(json.text_total);
				//   } else {
				//     window.location = json.href;
				
				//     return;
				//   }
				// }
				
				that.$fields.filter('.enabled').removeAttr('disabled');
				
				if (typeof scrollTarget != 'undefined') {
					that.scroll(scrollTarget);
				}
				
				if (isSlider) {
					scrollTarget.removeAttr('disabled');
				}
				
				
				
				// webfun
				// update products
				if (json['webfun_products']) {
					// $('#res-products .row').eq(0).html(json['webfun_products']);
					$('#res-products .row').eq(0).append(json['webfun_products']);
				}
				
				// update filter block
				$('.ocfilter-option').each(function(index, element) {
					if ($(element).hasClass('webfun-option-price')) {
						return;
					}
					
					var countAllLabel = parseInt($(element).find('label').length, 10);
					var countDisableLabel = parseInt($(element).find('label.disabled').length, 10);
					var countActiveLabel = countAllLabel - countDisableLabel;
					if (countActiveLabel <= 0) {
						$(element).addClass('webfun_disabled');
						return;
						} else {
						$(element).removeClass('webfun_disabled');
					}
					
					$(element).find('.collapse').addClass('in');
					$(element).find('.collapse hr').addClass('webfun_disabled');
					$(element).find('.collapse-value').addClass('webfun_disabled');
				});
				
				webfunUpdateSovmestimost();
				
				// remove preloader
				$('.webfun_ocfilter_preloader').remove();
				
				// add page to history
				// window.history.pushState({}, $('h1').html(), json['href'].replace(/&amp;/g, '&'));
				
				// update sort and limit selects
				// if (json['sort']) {
				//   $('#input-sort').html(json['sort']);
				// }
				// if (json['limit']) {
				//   $('#input-limit').html(json['limit']);
				// }
				
				// update selected filters
				if (json['selecteds']) {
					$('.webfun_selected_options_wrapper').html(json['selecteds']);
					
					if ($('.ocfilter .selected-options .ocfilter-option button').length == 0) {
						$('.webfun_selected_options_wrapper').html('');
					}
					} else {
					$('.webfun_selected_options_wrapper').html('');
				}
				
				// update pagination
				// if (json['pagination']) {
				$('.pagination-row > div').html(json['pagination']);
				// }
				if (parseInt(json['total'], 10) > $('#res-products .product-grid').length) {
					$('.webfun_load_more_product').css('display','inline-block');
					// $('.webfun_row_load_more_product').append(json['wenfun_load_product_btn']);
					} else {
					$('.webfun_load_more_product').css('display','none');
					// $('.webfun_row_load_more_product').remove();
				}
				
				// update lang href
				// var wfCurUrl = window.location.href;
				// var wgCurUrlLink = $('.current-link').attr('href');
				// if (wgCurUrlLink.indexOf('ua/ua') + 1) {
				//   // if ukrainian
				//   $('.current-link').attr('href', wfCurUrl);
				//   $('#form-language a:not(.current-link)').attr('href', wfCurUrl.replace('/ua/', '/'));
				// } else {
				//   // if russian
				//   $('.current-link').attr('href', wfCurUrl);
				//   $('#form-language a:not(.current-link)').attr('href', wfCurUrl.replace('vest.in.ua/', 'vest.in.ua/ua/'));
				// }
				
				// update view-hide disabled options 2
				$('.ocfilter .webfun_disabled2').each(function(index12, element2) {
					if ($(element2).find('.ocf-option-values label').not('.disabled').length > 0) {
						$(element2).removeClass('webfun_disabled2');
					}
				});
				
				// update h1
				// if (json['heading_title']) {
				//   $('h1').html(json['heading_title']);
				// }
				
				// update data limit
				$('.webfun_load_more_product').attr('data-limit', parseInt($('.webfun_load_more_product').attr('data-limit'), 10) + 1);
				
				// webfun end
				
				if (!$.isPlainObject(json.sliders) || $.isEmptyObject(json.sliders)) {
					return;
				}
				
				// for (var option_id in json.sliders) {
				//   var
				//     $element = $('.scale[data-option-id="' + option_id + '"]').removeAttr('disabled'),
				//     slider = $element.get(0).noUiSlider,
				//     hasParam = that.params.has.call(that, option_id),
				//     min = parseFloat(json.sliders[option_id]['min']),
				//     max = parseFloat(json.sliders[option_id]['max']),
				//     min_value = min,
				//     max_value = max,
				//     set = slider.get();
				
				//   if (!$.isArray(set)) {
				//     set = [set, set];
				//   }
				
				//   if (hasParam) {
				//     if (set[1] <= max) {
				//       max_value = set[1];
				//     }
				
				//     if (set[0] >= min) {
				//       min_value = set[0];
				//     }
				//   }
				
				//   if (min != max) {
				//     slider.destroy();
				
				//     $element.data({
				//       startMin: min_value,
				//       startMax: max_value,
				//       rangeMin: min,
				//       rangeMax: max
				//     });
				
				//     if ($element.data().controlMin && $($element.data().controlMin).length) {
				//       $($element.data().controlMin).val(min_value);
				//     }
				
				//     if ($element.data().controlMax && $($element.data().controlMax).length) {
				//       $($element.data().controlMax).val(max_value);
				//     }
				
				//     if ($element.data().elementMin && $($element.data().elementMin).length) {
				//       $($element.data().elementMin).html(min_value);
				//     }
				
				//     if ($element.data().elementMax && $($element.data().elementMax).length) {
				//       $($element.data().elementMax).html(max_value);
				//     }
				
				//     setSlider.call(that, 0, $element.get(0));
				
				//     // webfun
				//     $('input[name="price[min]"]').removeAttr('disabled');
				//     $('input[name="price[max]"]').removeAttr('disabled');
				//     // webfun - end
				//   } else {
				//     $element.attr('disabled', 'disabled');
				
				//     // webfun
				//     $('input[name="price[min]"]').val(min).attr('disabled', 'disabled');
				//     $('input[name="price[max]"]').val(max).attr('disabled', 'disabled');
				//     // webfun - end
				//   }
				// }
				/* End update */
			}, 'json');
		},
		
		update2: function(scrollTarget) {
			var date = new Date();
			var dateTimestamp = parseInt(date.getTime(), 10);
			if (dateTimestamp - parseInt(localStorage.getItem('wf_callback1'), 10) < 1000) {
				return false;
			}
			localStorage.setItem('wf_callback1', dateTimestamp);
			
			var wfPag = $('.webfun_pag_active').html();
			if ($('.webfun_pag_active').attr('href').indexOf('page=') !== -1) {
				var wfSplit = $('.webfun_pag_active').attr('href').split('page=');
				wfPag = parseInt(wfSplit[1], 10);
			}
			
			var
			that = this,
			isSlider = scrollTarget.hasClass('scale'),
			data = {
				path: this.options.php.path,
				option_id: scrollTarget.data().optionId,
				webfun_sort: $.webfunUrlParam('sort'),
				webfun_order: $.webfunUrlParam('order'),
				webfun_limit: $.webfunUrlParam('limit'),
				webfun_page: wfPag
			};
			
			if (this.options.php.params) {
				data[this.options.php.index] = this.options.php.params;
			}
			
			this.preload();
			
			$.get('index.php?route=extension/module/ocfilter/callback2', data, function(json) {
				/* Start update */
				for (var i in json.values) {
					var value = json.values[i],
					target = $(that.values['v-' + i]),
					total = value.t,
					selected = value.s,
					params = value.p;
					
					if (target.length > 0) {
						if (target.is('label')) {
							if (total === 0 && !selected) {
								target.addClass('disabled').removeClass('ocf-selected').find('input').attr('disabled', true).prop('checked', false);
								} else {
								target.removeClass('disabled').find('input').removeAttr('disabled');
							}
							
							$('input', target).val(params);
							
							if (that.options.php.showCounter) {
								$('small', target).text(total);
							}
							} else {
							target.prop('disabled', (total === 0)).val(params);
						}
					}
				}
				
				if (json.total === 0) {
					$('#ocfilter-button button').removeAttr('onclick').addClass('disabled').text(that.options.text.select);
					
					if (typeof scrollTarget != 'undefined' && scrollTarget.hasClass('scale')) {
						$('#ocfilter .scale').removeAttr('disabled');
					}
					} else {
					if (that.options.php.searchButton || isSlider) {
						$('#ocfilter-button button').attr('onclick', 'location = \'' + json.href + '\'').removeClass('disabled').text(json.text_total);
						} else {
						window.location = json.href;
						
						return;
					}
				}
				
				that.$fields.filter('.enabled').removeAttr('disabled');
				
				if (typeof scrollTarget != 'undefined') {
					that.scroll(scrollTarget);
				}
				
				if (isSlider) {
					scrollTarget.removeAttr('disabled');
				}
				
				
				
				// webfun
				// update products
				if (json['webfun_products']) {
					$('#res-products .row').eq(0).html(json['webfun_products']);
				}
				$("html, body").animate({
					scrollTop : $('h1').offset().top + 50
				}, 1000);
				// update filter block
				$('.ocfilter-option').each(function(index, element) {
					if ($(element).hasClass('webfun-option-price')) {
						return;
					}
					
					var countAllLabel = parseInt($(element).find('label').length, 10);
					var countDisableLabel = parseInt($(element).find('label.disabled').length, 10);
					var countActiveLabel = countAllLabel - countDisableLabel;
					if (countActiveLabel <= 0) {
						$(element).addClass('webfun_disabled');
						return;
						} else {
						$(element).removeClass('webfun_disabled');
					}
					
					$(element).find('.collapse').addClass('in');
					$(element).find('.collapse hr').addClass('webfun_disabled');
					$(element).find('.collapse-value').addClass('webfun_disabled');
				});
				
				webfunUpdateSovmestimost();
				
				// remove preloader
				$('.webfun_ocfilter_preloader').remove();
				
				// add page to history
				window.history.pushState({}, $('h1').html(), json['href'].replace(/&amp;/g, '&'));
				
				// update sort and limit selects
				if (json['sort']) {
					$('#input-sort').html(json['sort']);
				}
				if (json['limit']) {
					$('#input-limit').html(json['limit']);
				}
				
				// update selected filters
				if (json['selecteds']) {
					$('.webfun_selected_options_wrapper').html(json['selecteds']);
					
					if ($('.ocfilter .selected-options .ocfilter-option button').length == 0) {
						$('.webfun_selected_options_wrapper').html('');
					}
					} else {
					$('.webfun_selected_options_wrapper').html('');
				}
				
				// update pagination
				// if (json['pagination']) {
				$('.pagination-row > div').html(json['pagination']);
				// }
				
				// if (json['pagination']) {
				// console.log($('.pagination li.active').index('.pagination li'), $('.pagination li').length);
				if ($('.pagination li.active').index('.pagination li') != $('.pagination li').length - 1) {
					$('.webfun_load_more_product').css('display','inline-block');
					} else {
					$('.webfun_load_more_product').css('display','none');
				}
				
				// update lang href
				var wfCurUrl = window.location.href;
				var wgCurUrlLink = $('.current-link').attr('href');
				if (wgCurUrlLink.indexOf('ua/ua') + 1) {
					// if ukrainian
					$('#form-language .current-link').attr('href', wfCurUrl);
					$('#form-language a:not(.current-link)').attr('href', wfCurUrl.replace('/ua/', '/'));
					} else {
					// if russian
					$('#form-language .current-link').attr('href', wfCurUrl);
					$('#form-language a:not(.current-link)').attr('href', wfCurUrl.replace('vest.in.ua/', 'vest.in.ua/ua/'));
				}
				
				// update view-hide disabled options 2
				$('.ocfilter .webfun_disabled2').each(function(index12, element2) {
					if ($(element2).find('.ocf-option-values label').not('.disabled').length > 0) {
						$(element2).removeClass('webfun_disabled2');
					}
				});
				
				// update h1
				if (json['heading_title']) {
					$('h1').html(json['heading_title']);
				}
				
				$("html, body").animate({
					scrollTop : $('h1').offset().top + 50
				}, 600);
				
				// webfun end
				
				if (!$.isPlainObject(json.sliders) || $.isEmptyObject(json.sliders)) {
					return;
				}
				
				for (var option_id in json.sliders) {
					var
					$element = $('.scale[data-option-id="' + option_id + '"]').removeAttr('disabled'),
					slider = $element.get(0).noUiSlider,
					hasParam = that.params.has.call(that, option_id),
					min = parseFloat(json.sliders[option_id]['min']),
					max = parseFloat(json.sliders[option_id]['max']),
					min_value = min,
					max_value = max,
					set = slider.get();
					
					if (!$.isArray(set)) {
						set = [set, set];
					}
					
					if (hasParam) {
						if (set[1] <= max) {
							max_value = set[1];
						}
						
						if (set[0] >= min) {
							min_value = set[0];
						}
					}
					
					if (min != max) {
						slider.destroy();
						
						$element.data({
							startMin: min_value,
							startMax: max_value,
							rangeMin: min,
							rangeMax: max
						});
						
						if ($element.data().controlMin && $($element.data().controlMin).length) {
							$($element.data().controlMin).val(min_value);
						}
						
						if ($element.data().controlMax && $($element.data().controlMax).length) {
							$($element.data().controlMax).val(max_value);
						}
						
						if ($element.data().elementMin && $($element.data().elementMin).length) {
							$($element.data().elementMin).html(min_value);
						}
						
						if ($element.data().elementMax && $($element.data().elementMax).length) {
							$($element.data().elementMax).html(max_value);
						}
						
						setSlider.call(that, 0, $element.get(0));
						
						// webfun
						$('input[name="price[min]"]').removeAttr('disabled');
						$('input[name="price[max]"]').removeAttr('disabled');
						// webfun - end
						} else {
						$element.attr('disabled', 'disabled');
						
						// webfun
						$('input[name="price[min]"]').val(min).attr('disabled', 'disabled');
						$('input[name="price[max]"]').val(max).attr('disabled', 'disabled');
						// webfun - end
					}
				}
				/* End update */
			}, 'json');
		},
		
		params: {
			decode: function() {
				var params = {};
				if (this.options.php.params) {
					var matches = this.options.php.params.split(';');
					for (var i = 0; i < matches.length; i++) {
						var parts = matches[i].split(':');
						params[parts[0]] = typeof parts[1] != 'undefined' ? parts[1].split(',') : [];
					}
				}
				this.options.php.params = params;
			},
			
			encode: function() {
				var params = [];
				if (this.options.php.params) {
					for (i in this.options.php.params) {
						params.push(i + ':' + (typeof this.options.php.params[i] == 'object' ? this.options.php.params[i].join(',') : this.options.php.params[i]));
					}
				}
				this.options.php.params = params.join(';');
			},
			
			set: function(option_id, value_id) {
				this.params.decode.call(this);
				if (typeof this.options.php.params[option_id] != 'undefined') {
					this.options.php.params[option_id].push(value_id);
					} else {
					this.options.php.params[option_id] = [value_id];
				}
				this.params.encode.call(this);
			},
			
			has: function(option_id) {
				this.params.decode.call(this);
				
				var has = (typeof this.options.php.params[option_id] != 'undefined');
				
				this.params.encode.call(this);
				
				return has;
			},
			
			remove: function(option_id, value_id) {
				this.params.decode.call(this);
				if (typeof this.options.php.params[option_id] != 'undefined') {
					if (this.options.php.params[option_id].length === 1 || !value_id) {
						delete this.options.php.params[option_id];
						} else {
						this.options.php.params[option_id].splice(ocfilter.options.php.params[option_id].indexOf(value_id), 1);
					}
				}
				this.params.encode.call(this);
			}
		},
		
		preload: function() {
			if ($('.ocfilter-option-popover').length) {
				$('.ocfilter-option-popover button').button('loading');
			}
			
			this.$element.find('.scale').attr('disabled', 'disabled');
			setTimeout(function(that) {
				that.$values.addClass('disabled').find('small').text('0');
			}, 10, this);
			
			$('.ocfilter').append('<div class="webfun_ocfilter_preloader"></div>');
			$('#res-products .row').eq(0).append('<div class="webfun_ocfilter_preloader"></div>');
		},
		
		scroll: function(target) {
			var that = this;
			
			if (target.find('input:checked').length < 1 && target.parent().find('input:checked').length > 0) {
				target = target.parent().find('input:checked:first').parent();
			}
			
			if (that.options.mobile && target.is('label')) {
				target = target.find('input');
			}
			
			if (target.is(':hidden')) {
				target = target.parents(':visible:first');
			}
			
			// this.$element
			//   .find('[aria-describedby^="popover"]')
			//   .not('[data-toggle="popover-price"]')
			//   .not(target)
			//   .popover('destroy');
			
			// if (!target.attr('aria-describedby')) {
			//   var options = {
			//     placement: that.options.mobile ? 'bottom' : 'right',
			//     selector: that.options.mobile ? '> input' : false,
			//     delay: { 'show': 400, 'hide': 600 },
			//     content: function() {
			//       return $('#ocfilter-button').html();
			//     },
			//     container: that.$element,
			//     trigger: 'hover',
			//     html: true
			//   };
			
			//   target.popover(options).popover('show');
			
			//   $('#' + target.attr('aria-describedby')).addClass('ocfilter-option-popover');
			// } else {
			//   $('#' + target.attr('aria-describedby') + ' button').replaceWith($('#ocfilter-button').html());
			// }
		}
	};
	
	/* IE6+ */
	if (Object.create === undefined) {
		Object.create = function(object) {
			function f() {};
			f.prototype = object;
			return new f();
		};
	}
	
	$.fn.ocfilter = function(options) {
		return this.each(function() {
			var $element = $(this);
			
			if ($element.data('ocfilter')) {
				return $element.data('ocfilter');
			}
			
			$element.data('ocfilter', Object.create(ocfilter).init(options, $element));
		});
	};
	
	// webfun
	function webfunUpdateSovmestimost() {
		
		if (typeof($("img").lazyload) === "function"){
			setTimeout(function() {
				$("img.lazy").lazyload({
					effect : "fadeIn"
				});
			}, 10);
		}
		
		// update sovmestimost - model
		if ($('.ocfilter-option[data-wf-sort="0"] .ocf-selected').length > 0) {
			$('.ocfilter-option[data-wf-sort="0"] .ocf-selected').each(function(index, element) {
				var wfLabelName = $(element).text();
				wfLabelName = wfLabelName.replace(' ', '_');
				wfLabelName = wfLabelName.replace(/\s+/g, '');

				console.log(wfLabelName);
				
				if(wfLabelName.indexOf('+') > -1) {
					var parts = wfLabelName.split('+');
					wfLabelName = parts[0];
				}
				
				$('.ocfilter-option[data-wf-sort="-2"]').each(function(index1, element1) {
					var wfSectionName = $.trim($(element1).find('.ocf-option-name').html());
					if (wfSectionName.indexOf(wfLabelName.replace('_', ' ')) > -1) {
						// $(element1).removeClass('webfun_disabled').removeClass('webfun_disabled1');
						$(element1).removeClass('webfun_disabled1');
					}
				});
			});
			} else {
			$('.ocfilter-option[data-wf-sort="-2"]').addClass('webfun_disabled1');
		}
	}
	// webfun end
})(jQuery);