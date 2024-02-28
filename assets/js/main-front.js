Dropzone.autoDiscover = false;

// Dropzone.options.myAwesomeDropzone = {
  // paramName: "page_count_file", // The name that will be used to transfer the file
  // maxFilesize: 10, // MB
  // /*
  // accept: function(file, done) {
    // if (file.name == "..") {
      
    // }
    // else { done(); }
  // }
  // */
// };

(function($)
{
	$(document).ready(
	function()
	{
		$(".pce-form-language-from-input").selectize(
		{
			create: true,
			sortField: 'text',
			maxItems: 1
		});
		
		$(".pce-form-language-to-input").selectize(
		{
			plugins: ['remove_button']
		});
		
		var $dropZone = $(".pce-form-files-block-dropzone").dropzone(
		{
			url: dropZoneParams.upload,
			previewsContainer: '.pce-form-files-block-dropzone-previews-container',
			init: function()
			{
				this.on("sending", 
				function(file, xhr, formData)
				{
					var $pceContainer = $($(this)[0].element).closest('.pce-form-content');
					var withApostille = $pceContainer.find('#pce-form-apostille-checkbox').prop('checked');
					var languageFrom = $pceContainer.find('.pce-form-language-from-input').val();
					var languagesTo = $pceContainer.find('.pce-form-language-to-input').val();
					formData.append('with_apostille', withApostille);
					formData.append('language_from', languageFrom);
					formData.append('languages_to', languagesTo);
				});
			},
			success: function (file, response)
			{
				var $pceContainer = $($(this)[0].element).closest('.pce-form-content');
				if(true === response.success)
				{
					if('undefined' !== typeof response.data)
					{
						$pceContainer.find('.pce-number-of-pages-amount').text(response.data.page_count);
						$pceContainer.find('.pce-translation-price-amount').text(pceParams.currency_unit + ' ' + response.data.translation_price.toFixed(2));
						$pceContainer.find('.pce-authentication-amount').text(pceParams.currency_unit + ' ' + response.data.authentication_price.toFixed(2));
						$pceContainer.find('.pce-apostille-amount').text(pceParams.currency_unit + ' ' + response.data.apostille_price.toFixed(2));
						$pceContainer.find('.pce-shipping-amount').text(pceParams.currency_unit + ' ' + response.data.shipping_price.toFixed(2));
						$pceContainer.find('.pce-total-amount').text(pceParams.currency_unit + ' ' + response.data.total_price.toFixed(2));
						$pceContainer.find('.pce-plus-vat-amount').text(pceParams.currency_unit + ' ' + response.data.vat_price.toFixed(2));
						$pceContainer.find('.pce-amount-including-vat-amount').text(pceParams.currency_unit + ' ' + response.data.amount_including_vat.toFixed(2));
					}
				}
				else if('undefined' !== typeof response.data.msg)
				{
					$resultMsg = $pceContainer.next('.pce-form-result-msg');
					$resultMsg.text(response.data.msg);
					$resultMsg.show();
					setTimeout(function(){$resultMsg.fadeOut(1000);;}, 2500);
				}
			},
			error: function (file, response)
			{
			},
			removedfile: function(file)
			{
			}
		});
		
		$('.pce-form-files-block-dropzone button').click(
		function(e)
		{
			$(this).closest('.pce-form-files-block-dropzone').click();
		});
		
		$('.pce-form-apostille-block-checkbox, .pce-form-language-to-input').change(function()
		{
			updatePceData();
		});
			
		function updatePceData()
		{
			
			var $pceContainer = $('.pce-form-content'),
				withApostille = $pceContainer.find('#pce-form-apostille-checkbox').prop('checked'),
				languageFrom = $pceContainer.find('.pce-form-language-from-input').val(),
				languagesTo = $pceContainer.find('.pce-form-language-to-input').val();
			//
			var arrPost = 
				{
					action: 'pce_update_calc_amount',
					apostille: withApostille,
					language_from: languageFrom,
					languages_to: languagesTo
				};
			//
			$.ajax({
				url: pceParams.ajax,
				async: false,
				type: 'POST',
				data: arrPost,
				success: function success(response)
				{
					if(true === response.success)
					{
						if('undefined' !== typeof response.data)
						{
							$pceContainer.find('.pce-number-of-pages-amount').text(response.data.page_count);
							$pceContainer.find('.pce-translation-price-amount').text(pceParams.currency_unit + ' ' + response.data.translation_price.toFixed(2));
							$pceContainer.find('.pce-authentication-amount').text(pceParams.currency_unit + ' ' + response.data.authentication_price.toFixed(2));
							$pceContainer.find('.pce-apostille-amount').text(pceParams.currency_unit + ' ' + response.data.apostille_price.toFixed(2));
							$pceContainer.find('.pce-shipping-amount').text(pceParams.currency_unit + ' ' + response.data.shipping_price.toFixed(2));
							$pceContainer.find('.pce-total-amount').text(pceParams.currency_unit + ' ' + response.data.total_price.toFixed(2));
							$pceContainer.find('.pce-plus-vat-amount').text(pceParams.currency_unit + ' ' + response.data.vat_price.toFixed(2));
							$pceContainer.find('.pce-amount-including-vat-amount').text(pceParams.currency_unit + ' ' + response.data.amount_including_vat.toFixed(2));
						}
					}
					else if('undefined' !== typeof response.data.msg)
					{
						$resultMsg = $pceContainer.next('.pce-form-result-msg');
						$resultMsg.text(response.data.msg);
						$resultMsg.show();
						setTimeout(function(){$resultMsg.fadeOut(1000);}, 2500);
					}
				}
			});
		}
		
		$('.pce-form-wrapper .pce-form-order-button').click(
		function(e)
		{
			e.preventDefault();
			var $pceContainer = $('.pce-form-content'),
				withApostille = $pceContainer.find('#pce-form-apostille-checkbox').prop('checked'),
				languageFrom = $pceContainer.find('.pce-form-language-from-input').val(),
				languagesTo = $pceContainer.find('.pce-form-language-to-input').val();
			//
			var arrPost = 
				{
					action: 'pce_prod_checkout',
					apostille: withApostille,
					language_from: languageFrom,
					languages_to: languagesTo
				};
			//
			
			
			
			$.ajax({
				url: pceParams.ajax,
				async: false,
				type: 'POST',
				data: arrPost,
				success: function(response)
				{
					if(true === response.success)
					{
						if('undefined' !== typeof response.data.action)
						{
							if('open_contact_form' === response.data.action)
							{
								var modalContent = '';
								modalContent += '<div class="pce-contact">';
								modalContent += '	<div class="pce-contact-name-box">';
								modalContent += '		<input class="pce-contact-name-input" placeholder="' + pceParams.translate.Name + '">';
								modalContent += '	</div>';
								modalContent += '	<div class="pce-contact-email-box">';
								modalContent += '		<input class="pce-contact-email-input" placeholder="' + pceParams.translate.Email + '">';
								modalContent += '	</div>';
								modalContent += '	<div class="pce-contact-comment-box">';
								modalContent += '		<textarea class="pce-contact-comment-textarea" placeholder="' + pceParams.translate.Comments + '"></textarea>';
								modalContent += '	</div>';
								modalContent += '	<div class="pce-contact-submit-box">';
								modalContent += '		<button class="pce-contact-submit" id="pce-contact-submit">' + pceParams.translate.Send + '</button>';
								modalContent += '	</div>';
								modalContent += '	<div class="pce-contact-result-msg">';
								modalContent += '	</div>';
								modalContent += '</div>';
								cmbzxModalShow(modalContent, pceParams.translate.Please_contact_us);
								
								$('.pce-contact-submit').click(
								function(e)
								{
									e.preventDefault();
									var $pceContactContainer = $('.pce-contact'),
										name = $pceContactContainer.find('.pce-contact-name-input').val(),
										email = $pceContactContainer.find('.pce-contact-email-input').val(),
										comment = $pceContactContainer.find('.pce-contact-comment-textarea').val();
									//
									var arrPost = 
									{
										action: 'pce_prod_contact',
										name: name,
										email: email,
										comment: comment
									};
									$.ajax({
										url: pceParams.ajax,
										async: false,
										type: 'POST',
										data: arrPost,
										success: function(response)
										{
											if(true === response.success)
											{
												$pceContactContainer.find('.pce-contact-result-msg').html(response.data.msg);
											}
											else
											{
												$pceContactContainer.find('.pce-contact-result-msg').html(response.data.msg);
												$pceContactContainer.closest('#cmb-z-modal-x').removeClass('cmb-z-modal-x-open');
											}
										}
									});
								});
								return;
							}
							else if('go_to_cart' === response.data.action)
							{
								if('undefined' !== typeof response.data.cart_url)
								{
									location.href = response.data.cart_url;
								}
							}
						}
						
					}
					else if('undefined' !== typeof response.data.msg)
					{
						$resultMsg = $pceContainer.next('.pce-form-result-msg');
						$resultMsg.text(response.data.msg);
						$resultMsg.show();
						setTimeout(function(){$resultMsg.fadeOut(1000);}, 2500);
					}
				}
			});
		});
		
		
		// Modal box
		function cmbzxModalInit()
		{
			var classThis = this;
			var html = '';
			html += '<div id="cmb-z-modal-x" class="cmb-z-modal-x">';
			html += '	<div class="cmb-z-modal-x-content">';
			html += '		<div class="cmb-z-modal-x-header">';
			html += '			<div class="cmb-z-modal-x-header-content">';
			html += '			</div>';
			html += '			<span class="cmb-z-modal-x-close">&times;</span>';
			html += '		</div>';
			html += '		<div class="cmb-z-modal-x-body">';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			
			if(!$('body').find('#cmb-z-modal-x').length)
			{
				$('body').append(html);
			
				$('body').find('#cmb-z-modal-x').on('click', 
				function(e)
				{
					if(e.target !== this)
					{
						return;
					}
					$(this).closest('#cmb-z-modal-x').removeClass('cmb-z-modal-x-open');
				});
				
				//
				$('body').find('#cmb-z-modal-x .cmb-z-modal-x-close').on('click', 
				function(e)
				{
					$(this).closest('#cmb-z-modal-x').removeClass('cmb-z-modal-x-open');
				});
				
				//
				/*
				this.$shopObj.delegate('......', 'click', 
				function(e)
				{
					$(this).closest('#nadlo-shop-modal').removeClass('nadlo-shop-modal-open');
				});
				*/
			}
		}
		
		function cmbzxModalShow(content, headerContent)
		{
			cmbzxModalInit();
			
			if(headerContent)
			{
				$('#cmb-z-modal-x .cmb-z-modal-x-header-content').html(headerContent);
			}
			else
			{
				$('#cmb-z-modal-x .cmb-z-modal-x-header-content').html('');
			}
			
			if(content)
			{
				$('#cmb-z-modal-x .cmb-z-modal-x-body').html(content);
			}
			else
			{
				$('#cmb-z-modal-x .cmb-z-modal-x-body').html('');
			}
			
			$('body').find('#cmb-z-modal-x').addClass('cmb-z-modal-x-open');
		}
		
	});
})(jQuery)
