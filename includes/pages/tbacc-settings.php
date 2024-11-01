<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	/*** code section for getting  tab accordions settings starts***/
	if(isset($_GET['tbacc']) && !empty($_GET['tbacc'])):
		$tbacc_key  = sanitize_text_field($_GET['tbacc']);
	endif;
	
	/*** code section for getting  tab accordions settings starts***/
	if(isset($_POST['save']) && check_admin_referer( 'tbacc_settings', 'tbacc_nonce_key' )):
		global $wpdb;
		$settings_arr 	=  array();
		foreach($_POST as $key=>$value):
			if($key=='save')
				continue;

			$settings_arr[$key]		=	sanitize_text_field($value);
		endforeach;
		if(empty($tbacc_key)):
			$setting_key = "tbacc_settings";
		else:
			$setting_key = $tbacc_key."_settings";
		endif;
		$option_tb 				= $wpdb->prefix."options";
		$tbacc_setting 			= $wpdb->get_var("SELECT option_id FROM $option_tb WHERE option_name = '".$setting_key."'");
		if(empty($tbacc_setting)):
			$tbacc_updated = add_option($setting_key,json_encode($settings_arr));
		else:
			$tbacc_updated = update_option($setting_key,json_encode($settings_arr));
		endif;
	endif;
	$r_tbacc_setting 	= tbacc_get_setting($tbacc_key);
	
?>
<!--main container starts-->
<div class="tbacc-main-container">
  	<!--header starts-->
 	 <div class="tbacc-header tbacc-i-header">
   		<!--left header starts-->
    	<div class="tbacc-left-header">
      		<h2 class="tbacc-admin-page-heading">Settings</h2>
    	</div>
    	<!--left header ends-->
    	<!--right header starts-->
    	<div class="tbacc-right-header">
        	<i class="tbacc-sprite tbacc-setting"></i>
    	</div>
    	<!--right header ends-->
    	<div class="tbacc-clear"></div>
  	</div>
  	<!--header ends-->

	<!--content starts-->
	<div class="tbacc-content">
		<?php if(!empty($message)): ?>
	  		<p class="tbacc-message <?php echo $have_error?'tbacc-error':'tbacc-success';?> tbacc-fullwidth"><?php echo esc_html($message);?></p>
	  	<?php endif; ?>
	    <!--content wrapper starts-->
	    <div class="tbacc-content-wrapper">
	    	<!-- tab-accordion-form-wrap starts-->
	    	<div class="tbacc-tab-accordion-form-wrap">
	    		<form class="tbacc-setting-form" name="tbacc_setting_form" method="post" action="">
	    			<!--section for general settings starts -->
	    			<div class="tbacc-section">
	    				<div class="tbacc-setting-title">General Settings</div>
	    				<div class="tbacc-setting-content">
	    					<p class="tbacc-help-text">Enter max window width upto which you want to show accordions.</p>
	    					<div class="tbacc-form-group">
	    						<?php $d_window_width = (empty($r_tbacc_setting['tbacc_window_width'])?"767px":$r_tbacc_setting['tbacc_window_width']); ?>
	    						<label for="window-width">Window Width: </label>
	    						<input type="text" name="tbacc_window_width" id="window-width" class="tbacc-form-control" value="<?php echo esc_html($d_window_width);?>">
	    					</div>
	    					<p class="tbacc-help-text"><strong>Note:</strong> By default accordion will be display upto window width "767px".</p>   					
	    				</div>
	    			</div>
	    			<!-- section for general settings ends -->
	    			
	    			<!-- section for display settings starts -->
	    			<div class="tbacc-section">
	    				<div class="tbacc-setting-title">Display Settings</div>
	    				<div class="tbacc-setting-content" id="tab-settings">
	    					<div class="tbacc-setting-sub-title">Tabs</div>
	    					<div class="tbacc-setting-sub-content">
		    					<div class="tbacc-form-group tbacc-range-group">
		    						<?php $d_tab_title_font_size = (empty($r_tbacc_setting['tab_title_font_size'])?"10":$r_tbacc_setting['tab_title_font_size']); ?>
		    						<label for="tab-title-font-size">Tab Title Font Size: </label>
		    						<input type="range" min="1" max="100" step="1" value="<?php echo esc_html($d_tab_title_font_size);?>" class="tbacc-form-control" name="tab_title_font_size" id="tab-title-font-size">
		    						<span class="tbacc-range-value"><?php echo esc_html($d_tab_title_font_size);?>px</span>
		    						<div class="tbacc-clear"></div>
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_tab_title_color = (empty($r_tbacc_setting['tab_title_color'])?"000000":$r_tbacc_setting['tab_title_color']); ?>
		    						<label for="tab-title-color">Tab Title Color: </label>
		    						<input type="text" name="tab_title_color" id="tab-title-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_tab_title_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_tab_background_color = (empty($r_tbacc_setting['tab_background_color'])?"ab2567":$r_tbacc_setting['tab_background_color']); ?>
		    						<label for="tab-background-color">Tab Background Color: </label>
		    						<input type="text" name="tab_background_color" id="tab-background-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_tab_background_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_tab_active_title_color = (empty($r_tbacc_setting['tab_active_title_color'])?"000000":$r_tbacc_setting['tab_active_title_color']); ?>
		    						<label for="tab-active-title-color">Tab Active Title Color: </label>
		    						<input type="text" name="tab_active_title_color" id="tab-active-title-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_tab_active_title_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_tab_active_background_color = (empty($r_tbacc_setting['tab_active_background_color'])?"ab2567":$r_tbacc_setting['tab_active_background_color']); ?>
		    						<label for="tab-active-background-color">Tab Active Background Color: </label>
		    						<input type="text" name="tab_active_background_color" id="tab-active-background-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_tab_active_background_color);?>">
		    					</div>

		    					<div class="tbacc-form-group tbacc-range-group">
		    						<?php $d_tab_content_font_size = (empty($r_tbacc_setting['tab_content_font_size'])?"10":$r_tbacc_setting['tab_content_font_size']); ?>
		    						<label for="tab-content-font-size">Tab Content Font Size: </label>
		    						<input type="range" min="1" max="100" step="1" value="<?php echo esc_html($d_tab_content_font_size);?>" class="tbacc-form-control" name="tab_content_font_size" id="tab-content-font-size">
		    						<span class="tbacc-range-value"><?php echo esc_html($d_tab_content_font_size);?>px</span>
		    						<div class="tbacc-clear"></div>
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_tab_content_color = (empty($r_tbacc_setting['tab_content_color'])?"000000":$r_tbacc_setting['tab_content_color']); ?>
		    						<label for="tab-content-color">Tab Content Color</label>
		    						<input type="text" name="tab_content_color" id="tab-content-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_tab_content_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_tab_content_background_color = (empty($r_tbacc_setting['tab_content_background_color'])?"ab2567":$r_tbacc_setting['tab_content_background_color']); ?>
		    						<label for="tab-content-background-color">Tab Content Background Color</label>
		    						<input type="text" name="tab_content_background_color" id="tab-content-background-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_tab_content_background_color);?>">
		    					</div>
		    				</div>
		    			</div>

		    			<div class="tbacc-form-group tbacc-checkbox-group">
		    				<label for="same-accordion-settings">
		    					<input type="checkbox" name="same_accordion_settings" id="same-accordion-settings" value="1" class="tbacc-form-control" <?php echo isset($r_tbacc_setting['same_accordion_settings'])?"checked":"";?>>
		    					<span>Select if you want same settings for accordion as tabs.</span>
		    				</label>	
		    			</div>

		    			<div class="tbacc-setting-content" id="accordions-setting" style="display: <?php echo isset($r_tbacc_setting['same_accordion_settings'])?"none":"block";?>">
	    					<div class="tbacc-setting-sub-title">Accordions</div>
	    					<div class="tbacc-setting-sub-content">
		    					<div class="tbacc-form-group tbacc-range-group">
		    						<?php $d_accordion_title_font_size = (empty($r_tbacc_setting['accordion_title_font_size'])?"10":$r_tbacc_setting['accordion_title_font_size']); ?>
		    						<label for="accordion-title-font-size">Accordion Title Font Size</label>
		    						<input type="range" min="1" max="100" step="1" value="<?php echo esc_html($d_accordion_title_font_size);?>" class="tbacc-form-control" name="accordion_title_font_size" id="accordion-title-font-size">
		    						<span class="tbacc-range-value"><?php echo esc_html($d_accordion_title_font_size);?>px</span>
		    						<div class="tbacc-clear"></div>
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_accordion_title_color = (empty($r_tbacc_setting['accordion_title_color'])?"000000":$r_tbacc_setting['accordion_title_color']); ?>
		    						<label for="accordion-title-color">Accordion Title Color</label>
		    						<input type="text" name="accordion_title_color" id="accordion-title-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_accordion_title_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_accordion_background_color = (empty($r_tbacc_setting['accordion_background_color'])?"ab2567":$r_tbacc_setting['accordion_background_color']); ?>
		    						<label for="accordion-background-color">Accordion Background Color</label>
		    						<input type="text" name="accordion_background_color" id="accordion-background-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_accordion_background_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_accordion_active_title_color = (empty($r_tbacc_setting['accordion_active_title_color'])?"000000":$r_tbacc_setting['accordion_active_title_color']); ?>
		    						<label for="accordion-title-color">Accordion Title Color</label>
		    						<input type="text" name="accordion_active_title_color" id="accordion-active-title-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_accordion_active_title_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_accordion_active_background_color = (empty($r_tbacc_setting['accordion_active_background_color'])?"ab2567":$r_tbacc_setting['accordion_active_background_color']); ?>
		    						<label for="accordion-active-background-color">Accordion Active Background Color</label>
		    						<input type="text" name="accordion_active_background_color" id="accordion-active-background-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_accordion_active_background_color);?>">
		    					</div>

		    					<div class="tbacc-form-group tbacc-range-group">
		    						<?php $d_accordion_content_font_size = (empty($r_tbacc_setting['accordion_content_font_size'])?"10":$r_tbacc_setting['accordion_content_font_size']); ?>
		    						<label for="accordion-content-font-size">Accordion Content Font Size</label>
		    						<input type="range" min="1" max="100" step="1" value="<?php echo esc_html($d_accordion_content_font_size);?>" class="tbacc-form-control" name="accordion_content_font_size" id="accordion-content-font-size">
		    						<span class="tbacc-range-value"><?php echo esc_html($d_accordion_content_font_size);?>px</span>
		    						<div class="tbacc-clear"></div>
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_accordion_content_color = (empty($r_tbacc_setting['accordion_content_color'])?"000000":$r_tbacc_setting['accordion_content_color']); ?>
		    						<label for="accordion-content-color">Accordion Content Color</label>
		    						<input type="text" name="accordion_content_color" id="accordion-content-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_accordion_content_color);?>">
		    					</div>

		    					<div class="tbacc-form-group">
		    						<?php $d_accordion_content_background_color = (empty($r_tbacc_setting['accordion_content_background_color'])?"ab2567":$r_tbacc_setting['accordion_content_background_color']); ?>
		    						<label for="accordion-content-background-color">Accordion Content Background Color</label>
		    						<input type="text" name="accordion_content_background_color" id="accordion-content-background-color" class="tbacc-form-control jscolor" value="<?php echo esc_html($d_accordion_content_background_color);?>">
		    					</div>
		    				</div>
		    			</div>

		    			<div class="tbacc-setting-content">
		    				<div class="tbacc-setting-sub-title">Custom CSS</div>
	    					<div class="tbacc-setting-sub-content">
	    						<div class="tbacc-form-group">
	    							<textarea name="tbacc_custom_css" class="tbacc-form-control tbacc-custom-field" ><?php echo esc_textarea(stripslashes($r_tbacc_setting['tbacc_custom_css']));?></textarea>
	    						</div>
	    					</div>
		    			</div>

		    			<div class="tbacc-setting-content">
		    				<div class="tbacc-setting-sub-title">Custom JS</div>
	    					<div class="tbacc-setting-sub-content">
	    						<div class="tbacc-form-group">
	    							<textarea name="tbacc_custom_js" class="tbacc-form-control tbacc-custom-field"><?php echo esc_textarea(stripslashes($r_tbacc_setting['tbacc_custom_js']));?></textarea>
	    						</div>
	    					</div>
		    			</div>

		    			<!-- save tab accordion settings starts -->
			    		<div class="save-tab-accordion-settings">
			    			<button class="tbacc-button tbacc-save-button" type="submit" title="Save" name="save"><i class="tbacc-sprite tbacc-save-small"></i><span>Save</span></button>
			    		</div>
	    				<!-- save tab accordion settings ends -->

	    			</div>
	    			<!-- section for display settings ends -->
	    			<?php wp_nonce_field( 'tbacc_settings', 'tbacc_nonce_key' ); ?>
	    		</form>
	    	</div>
	    	<!-- tab-accordion-form-wrap ends-->

	    </div>
	    <!--content wrapper ends-->
	</div>
	<!--content ends-->
</div>
<!--main container ends-->
