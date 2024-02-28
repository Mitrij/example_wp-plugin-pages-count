<?php
if(!defined('WPINC'))
{
	die;
}

add_action('wp_ajax_pce_upload_file', 'pagesCountEstimateUploadFile');
add_action('wp_ajax_nopriv_pce_upload_file', 'pagesCountEstimateUploadFile');
function pagesCountEstimateUploadFile()
{
	foreach($_FILES as $file)
	{
		if(pagesCountEstimateIsUploadedFileAllowed($file))
		{
			if($uploadedFile = pagesCountEstimateSaveUploadedFile($file))
			{
				pagesCountEstimateAddFileToSession($uploadedFile);
			}
		}
		else
		{
			wp_send_json_error(['msg' => __('Something is wrong, please contact admin or support', 'wp-pages-count-estimate')]);
		}
	}
	$withApostille = (!empty($_POST['apostille']) && 'true' === $_POST['apostille']) ? true : false;
	$languageFrom = (!empty($_POST['language_from'])) ? strval($_POST['language_from']) : '';
	$languagesTo = (!empty($_POST['languages_to'])) ? $_POST['languages_to'] : [];
	
	$estimateData = pagesCountEstimateCalcData($languageFrom, $languagesTo, $withApostille);
	wp_send_json_success($estimateData);
}


add_action('wp_ajax_pce_update_calc_amount', 'pagesCountEstimateUpdateCalcAmount');
add_action('wp_ajax_nopriv_pce_update_calc_amount', 'pagesCountEstimateUpdateCalcAmount');
function pagesCountEstimateUpdateCalcAmount()
{
	$withApostille = (!empty($_POST['apostille']) && 'true' === $_POST['apostille']) ? true : false;
	$languageFrom = (!empty($_POST['languageFrom'])) ? strval($_POST['apostille']) : '';
	$languagesTo = (!empty($_POST['languages_to'])) ? $_POST['languages_to'] : [];
	
	$estimateData = pagesCountEstimateCalcData($languageFrom, $languagesTo, $withApostille);
	wp_send_json_success($estimateData);
}


add_action('wp_ajax_pce_prod_checkout', 'pagesCountEstimateProdCheckout');
add_action('wp_ajax_nopriv_pce_prod_checkout', 'pagesCountEstimateProdCheckout');
function pagesCountEstimateProdCheckout()
{
	$withApostille = (!empty($_POST['apostille']) && 'true' === $_POST['apostille']) ? true : false;
	$languageFrom = (!empty($_POST['languageFrom'])) ? strval($_POST['apostille']) : '';
	$languagesTo = (!empty($_POST['languages_to'])) ? $_POST['languages_to'] : [];
	
	$estimateData = pagesCountEstimateCalcData($languageFrom, $languagesTo, $withApostille);
	
	$retData = [];
	if(!empty($estimateData['page_count']) && !empty($estimateData['amount_including_vat']))
	{
		if($estimateData['page_count'] > 5)
		{
			$retData['action'] = 'open_contact_form';
			wp_send_json_success($retData);
		}
		else
		{
			global $woocommerce;
			if(!empty($woocommerce))
			{
				$adminSettings = pagesCountEstimateAdminGetSettings();
				// Cart item data to send & save in order
				$cartItemData = array('pce_prod_type' => 'translate', 'custom_price' => $estimateData['amount_including_vat']);   
				// woocommerce function to add product into cart check its documentation also 
				// what we need here is only $product_id & $cart_item_data other can be default.
				$woocommerce->cart->add_to_cart($adminSettings['pce_product_id'], 1, 0, [], $cartItemData);
				// Calculate totals
				$woocommerce->cart->calculate_totals();
				// Save cart to session
				$woocommerce->cart->set_session();
				// Maybe set cart cookies
				$woocommerce->cart->maybe_set_cart_cookies();
				
				$retData['action'] = 'go_to_cart';
				$retData['cart_url'] = wc_get_cart_url();
				wp_send_json_success($retData);
			}
		}
	}
	wp_send_json_error(['msg' => __('Something is wrong, please check all data', 'wp-pages-count-estimate')]);
}


add_action('wp_ajax_pce_prod_contact', 'pagesCountEstimateProdContact');
add_action('wp_ajax_nopriv_pce_prod_contact', 'pagesCountEstimateProdContact');
function pagesCountEstimateProdContact()
{
	$name = (!empty($_POST['name'])) ? strval($_POST['name']) : '';
	$email = (!empty($_POST['email'])) ? strval($_POST['email']) : '';
	$email = (!empty($_POST['comment'])) ? strval($_POST['comment']) : '';
	
	pagesCountEstimateStartSession();
	$filesToSend = [];
	$adminSettings = pagesCountEstimateAdminGetSettings();
	// $estimateData = pagesCountEstimateCalcData($languageFrom, $languagesTo, $withApostille);
	if(!empty($_SESSION['pages_count_estimate']))
	{
		foreach($_SESSION['pages_count_estimate']['files'] as $file)
		{
			$filesToSend[] = $file['file_path'];
		}
	}
	
	$to = $adminSettings['pce_email'];
	$subject = __('Translation request form', 'wp-pages-count-estimate');
	$message = __('Email', 'wp-pages-count-estimate') . ': ' . $email . "\n<br />";
	$message .= __('Name', 'wp-pages-count-estimate') . ': ' . $name . "\n<br />";
	$message .= __('Comment', 'wp-pages-count-estimate') . ': ' . $comment . "\n<br />";
	
	$headers = 	[
					'Content-Type: text/html; charset=UTF-8',
					'Reply-To: ' . $name . ' <' . $email . '>'
				];
	//
	if(wp_mail($to, $subject, $message, $headers, $filesToSend))
	{
		wp_send_json_success(['msg' => __('Sent', 'wp-pages-count-estimate')]);
	}
	else
	{
		wp_send_json_error(['msg' => __('Something is wrong, email not sent', 'wp-pages-count-estimate')]);
	}
}



add_action('wp_ajax_pce_admin_form_save', 'pagesCountEstimateAdminFormSave');
function pagesCountEstimateAdminFormSave()
{
	if(current_user_can('editor') || current_user_can('administrator'))
	{
		$settingsArrKeys = [
							'pce_vat', 'pce_apostille_price', 'pce_authentication_price', 'pce_shipping_price', 
							'pce_shipping_price_from_languages_list', 'pce_shipping_price_to_languages_list',
							'pce_1_3_pages_price', 'pce_4_5_pages_price', 'pce_product_id', 'pce_email'
							];
		$settingsArr = [];
		foreach($_POST as $key => $value)
		{
			if(in_array($key, $settingsArrKeys))
			{
				$settingsArr[$key] = $value;
			}
		}
		pagesCountEstimateAdminSaveSettings($settingsArr);
	}
}
?>