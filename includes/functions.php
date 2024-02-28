<?php
if(!defined('WPINC'))
{
	die;
}

require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/FPDI/src/autoload.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/Psr/autoloader.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/phpoffice/PhpSpreadsheet/autoloader.php';

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

function pagesCountEstimateIsUploadedFileAllowed($file)
{
	if(!is_file($file['tmp_name']))
	{
		return false;
	}
	
	$pathParts = pathinfo($file["name"]);
	$extension = $pathParts['extension'];
	if(!in_array(mb_strtolower($extension), ['pdf', 'xls', 'xlsx', 'doc', 'docx', 'pptx'], true))
	{
		return false;
	}
	
	if(filesize($file['tmp_name']) > 10 * 1024 * 1024)
	{
		return false;
	}
	
	return true;
}

function pagesCountEstimateCleanUploadedFiles()
{
	$folderPath = WP_PAGECOUNT_ESTIMATE_FILES_STORAGE;
	$time = 2 * 24 * 60 * 60;
	if(file_exists($folderPath))
	{
		foreach(new DirectoryIterator($folderPath) as $fileInfo)
		{
			if ($fileInfo->isDot())
			{
				continue;
			}
			if ($fileInfo->isFile() && time() - $fileInfo->getCTime() >= $time)
			{
				unlink($fileInfo->getRealPath());
			}
		}
	}
}

function pagesCountEstimateSaveUploadedFile($file)
{
	$dirPath = WP_PAGECOUNT_ESTIMATE_FILES_STORAGE;
	if( !is_dir($dirPath) )
	{
		if( !mkdir($dirPath, 0777, true) )
		{
			return false;
		}
	}
	
	$pathParts = pathinfo($file["name"]);
	$extension = $pathParts['extension'];
	$filePath = $dirPath . DIRECTORY_SEPARATOR . date('Ymdhis') . '-' . pagesCountEstimateGenerateRandomString() . '.' . $extension;
	if(move_uploaded_file($file['tmp_name'] , $filePath))
	{
		return $filePath;
	}
	return false;
}

function pagesCountEstimateAddFileToSession($filePath)
{
	pagesCountEstimateStartSession();
	if($fileData = pagesCountEstimateFileGetInfo($filePath))
	{
		if(empty($_SESSION['pages_count_estimate']['files']))
		{
			$_SESSION['pages_count_estimate']['files'] = [];
		}
		$_SESSION['pages_count_estimate']['files'][] = $fileData;
	}
}

function pagesCountEstimateClearSession()
{
	pagesCountEstimateStartSession();
	unset($_SESSION['pages_count_estimate']);
}

function pagesCountEstimateFileGetInfo($filePath)
{
	$fileData = false;
	$ftype = mime_content_type($filePath);
	$type = '';
	$pageCount = false;
	if($ftype == 'application/pdf')
	{
		$type='pdf';
		$pdf = new Fpdi();
		$pageCount = $pdf->setSourceFile($filePath);
	}
	else if($ftype == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
	{
		$type='pptx';
		$zip = new \ZipArchive();
		if($zip->open($filePath) === true)
		{
			if(($index = $zip->locateName('docProps/app.xml')) !== false)
			{
				$data = $zip->getFromIndex($index);
				$zip->close();
				$xml = new \SimpleXMLElement($data);
				$xmlPageCount = $xml->Slides;
				$pageCount = (int) $xmlPageCount[0];
			}
			$zip->close();
		}
	}
	else if($ftype == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
	{
		$type='docx';
		$zip = new \ZipArchive();
		if($zip->open($filePath) === true) {
			if(($index = $zip->locateName('docProps/app.xml')) !== false)
			{
				$data = $zip->getFromIndex($index);
				$zip->close();
				$xml = new \SimpleXMLElement($data);
				$xmlPageCount = $xml->Pages;
				$pageCount = (int) $xmlPageCount[0];
			}
			$zip->close();
		}
	}
	else
	{
		$type='excell';
		if($ftype == 'application/vnd.ms-excel')
		{
			$inputFileType = 'Xls';
		}
		else
		{
			$inputFileType = 'Xlsx';
		}
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		$worksheetData = $reader->listWorksheetInfo($filePath);
		$pageCount = count($worksheetData);
	}
	
	if(false !== $pageCount)
	{
		$fileData = ['file_path' => $filePath, 'page_count' => $pageCount, 'type' => $type];
	}
	
	return $fileData;
}

function pagesCountEstimateCalcData($langFrom, $langsTo, $withApostille)
{
	pagesCountEstimateStartSession();
	$adminSettings = pagesCountEstimateAdminGetSettings();
	
	$totalPageCount = 0;
	if(!empty($_SESSION['pages_count_estimate']))
	{
		foreach($_SESSION['pages_count_estimate']['files'] as $file)
		{
			$totalPageCount += $file['page_count'];
		}
	}
	if($totalPageCount < 4)
	{
		$translationPrice = $totalPageCount * ((!empty($adminSettings['pce_1_3_pages_price'])) ? floatval($adminSettings['pce_1_3_pages_price']) : 0);
	}
	else if($totalPageCount < 6)
	{
		$translationPrice = $totalPageCount * ((!empty($adminSettings['pce_4_5_pages_price'])) ? floatval($adminSettings['pce_4_5_pages_price']) : 0);
	}
	else
	{
		$translationPrice = 0;
	}

	$translationPrice = $translationPrice * count($langsTo);
	$authenticationPrice = (!empty($adminSettings['pce_authentication_price'])) ? floatval($adminSettings['pce_authentication_price']) : 0;
	$apostillePrice = ($withApostille && !empty($adminSettings['pce_apostille_price'])) ? floatval($adminSettings['pce_apostille_price']) : 0;
	$shippingPrice = (!empty($adminSettings['pce_shipping_price'])) ? floatval($adminSettings['pce_shipping_price']) : 0;
	$VAT = (!empty($adminSettings['pce_vat'])) ? floatval($adminSettings['pce_vat']) : 0;
	$totalPrice = $translationPrice + $authenticationPrice + $apostillePrice ;
	$estimateData = [
						'page_count' => $totalPageCount,
						'translation_price' => $translationPrice,
						'authentication_price' => $authenticationPrice,
						'apostille_price' => $apostillePrice,
						'shipping_price' => $shippingPrice,
						'total_price' => $totalPrice,
						'vat' => $VAT,
						'vat_price' => round($VAT / 100 * $totalPrice),
						'amount_including_vat' => round($VAT / 100 * $totalPrice + $totalPrice)
					];
	//
	return $estimateData;
}

function pagesCountEstimateGenerateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for($i = 0; $i < $length; $i++)
	{
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function pagesCountEstimateStartSession()
{
	if(!session_id())
	{
		session_start();
	}
}

add_action('init', 'pagesCountEstimateStartSession', 1);

function pagesCountEstimateCustomPriceToCartItem($cartObject)
{  
	if(!WC()->session->__isset("reload_checkout"))
	{
		foreach($cartObject->cart_contents as $key => $value)
		{
			if(isset($value["custom_price"]))
			{
				//for woocommerce version lower than 3
				//$value['data']->price = $value["custom_price"];
				//for woocommerce version +3
				$value['data']->set_price($value["custom_price"]);
				$value['data']->add_meta_data('pce_prod_type', 'translate');
			}
		}  
	}  
}

add_action('woocommerce_before_calculate_totals', 'pagesCountEstimateCustomPriceToCartItem', 99);

function pagesCountEstimateCustomEmailNotification($order_id)
{
	if(!$order_id)
	{
		return;
	}
	
	if(!$order = wc_get_order($order_id))
	{
		return;
	}
	
	$adminSettings = pagesCountEstimateAdminGetSettings();
	$sendEmail = false;
	foreach( $order->get_items() as $item_id => $order_item )
	{
		$product = $order_item->get_product();
		if($adminSettings['pce_product_id'] == $product->get_id())
		{
			$sendEmail = true;
			break;
		}
	}
	
	if($sendEmail)
	{
		pagesCountEstimateStartSession();
		$filesToSend = [];
		if(!empty($_SESSION['pages_count_estimate']))
		{
			foreach($_SESSION['pages_count_estimate']['files'] as $file)
			{
				$filesToSend[] = $file['file_path'];
			}
		}
		$email = $order->get_billing_email();
		$name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
		$to = $adminSettings['pce_email'];
		$subject = __('Translation product cart submit', 'wp-pages-count-estimate');
		$message = '';
		$message = __('Email', 'wp-pages-count-estimate') . ': ' . $email . "\n<br />";
		$message .= __('Name', 'wp-pages-count-estimate') . ': ' . $name . "\n<br />";
		// $message .= __('Comment', 'wp-pages-count-estimate') . ': ' . $comment . "\n<br />";

		$headers = 	[
						'Content-Type: text/html; charset=UTF-8',
						'Reply-To: ' . $name . ' <' . $email . '>'
					];
		//

		if(wp_mail($to, $subject, $message, $headers, $filesToSend))
		{
			//
		}
		else
		{
			//
		}
	}
}

add_action('woocommerce_thankyou', 'pagesCountEstimateCustomEmailNotification', 10, 1);
