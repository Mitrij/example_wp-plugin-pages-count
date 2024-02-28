<?php
if(!defined('WPINC'))
{
	die;
}

?>

<div class="pce-admin-form-wrapper">
	<div class="pce-admin-form-header">
		<h2><?php _e('Page count plugin settings', 'wp-pages-count-estimate'); ?></h2>
	</div>
	<div class="pce-admin-form-content">
		<form>
			<input type="hidden" name="action" value="pce_admin_form_save">

			<div class="pce-admin-form-price-settings-title pce-admin-form-price-title-block">
				<h3><?php _e('Price settings', 'wp-pages-count-estimate'); ?></h3>
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('Plus VAT value', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-vat" name="pce_vat" value="<?php echo (!empty($adminSettings['pce_vat'])) ? $adminSettings['pce_vat'] : ''; ?>">
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('Apostille price', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-apostille" name="pce_apostille_price" value="<?php echo (!empty($adminSettings['pce_apostille_price'])) ? $adminSettings['pce_apostille_price'] : ''; ?>">
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('Authentication price', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-authentication" name="pce_authentication_price" value="<?php echo (!empty($adminSettings['pce_authentication_price'])) ? $adminSettings['pce_authentication_price'] : ''; ?>">
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('Shipping price', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-shipping" name="pce_shipping_price" value="<?php echo (!empty($adminSettings['pce_shipping_price'])) ? $adminSettings['pce_shipping_price'] : ''; ?>">
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('1-3 pages price', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-1-3-pages-price" name="pce_1_3_pages_price" value="<?php echo (!empty($adminSettings['pce_1_3_pages_price'])) ? $adminSettings['pce_1_3_pages_price'] : ''; ?>">
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('4-5 pages price', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-4-5-pages-price" name="pce_4_5_pages_price" value="<?php echo (!empty($adminSettings['pce_4_5_pages_price'])) ? $adminSettings['pce_4_5_pages_price'] : ''; ?>">
			</div>
			
			<hr />
			<div class="pce-admin-form-languages-from-title pce-admin-form-price-title-block">
				<h3><?php _e('"From Languages" list', 'wp-pages-count-estimate'); ?></h3>
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('Add language', 'wp-pages-count-estimate'); ?></label>
				<div class="pce-admin-form-input-with-button-container">
					<input id="pce_admin_form_add_from_language_value" class="pce-admin-form-add-language" value=""><button id="pce_admin_form_add_from_language_btn" type="button"><?php _e('Add', 'wp-pages-count-estimate'); ?></button>
				</div>
			</div>
			<div class="pce-admin-form-item">
				<select class="pce-admin-form-from-languages-list" size="10" id="pce_admin_form_from_languages_list">
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
			<div class="pce-admin-form-item">
				<button id="pce_admin_form_remove_from_language_btn" class="pce-admin-form-btn" type="button"><?php _e('Remove', 'wp-pages-count-estimate'); ?></button>
			</div>
			
			<hr />
			<div class="pce-admin-form-languages-to-title pce-admin-form-price-title-block">
				<h3><?php _e('"To Languages" list', 'wp-pages-count-estimate'); ?></h3>
			</div>
			<div class="pce-admin-form-item">
				<label><?php _e('Add language', 'wp-pages-count-estimate'); ?></label>
				<div class="pce-admin-form-input-with-button-container">
					<input id="pce_admin_form_add_to_language_value" class="pce-admin-form-add-language" value=""><button id="pce_admin_form_add_to_language_btn" type="button"><?php _e('Add', 'wp-pages-count-estimate'); ?></button>
				</div>
			</div>
			<div class="pce-admin-form-item">
				<select class="pce-admin-form-to-languages-list" size="10" id="pce_admin_form_to_languages_list">
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
			<div class="pce-admin-form-item">
				<button id="pce_admin_form_remove_to_language_btn" class="pce-admin-form-btn" type="button"><?php _e('Remove', 'wp-pages-count-estimate'); ?></button>
			</div>
			
			<hr />
			<div class="pce-admin-form-prod-id pce-admin-form-price-title-block">
				<h3><?php _e('Product settings', 'wp-pages-count-estimate'); ?></h3>
			</div>
			
			<div class="pce-admin-form-item">
				<label><?php _e('Product id', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-product-id" name="pce_product_id" value="<?php echo (!empty($adminSettings['pce_product_id'])) ? $adminSettings['pce_product_id'] : ''; ?>">
			</div>
			
			<hr />
			<div class="pce-admin-form-prod-id pce-admin-form-price-title-block">
				<h3><?php _e('Email settings', 'wp-pages-count-estimate'); ?></h3>
			</div>
			
			<div class="pce-admin-form-item">
				<label><?php _e('Email to send data to', 'wp-pages-count-estimate'); ?></label>
				<input class="pce-admin-form-email" name="pce_email" value="<?php echo (!empty($adminSettings['pce_email'])) ? $adminSettings['pce_email'] : ''; ?>">
			</div>
			
			
			<div class="pce-admin-form-item">
				<button id="pce_admin_form_save_btn" class="pce-admin-form-save-btn" type="button"><?php _e('Save settings', 'wp-pages-count-estimate'); ?></button>
			</div>
			
			<div class="pce-admin-form-message">
			</div>
		</form>
	</div>
</div>

