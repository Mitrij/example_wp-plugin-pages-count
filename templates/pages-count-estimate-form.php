<?php
if(!defined('WPINC'))
{
	die;
}

$pceCurrency = 'CHF';
$pcePageCount = 0;
$pceTranslationPrice = 0;
$pceAuthentication = 0;
$pceApostille = 0;
$pceShipping = 0;
$pceTotal = 0;
$pcePlusVAT = 0;
$pceAmountIncludingVAT = 0;
?>
<div class="pce-form-wrapper">

	<div class="pce-form-header">
		<?php _e('Offer requests', 'wp-pages-count-estimate'); ?>
	</div>
	
	<div class="pce-form-content">
	
		<div class="pce-form-left-col">
			<div class="pce-form-language-from-block">
				<!-- <input class="pce-form-language-from-input" name="language_from"> -->
				<select class="pce-form-language-from-input" name="language_from">
				<?php
				if(!empty($adminSettings['pce_shipping_price_from_languages_list']))
				{
					foreach($adminSettings['pce_shipping_price_from_languages_list'] as $lang)
					{
						echo '<option value="' . $lang . '">' . $lang .'</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="pce-form-language-to-block">
				<!-- <input class="pce-form-language-to-input" name="to"> -->
				<select class="pce-form-language-to-input" name="to" multiple>
				<?php
				if(!empty($adminSettings['pce_shipping_price_from_languages_list']))
				{
					foreach($adminSettings['pce_shipping_price_to_languages_list'] as $lang)
					{
						echo '<option value="' . $lang . '">' . $lang .'</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="pce-form-apostille-block">
				<input type="checkbox" id="pce-form-apostille-checkbox" class="pce-form-apostille-block-checkbox"> <label for="pce-form-apostille-checkbox" class="pce-form-apostille-checkbox-label"><?php _e('with Apostille', 'wp-pages-count-estimate'); ?></label> <span class="pce-form-apostille-block-i">i</span>
			</div>
			<div class="pce-form-files-block">
				<!--
				<form class="pce-form dropzone">
					<input type="file" name="page_count_file" multiple />
				</form>
				-->
				<div class="pce-form-files-block-dropzone">
					<?php _e('Drag documents here', 'wp-pages-count-estimate'); ?>
					<br />
					<?php _e('or', 'wp-pages-count-estimate'); ?>
					<br />
					<button><?php _e('Browse', 'wp-pages-count-estimate'); ?></button>
				</div>
				<div class="pce-form-files-block-dropzone-previews-container dropzone-previews">
				</div>
				<!--
				<svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">      <title>Error</title>      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">        <g stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">          <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"></path>        </g>      </g>    </svg>
				-->
			</div>
		</div>
		
		<div class="pce-form-right-col">
			<div class="pce-form-amount-of-pages-block">
				<span class="pce-number-of-pages-label"><?php _e('Number of pages', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-number-of-pages-amount"><?php echo $pcePageCount; ?></span>
			</div>
			
			<div class="pce-form-translation-price-block">
				<span class="pce-translation-price-label"><?php _e('Translation price', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-translation-price-amount"><?php echo $pceCurrency . ' ' . $pceTranslationPrice; ?></span>
			</div>
			
			<div class="pce-form-authentication-block">
				<span class="pce-authentication-label"><?php _e('Authentication', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-authentication-amount"><?php echo $pceCurrency . ' ' . $pceAuthentication; ?></span>
			</div>
			
			<div class="pce-form-apostille-block">
				<span class="pce-apostille-label"><?php _e('Apostille', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-apostille-amount"><?php echo $pceCurrency . ' ' . $pceApostille; ?></span>
			</div>
			
			<div class="pce-form-shipping-block">
				<span class="pce-shipping-label"><?php _e('Shipping', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-shipping-amount"><?php echo $pceCurrency . ' ' . $pceShipping; ?></span>
			</div>
			
			<div class="pce-form-total-block">
				<span class="pce-total-label"><?php _e('Total', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-total-amount"><?php echo $pceCurrency . ' ' . $pceTotal; ?></span>
			</div>
			
			<div class="pce-form-plus-vat-block">
				<span class="pce-plus-vat-label"><?php _e('Plus VAT', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-plus-vat-amount"><?php echo $pceCurrency . ' ' . $pcePlusVAT; ?></span>
			</div>
			
			<div class="pce-form-amount-including-vat-block">
				<span class="pce-amount-including-vat-label"><?php _e('Amount including VAT', 'wp-pages-count-estimate'); ?></span>
				<span class="pce-amount-including-vat-amount"><?php echo $pceCurrency . ' ' . $pceAmountIncludingVAT; ?></span>
			</div>
			
			<div class="pce-form-order-button-block">
				<button class="pce-form-order-button" type="button"><?php _e('Order', 'wp-pages-count-estimate'); ?></button>
			</div>
		</div>
	
	</div>
	
	<div class="pce-form-result-msg">
	</div>

</div>