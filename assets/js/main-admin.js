(function($)
{
	$(document).ready(
	function()
	{
		$('#pce_admin_form_save_btn').click(
		function(e)
		{
			var formData = $(this).closest('form').serialize();
			$('#pce_admin_form_from_languages_list option').each(
			function()
			{
				formData += '&pce_shipping_price_from_languages_list[]=' + $(this).val();
			});
			$('#pce_admin_form_to_languages_list option').each(
			function()
			{
				formData += '&pce_shipping_price_to_languages_list[]=' + $(this).val();
			});
			
			$.ajax(
			{
				url: ajaxurl,
				async: false,
				type: 'POST',
				data: formData,
				success: function success(result)
				{
					// var result = JSON.parse(result);
					$('.pce-admin-form-message').text(result.result);
					// if (result.statusCode == 200)
					// {
						
					// }
				}
			});
		});
		
		$('#pce_admin_form_add_from_language_btn').click(
		function(e)
		{
			var language = $('#pce_admin_form_add_from_language_value').val();
			if(language)
			{
				var alreadyExists = false;
				$('#pce_admin_form_from_languages_list option').each(
				function(index)
				{
					if($(this).val() === language)
					{
						alreadyExists = true;
						return;
					}
				});
				if(!alreadyExists)
				{
					$('#pce_admin_form_from_languages_list').append($('<option>', 
					{
						value: language,
						text: language
					}));
				}
			}
		});
		
		$('#pce_admin_form_remove_from_language_btn').click(
		function(e)
		{
			$('#pce_admin_form_from_languages_list option:selected').remove();
		});
		
		$('#pce_admin_form_add_to_language_btn').click(
		function(e)
		{
			var language = $('#pce_admin_form_add_to_language_value').val();
			if(language)
			{
				
				var alreadyExists = false;
				$('#pce_admin_form_to_languages_list option').each(
				function(index)
				{
					if($(this).val() === language)
					{
						alreadyExists = true;
						return;
					}
				});
				if(!alreadyExists)
				{
					$('#pce_admin_form_to_languages_list').append($('<option>', 
					{
						value: language,
						text: language
					}));
				}
			}
		});
		
		$('#pce_admin_form_remove_to_language_btn').click(
		function(e)
		{
			$('#pce_admin_form_to_languages_list option:selected').remove();
		});
	});
})(jQuery)