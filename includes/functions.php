<?php
/********************
** Tabs Accordion Plugin Functions
********************/
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
/**
* function to get tabs details
* @tbacc_key is for the tbacc_key it will be unique key for each tabs and accordions
* @item_key is the name of attribte of tbacc type post
**/
function tbacc_get_tabs_details($tbacc_key,$item_key){
	global $wpdb;
	$args	= array(
		'post_type'			=>	'tbacc',
		'post_status'		=>	'publish',
		'posts_per_page'	=>	-1,
		'meta_key'			=>	'tbacc_key',
		'meta_value'		=>	$tbacc_key,
		'order'				=>	'ASC'
	);
	$result_arr = array();

	query_posts($args);
	if(have_posts()):
		while(have_posts()): the_post();
			switch ($item_key):
				case 'ID':
					$item = get_the_ID();
					break;

				case 'title':
					$item = get_the_title();
					break;

				case 'enable_accordion_title':
					$item = get_post_meta(get_the_ID(),'enable_accordion_title',true);
					break;

				case 'accordion_title':
					$item = get_post_meta(get_the_ID(),'accordion_title',true);
					break;	

				case 'tab_accordion_content':
					$item = get_the_content();
					break;
				
			endswitch;
			array_push($result_arr,$item);
		endwhile; wp_reset_query();
	endif;

	return $result_arr;
}

/**
* function to get all tbacc tab and accordions
* @paged is for the current page of pagination
* @items_per_page is number of items to be shown per page 
**/
function tbacc_get_all_tab_accordions($paged,$items_per_page){
	global $wpdb;
	$pm_tb	= 	$wpdb->prefix."postmeta";
	$offset	= ($paged-1)*$items_per_page;
	$tbacc_query = "SELECT meta_value FROM $pm_tb WHERE meta_key='tbacc_key' GROUP BY meta_value LIMIT $offset,$items_per_page";
	$tbacc_arr = $wpdb->get_results($tbacc_query);
	return $tbacc_arr;
}
/**
* function to get count of tbacc tab and accordions
**/
function tbacc_count_tab_accordions(){
	global $wpdb;
	$pm_tb	= 	$wpdb->prefix."postmeta";
	$offset	= ($paged-1)*$items_per_page;
	$tbacc_query = "SELECT meta_value FROM $pm_tb WHERE meta_key='tbacc_key' GROUP BY meta_value";
	$tbacc_arr = $wpdb->get_results($tbacc_query);
	return sizeof($tbacc_arr);
}

/**
* funtion to get tabs and accordions settings
* @tbacc_key is for the tbacc_key it will be unique key for each tabs and accordions
**/
function tbacc_get_setting($tbacc_key=''){
	if(empty($tbacc_key)):
		$tbacc_settings = get_option('tbacc_settings');
	else:
		$tbacc_settings = get_option($tbacc_key.'_settings');
		if(sizeof($tbacc_settings)==0):
			$tbacc_settings = get_option('tbacc_settings');
		endif;
	endif;
	return json_decode($tbacc_settings,true);
}

/**
* function to display shortcode content
* @atts is shortcode attributes
**/
function tbacc_shortcode_func($atts){
	$tbacc_key = $atts['key'];
	$html = '';
	if(!empty($tbacc_key)):
		$r_tab_id 							= tbacc_get_tabs_details($tbacc_key,'ID');
		$r_tab_title 						= tbacc_get_tabs_details($tbacc_key,'title');
		$r_enable_accordion_title 			= tbacc_get_tabs_details($tbacc_key,'enable_accordion_title');
		$r_accordion_title 					= tbacc_get_tabs_details($tbacc_key,'accordion_title');
		$r_tab_accordion_content 			= tbacc_get_tabs_details($tbacc_key,'tab_accordion_content');
		$r_tbacc_setting 					= tbacc_get_setting($tbacc_key);
		$r_tab_title_font_size 				= (empty($r_tbacc_setting['tab_title_font_size'])?"10":$r_tbacc_setting['tab_title_font_size']);
		$r_tab_title_color 					= (empty($r_tbacc_setting['tab_title_color'])?"000000":$r_tbacc_setting['tab_title_color']);
		$r_tab_background_color 			= (empty($r_tbacc_setting['tab_background_color'])?"ab2567":$r_tbacc_setting['tab_background_color']);
		$r_tab_active_title_color 			= (empty($r_tbacc_setting['tab_active_title_color'])?"000000":$r_tbacc_setting['tab_active_title_color']); 
		$r_tab_active_background_color 		= (empty($r_tbacc_setting['tab_active_background_color'])?"ab2567":$r_tbacc_setting['tab_active_background_color']);
		$r_tab_content_font_size 			= (empty($r_tbacc_setting['tab_content_font_size'])?"10":$r_tbacc_setting['tab_content_font_size']);
		$r_tab_content_color 				= (empty($r_tbacc_setting['tab_content_color'])?"000000":$r_tbacc_setting['tab_content_color']);
		$r_tab_content_background_color 	= (empty($r_tbacc_setting['tab_content_background_color'])?"ab2567":$r_tbacc_setting['tab_content_background_color']);
		if(!isset($r_tbacc_setting['same_accordion_settings'])):
			$r_accordion_title_font_size 		= (empty($r_tbacc_setting['accordion_title_font_size'])?$r_tab_title_font_size:$r_tbacc_setting['accordion_title_font_size']);
			$r_accordion_title_color 			= (empty($r_tbacc_setting['accordion_title_color'])?$r_tab_title_color:$r_tbacc_setting['accordion_title_color']);
			$r_accordion_background_color 		= (empty($r_tbacc_setting['accordion_background_color'])?$r_tab_background_color:$r_tbacc_setting['accordion_background_color']);
			$r_accordion_active_title_color 	= (empty($r_tbacc_setting['accordion_active_title_color'])?$r_tab_active_title_color:$r_tbacc_setting['accordion_active_title_color']);
			$r_accordion_active_background_color= (empty($r_tbacc_setting['accordion_active_background_color'])?$r_tab_active_background_color:$r_tbacc_setting['accordion_active_background_color']);
			$r_accordion_content_font_size 		= (empty($r_tbacc_setting['accordion_content_font_size'])?$r_tab_content_font_size:$r_tbacc_setting['accordion_content_font_size']);
			$r_accordion_content_color 			= (empty($r_tbacc_setting['accordion_content_color'])?$r_tab_content_color:$r_tbacc_setting['accordion_content_color']); 
			$r_accordion_content_background_color = (empty($r_tbacc_setting['accordion_content_background_color'])?$r_tab_content_background_color:$r_tbacc_setting['accordion_content_background_color']);
		else:
			$r_accordion_title_font_size 			=	$r_tab_title_font_size;
			$r_accordion_title_color 				=	$r_tab_title_color;
			$r_accordion_background_color 			=	$r_tab_background_color;
			$r_accordion_active_title_color 		=	$r_tab_active_title_color;
			$r_accordion_active_background_color	=	$r_tab_active_background_color;
			$r_accordion_content_font_size 			=	$r_tab_content_font_size;
			$r_accordion_content_color 				=	$r_tab_content_color;
			$r_accordion_content_background_color 	=	$r_tab_content_background_color;
		endif;
		$r_window_width = (empty($r_tbacc_setting['tbacc_window_width'])?"767px":$r_tbacc_setting['tbacc_window_width']);
		if(sizeof($r_tab_title)>0):
			$html .= '<style>
				.tbacc-tab-titles li.tbacc-title{background-color:#'.$r_tab_background_color.'}
				.tbacc-tab-titles li.tbacc-title a{font-size:'.$r_tab_title_font_size.'px;color:#'.$r_tab_title_color.';}
				.tbacc-tab-titles li.tbacc-title.tbacc-active,.tbacc-tab-titles li.tbacc-title:active,.tbacc-tab-titles li.tbacc-title:hover{background-color:#'.$r_tab_active_background_color.';}
				.tbacc-tab-titles li.tbacc-title.tbacc-active a,.tbacc-tab-titles li.tbacc-title:active a,.tbacc-tab-titles li.tbacc-title:hover a{color:#'.$r_tab_active_title_color.';}
				.tbacc-tab-accordion-content{font-size:'.$r_tab_content_font_size.'px;color:#'.$r_tab_content_color.';background-color:#'.$r_tab_content_background_color.';}

				.tbacc-accordion-title{background-color:#'.$r_accordion_background_color.'}
				.tbacc-accordion-title a{font-size:'.$r_accordion_title_font_size.'px;color:#'.$r_accordion_title_color.';}
				.tbacc-active .tbacc-accordion-title,.tbacc-accordion-title:active,.tbacc-accordion-title:hover{background-color:#'.$r_accordion_active_background_color.';}
				.tbacc-active .tbacc-accordion-title a,.tbacc-accordion-title:active a,.tbacc-accordion-title:hover a{color:#'.$r_accordion_active_title_color.';}
				.tbacc-accordions .tbacc-tab-accordion-content{font-size:'.$r_accordion_content_font_size.'px;color:#'.$r_accordion_content_color.';background-color:#'.$r_accordion_content_background_color.';}
				'.stripslashes($r_tbacc_setting['tbacc_custom_css']).'
			</style>';
			$html .= '<!--tbacc-container-wrap starts-->
					<div class="tbacc-container-wrap" style="display:none;">
						<!--tbacc-content starts-->
						<div class="tbacc-content">
							<!-tbacc-tab-titles-navigations starts-->
							<div class="tbacc-tab-titles-navigations">
								<ul class="tbacc-tab-titles">';
									foreach($r_tab_title as $key=>$value):
										$html .= '<li class="tbacc-title '.($key==0?"tbacc-active":"").'">
												<a href="#tbacc-tab-'.$key.'" class="tbacc-tab-link">'.$value.'</a>
										</li>';
									endforeach;
								$html.='</ul>
							</div>
							<!-tbacc-tab-titles-navigations ends-->
							<!--tbacc-tab-content-wrap starts-->
							<div class="tbacc-tab-content-wrap">
								<!--tbacc-tab-contents starts-->
								<ul class="tbacc-tab-contents">';
									foreach($r_tab_title as $key=>$value):
										$html .= '<li class="tbacc-tab-content" id="tbacc-tab-'.$key.'">';
												if($r_enable_accordion_title[$key]):
													$html .= '<div class="tbacc-accordion-title"><a href="#tbacc-tab-'.$key.'" class="tbacc-accordion-link">'.$r_accordion_title[$key].'</a></div>';
												else:
													$html .= '<div class="tbacc-accordion-title"><a href="#tbacc-tab-'.$key.'" class="tbacc-accordion-link">'.$r_tab_title[$key].'</a></div>';
												endif;

												$html .= '<div class="tbacc-tab-accordion-content">
												'.$r_tab_accordion_content[$key].'
												</div>
										</li>';
									endforeach;
								$html.='</ul>
								<!--tbacc-tab-contents ends-->
							</div>
							<!--tbacc-tab-content-wrap ends-->
						</div>
						<!--tbacc-content ends-->
					</div>';
			$html .= '<!--tbacc-container-wrap ends-->';
			$html .= '<script type="text/javascript"> var tbaccWidth="'.$r_window_width.'";'.stripslashes($r_tbacc_setting['tbacc_custom_js']).'</script>';

		endif;
	endif;
	return $html;
}