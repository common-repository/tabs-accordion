<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	/*** code section for getting  tab accordions details starts***/
	if(isset($_GET['tbacc']) && !empty($_GET['tbacc'])):
		$tbacc_key  				= sanitize_text_field($_GET['tbacc']);
		$r_tab_id 					= tbacc_get_tabs_details($tbacc_key,'ID');
		$r_tab_title 				= tbacc_get_tabs_details($tbacc_key,'title');
		$r_enable_accordion_title 	= tbacc_get_tabs_details($tbacc_key,'enable_accordion_title');
		$r_accordion_title 			= tbacc_get_tabs_details($tbacc_key,'accordion_title');
		$r_tab_accordion_content 	= tbacc_get_tabs_details($tbacc_key,'tab_accordion_content');
	endif;
	/*** code section for getting  tab accordions details starts***/

	/**** code section for form submit starts***/
	if(isset($_POST['save']) && check_admin_referer( 'tbacc_add_edit', 'tbacc_nonce_key' )):
		$tab_id 					= filter_var_array($_POST['tab_id'],FILTER_SANITIZE_NUMBER_INT);
		$tab_title 					= filter_var_array($_POST['tab_title'],FILTER_SANITIZE_STRING);
		$enable_accordion_title		= filter_var_array($_POST['enable_accordion_title'],FILTER_SANITIZE_STRING);
		$accordion_title			= filter_var_array($_POST['accordion_title'],FILTER_SANITIZE_STRING);
		$tab_accordion_content		= filter_var_array($_POST['tab_accordion_content'],FILTER_SANITIZE_STRING);
		$tbacc_error				= false;
		$tbacc_error_arr 			= array();
		$tbacc_error_message_arr 	= array();
		$tbacc_tab_error			= array();
		$tbacc_tab_error_message	= array();
		/** section for checking required validation of title starts***/
		foreach($tab_title as $key=>$value):
			if(empty($value)):
				$tbacc_error_arr['tab_title['.$key.']'] = true;
				$tbacc_error_message_arr['tab_title['.$key.']'] = "Title is required.";
				$tbacc_error = true;
			endif;
		endforeach;
		/** section for checking required validation of title ends***/
			
		/** section for checking required validation of accordion title starts***/
		foreach($accordion_title as $key=>$value):

			if($enable_accordion_title[$key] && empty($value)):
				$tbacc_error_arr['accordion_title['.$key.']'] = true;
				$tbacc_error_message_arr['accordion_title['.$key.']'] = "Accordion Title is required.";
				$tbacc_error = true;
			endif;
		endforeach;
		/** section for checking required validation of accordion title ends***/

		/** section for checking required validation of tab accordion content starts***/
		foreach($tab_accordion_content as $key=>$value):
			if(empty($value)):
				$tbacc_error_arr['tab_accordion_content['.$key.']'] = true;
				$tbacc_error_message_arr['tab_accordion_content['.$key.']'] = "Content is required.";
				$tbacc_error = true;
			endif;
		endforeach;
		/** section for checking required validation of tab accordion content ends***/

		/** section of this is not required error  starts***/
		if(!$tbacc_error):
			$post_type		= "tbacc";
			$n_tbacc_key 		= "tbacc_".substr(md5(uniqid(time(), true)),0,6);

			if(sizeof($r_tab_id)>0):
				$tbacc_arr_diff = array_diff($r_tab_id,$tab_id); 
				if(sizeof($tbacc_arr_diff)>0):
					foreach($tbacc_arr_diff as $diff):
						wp_delete_post($diff,true);
					endforeach;
				endif;
			endif;

			foreach($tab_title as $key=>$value):
				if(empty($tbacc_key)):
					$post_data		= array(
										'post_title'		=>	$value,
										'post_content'		=>	$tab_accordion_content[$key],
										'post_type'			=>	$post_type,
										'post_status'		=>	'publish'		
									);
					$post_id		=	wp_insert_post($post_data);

				else:
					$n_tbacc_key	=	$tbacc_key;
					$post_data		= array(
										'ID'				=>	$r_tab_id[$key],
										'post_title'		=>	$value,
										'post_content'		=>	$tab_accordion_content[$key],
									);
					$post_id		=	wp_update_post($post_data);
				endif;
				if($post_id):
					update_post_meta($post_id,'tbacc_key',$n_tbacc_key);
					$ins_accordion_title = ($enable_accordion_title[$key]?$accordion_title[$key]:"");
					update_post_meta($post_id,'enable_accordion_title',$enable_accordion_title[$key]);
					update_post_meta($post_id,'accordion_title',$ins_accordion_title);
					
				else:
					$tbacc_tab_error[$key]  		=  true;
					$tbacc_tab_error_message[$key]  =  "Error on ".(empty($tbacc_key)?"inserting":"updating")." this tab details.";
					$tbacc_error 					= true;
				endif;
			endforeach;

			if(!$tbacc_error):
				wp_redirect('?page=add-new-tabs-accordion&tbacc='.$n_tbacc_key);
				exit;
			endif;
		endif;
	/** section of this is not required error  ends***/
	endif;

	/**** code section for form submit ends***/
?>

<!--main container starts-->
<div class="tbacc-main-container">
  <!--header starts-->
  <div class="tbacc-header tbacc-i-header">
    <!--left header starts-->
    <div class="tbacc-left-header">
      <h2 class="tbacc-admin-page-heading"><?php echo (empty($tbacc_key)?"Add New":"Edit"); ?></h2>
    </div>
    <!--left header ends-->
    <!--right header starts-->
    <div class="tbacc-right-header">
        <i class="tbacc-sprite <?php echo (empty($tbacc_key)?"tbacc-add-new":"tbacc-edit"); ?>"></i>
    </div>
    <!--right header ends-->
    <div class="tbacc-clear"></div>
  </div>
  <!--header ends-->

  <!--content starts-->
  <div class="tbacc-content ">
  	<?php if(!empty($tbacc_key)): ?>
  		<p class="tbacc-message tbacc-info tbacc-fullwidth">Use this shortcode <code>[tbacc type="<?php echo esc_html($tbacc_key);?>"]</code> to add this tab accordion content to the webpage.</p>
  	<?php endif; ?>
   
    <!--content wrapper starts-->
    <div class="tbacc-content-wrapper">
	    <!-- tab-accordion-form-wrap starts-->
	    <div class="tbacc-tab-accordion-form-wrap">
	    	<form class="tbacc-form1" name="tbacc_form" method="post" action="">

	    		<!-- tab-accordion-content-wrap starts -->
	    		<div class="tbacc-tab-accordion-content-wrap">

	    			<?php if($tbacc_error || !empty($tbacc_key)): ?>
	    				<?php $tbacc_tabs 		= ($tbacc_error?$tab_title:$r_tab_title);	?>
	    				<?php foreach($tbacc_tabs as $key=>$value): ?>
	    					<?php 
	    						$d_tab_title 					= $value;
	    						$d_tab_id 						= ($tbacc_error?$tab_id[$key]:$r_tab_id[$key]);	
	    						$d_enable_accordion_title 		= ($tbacc_error?$enable_accordion_title[$key]:$r_enable_accordion_title[$key]);	
	    						$d_accordion_title 				= ($tbacc_error?$accordion_title[$key]:$r_accordion_title[$key]);
	    						$d_tab_accordion_content 		= ($tbacc_error?$tab_accordion_content[$key]:$r_tab_accordion_content[$key]);
	    					?>
	    					<!-- tab-accordion-content starts -->
			    			<div class="tbacc-tab-accordion-content">

			    				<!-- tbacc-content starts -->
			    				<div class="tbacc-inner-content">
			    					<!-- tbacc-left-content starts -->
				    				<div class="tbacc-left-content">
				    					<span class="tbacc-count"><?php echo esc_html($key+1);?></span>
				    				</div>
				    				<!-- tbacc-left-content ends -->
				    				
				    				<!-- tbacc-right-content starts -->
				    				<div class="tbacc-right-content">
				    					<div class="tbacc-remove-button-wrap" style="display:	<?php echo (sizeof($tbacc_tabs)>1?'block':'none');?>">
				    						<a href="javascript:void(0)" class="tbacc-remove-button" title="Remove">
				    							<i class="tbacc-sprite tbacc-remove"></i>
				    						</a>
				    					</div>
				    				</div>
				    				<!-- tbacc-right-content ends -->
				    				<div class="tbacc-clear"></div>

				    				<!-- tbacc-form controls wrap starts -->
				    				<div class="tbacc-form-controls-wrap">

				    					<input type="hidden" name="tab_id[<?php echo $key;?>]" value="<?php echo $d_tab_id;?>">
				    					<!-- form group for tab-title starts -->
							    		<div class="tbacc-form-group">
							    			<label for="tab-title[<?php echo $key;?>]">Title <em>*</em> :</label>
							    			<input type="text" name="tab_title[<?php echo $key;?>]" id="tab-title[<?php echo $key;?>]" data-required="true" class="tbacc-form-control <?php echo ($tbacc_error_arr['tab_title['.$key.']']?'tbacc-error':'');?>" data-required-text="Title" value="<?php echo esc_html($d_tab_title);?>">

							    			<?php if($tbacc_error_arr['tab_title['.$key.']']): ?>
							    				<p class="tbacc-message tbacc-error"><?php echo esc_html($tbacc_error_message_arr['tab_title['.$key.']']); ?></p>
							    			<?php endif; ?>

							    		</div> 
							    		<!-- form group for tab-title ends -->

							    		<!-- form group for enabling accordion title starts -->
							    		<div class="tbacc-form-group tbacc-checkbox-group">
							    			<label for="enable-accordion-title[<?php echo $key;?>]">
							    				<input type="checkbox" name="enable_accordion_title[<?php echo $key;?>]" id="enable-accordion-title[<?php echo $key;?>]" class="tbacc-form-control enable-accordion-title" value="1" <?php echo ($d_enable_accordion_title==1?"checked":""); ?> >
							    				<span>Check to add different title for accordion.</span>
							    			</label>
							    		</div>
							    		<!-- form group for enabling accordion title ends -->

							    		<!-- form group for tab-title starts -->
							    		<div class="tbacc-form-group tbacc-dependent-field enable_accordion_title[]" style="display:<?php echo ($d_enable_accordion_title==1?'block':'none'); ?>;">
							    			<label for="accordion-title[<?php echo $key;?>]">Accordion Title <em>*</em> :</label>
							    			<input type="text" name="accordion_title[<?php echo $key;?>]" id="accordion-title[<?php echo $key;?>]" data-required="false" class="tbacc-form-control" data-required-text="Accordion Title" value="<?php echo esc_html($d_accordion_title);?>" <?php echo ($tbacc_error_arr['accordion_title['.$key.']']?'tbacc-error':'');?>>

							    			<?php if($tbacc_error_arr['accordion_title['.$key.']']): ?>
							    				<p class="tbacc-message tbacc-error"><?php echo esc_html($tbacc_error_message_arr['accordion_title['.$key.']']); ?></p>
							    			<?php endif; ?>
esc_html(
							    		</div> 
							    		<!-- form group for tab-title ends -->

							    		<!-- form group for tab-accordion-content starts -->
							    		<div class="tbacc-form-group <?php echo ($tbacc_error_arr['tab_accordion_content['.$key.']']?'tbacc-error':'');?>" data-required-text="Content">
							    			<label for="tab_accordion_content[<?php echo $key;?>]">Content <em>*</em> :</label>
							    			<?php
												$settings = array( 'media_buttons' => true );
												$content   = $d_tab_accordion_content;
												$editor_id = 'tab_accordion_content['.$key.']';
												wp_editor( $content, $editor_id, $settings);
											?>

											<?php if($tbacc_error_arr['tab_accordion_content['.$key.']']): ?>
							    				<p class="tbacc-message tbacc-error"><?php echo esc_html($tbacc_error_message_arr['tab_accordion_content['.$key.']']); ?></p>
							    			<?php endif; ?>
							    		</div> 
							    		<!-- form group for tab-accordion-content ends -->
				    				</div>
				    				<!-- tbacc-form controls wrap ends -->

			    					
			    				</div>
			    				<!-- tbacc-content ends -->
			    				
			    				<?php if($tbacc_tab_error[$key]): ?>
				    				<p class="tbacc-message tbacc-error"><?php echo esc_html($tbacc_tab_error_message[$key]); ?></p>
				    			<?php endif; ?>
			    				
			    			</div>
			    			<!-- tab-accordion-content ends -->
	    				<?php endforeach; ?>
	    			<?php else: ?>
	    				<!-- tab-accordion-content starts -->
		    			<div class="tbacc-tab-accordion-content">

		    				<!-- tbacc-content starts -->
		    				<div class="tbacc-inner-content">
		    					<!-- tbacc-left-content starts -->
			    				<div class="tbacc-left-content">
			    					<span class="tbacc-count">1</span>
			    				</div>
			    				<!-- tbacc-left-content ends -->
			    				
			    				<!-- tbacc-right-content starts -->
			    				<div class="tbacc-right-content">
			    					<div class="tbacc-remove-button-wrap">
			    						<a href="javascript:void(0)" class="tbacc-remove-button" title="Remove">
			    							<i class="tbacc-sprite tbacc-remove"></i>
			    						</a>
			    					</div>
			    				</div>
			    				<!-- tbacc-right-content ends -->
			    				<div class="tbacc-clear"></div>

			    				<!-- tbacc-form controls wrap starts -->
			    				<div class="tbacc-form-controls-wrap">
			    					<!-- form group for tab-title starts -->
						    		<div class="tbacc-form-group">
						    			<label for="tab-title[0]">Title <em>*</em> :</label>
						    			<input type="text" name="tab_title[0]" id="tab-title[0]" data-required="true" class="tbacc-form-control" data-required-text="Title">
						    		</div> 
						    		<!-- form group for tab-title ends -->

						    		<!-- form group for enabling accordion title starts -->
						    		<div class="tbacc-form-group tbacc-checkbox-group">
						    			<label for="enable-accordion-title[0]">
						    				<input type="checkbox" name="enable_accordion_title[0]" id="enable-accordion-title[0]" class="tbacc-form-control enable-accordion-title" value="1">
						    				<span>Check to add different title for accordion.</span>
						    			</label>
						    		</div>
						    		<!-- form group for enabling accordion title ends -->

						    		<!-- form group for tab-title starts -->
						    		<div class="tbacc-form-group tbacc-dependent-field enable_accordion_title[]">
						    			<label for="accordion-title[0]">Accordion Title <em>*</em> :</label>
						    			<input type="text" name="accordion_title[0]" id="accordion-title[0]" data-required="false" class="tbacc-form-control" data-required-text="Accordion Title">
						    		</div> 
						    		<!-- form group for tab-title ends -->

						    		<!-- form group for tab-accordion-content starts -->
						    		<div class="tbacc-form-group" data-required-text="Content">
						    			<label for="tab_accordion_content[0]">Content <em>*</em> :</label>
						    			<?php
											$settings = array( 'media_buttons' => true );
											$content   = '';
											$editor_id = 'tab_accordion_content[0]';
											wp_editor( $content, $editor_id, $settings);
										?>
						    		</div> 
						    		<!-- form group for tab-accordion-content ends -->
			    				</div>
			    				<!-- tbacc-form controls wrap ends -->

		    					
		    				</div>
		    				<!-- tbacc-content ends -->
		    	
		    				
		    			</div>
		    			<!-- tab-accordion-content ends -->
	    			<?php endif; ?>
	    					
	    		</div>
	    		<!-- tab-accordion-content-wrap ends -->

	    		<!-- save tab accordion starts -->
	    		<div class="save-tab-accordion">
	    			<button class="tbacc-button tbacc-save-button" type="submit" title="Save" name="save"><i class="tbacc-sprite tbacc-save-small"></i><span>Save</span></button>
	    		</div>
	    		<!-- save tab accordion ends -->


	    		<!-- add more tab accordion starts -->
	    		<div class="add-more-tab-accordion">
	    			<button class="tbacc-button tbacc-add-more-button" type="button" title="Add more"><i class="tbacc-sprite tbacc-add-more"></i><span>Add More</span></button>
	    		</div>
	    		<!-- add more tab accordion ends -->
    			<div class="tbacc-clear"></div>

    			<?php wp_nonce_field( 'tbacc_add_edit', 'tbacc_nonce_key' ); ?>
	    	</form>
	    </div>
	    <!-- tab-accordion-form-wrap ends-->
 
    </div>
    <!--content wrapper ends-->
  </div>
  <!--content ends-->
</div>
<!--main container ends-->
