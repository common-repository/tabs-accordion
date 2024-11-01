<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$paged 					= isset($_GET['paged'])?intval($_GET['paged']):1;
	$items_per_page			= isset($_GET['items_per_page'])?intval($_GET['items_per_page']):10;
	global $wpdb;
	$post_tb				= $wpdb->prefix."posts";
	$pm_tb					= $wpdb->prefix."postmeta";

	/** code section for tab accordion starts **/
	if(isset($_GET['action']) && $_GET['action']=='delete'):
		$tbacc_key 			=	sanitize_text_field($_GET['tbacc']);
		if(check_admin_referer( 'delete_tbacc_'.$tbacc_key, 'tbacc_nonce' )):
			$key_id				= $wpdb->get_var("SELECT meta_id FROM $pm_tb WHERE meta_key='tbacc_key' AND meta_value='".$tbacc_key."' LIMIT 1");
			if(empty($key_id)):
				$query_str = '?page=tabs-accordion';
				foreach($_GET as $key=>$value):
					if($key=='action' || $key=='tbacc' || $key=='page' || $key =='tbacc_nonce')
						continue;

					$query_str .= '&'.$key.'='.$value;
				endforeach;
				wp_redirect($query_str);
				exit;
			endif;

			$delete_query		= "DELETE FROM $post_tb WHERE ID IN (SELECT post_id FROM $pm_tb WHERE meta_key='tbacc_key' AND meta_value='".$tbacc_key."')";
			$wpdb->query($delete_query);
			$delete_meta_query		= "DELETE FROM $pm_tb WHERE meta_value='".$tbacc_key."'";
			$wpdb->query($delete_meta_query);

			if($wpdb->last_error) :
			    $have_error = true;
				$message    = $wpdb->last_error;
			else:
				$have_error = false;
				$message	= "Tab & Accordion successfully deleted.";
			endif;
		endif;	
	endif;	
	/** code section for tab accordion ends **/	
?>
<!--main container starts-->
<div class="tbacc-main-container">
  	<!--header starts-->
 	 <div class="tbacc-header tbacc-i-header">
   		<!--left header starts-->
    	<div class="tbacc-left-header">
      		<h2 class="tbacc-admin-page-heading">Listings</h2>
    	</div>
    	<!--left header ends-->
    	<!--right header starts-->
    	<div class="tbacc-right-header">
        	<i class="tbacc-sprite tbacc-listing"></i>
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
	    	<!-- top content right starts -->
	    	<div class="tbacc-top-right-content">
	    		<form name="items_per_page_form" class="tbacc-items-per-page-form" action="" method="get">
	    			<?php 
                		if(sizeof($_GET)>0):
                			foreach($_GET as $key=>$value):
                				if($key!='items_per_page' && $key!='action' && $key!='tbacc' && $key!='paged'):
                					echo '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
                				endif;
                			endforeach;
                		endif;
                	?>
	    			<span>Show</span>
	    			<select name="items_per_page" class="tbacc-dropdown">
		    			<option value="10" <?php echo ($items_per_page==10?"selected":""); ?> >10</option>
		    			<option value="25" <?php echo ($items_per_page==25?"selected":""); ?> >25</option>
		    			<option value="50" <?php echo ($items_per_page==50?"selected":""); ?>>50</option>
		    			<option value="100" <?php echo ($items_per_page==100?"selected":""); ?>>100</option>
		    		</select>	
	    			<span>records</span>
	    		</form>
	    		
	    	
	    	</div>
	    	<!-- top content right ends -->
	    	
	      	<!-- tab-accordion-table-wrap starts-->
	      	<div class="tbacc-tab-accordion-table-wrap">
	      		<?php $tbacc_arr 		= tbacc_get_all_tab_accordions($paged,$items_per_page);?>
	      		<?php $tbacc_tab_count 	= tbacc_count_tab_accordions();?>
	      		<!-- tab-accodion table starts -->
	      		<table class="tab-accordion-table" cellpadding="0" cellspacing="0">
	      			<thead>
	      				<tr>
	      					<th width="10%">S.No</th>
	      					<th width="70%">Shortcode</th>
	      					<th width="20%">Action</th>
	      				</tr>
	      			</thead>
	      			<tbody>
	      				<?php if(sizeof($tbacc_arr)>0): $count=0; ?>
	      					<?php foreach($tbacc_arr as $tbacc):  $tbacc_key = $tbacc->meta_value;?>
	      						<tr>
	      							<td><?php echo ++$count; ?></td>
	      							<td><code>[tbacc key="<?php echo $tbacc_key; ?>"]</code></td>
	      							<td>
	      								<a href="?page=add-new-tabs-accordion&action=edit&tbacc=<?php echo $tbacc_key; ?>" class="tbacc-edit-link"><i class="tbacc-sprite tbacc-edit-small" title="Edit"></i></a>
	      								<a href="?page=tabs-accordion-settings&tbacc=<?php echo $tbacc_key; ?>" class="tbacc-setting-link"><i class="tbacc-sprite tbacc-setting-small" title="Setting"></i></a>
	      								<?php $delete_url = wp_nonce_url('?page=tabs-accordion&action=delete&tbacc='.$tbacc_key, 'delete_tbacc_'.$tbacc_key,'tbacc_nonce' );?>
	      								<a href="<?php echo $delete_url; ?>" class="tbacc-delete-link" title="Delete" onclick="return confirm('Are you sure yuo want to delete this tab accordion?');"><i class="tbacc-sprite tbacc-delete"></i></a>
	      							</td>
	      						</tr>
	      					<?php endforeach; ?>
	      				<?php else: ?>
	      					<tr>
	      						<td colspan="3">No record found.</td>
	      					</tr>
	      				<?php endif; ?>
	      			</tbody>
	      		</table>
	      		<!-- tab-accodion table endss -->
	      	</div>
	    	<!-- tab-accordion-table-wrap ends-->

	    	<!-- bottom content starts -->
	    	<div class="tbacc-bottom-content">
	    		<!-- bottom left content starts -->
	    		<div class="tbacc-bottom-left-content">
	    			<div class="tbacc-page-display-count">
		                Showing <span><?php echo esc_html((($paged-1)*$items_per_page)+1);?></span>
		                to <span><?php echo esc_html(($paged*$items_per_page<$tbacc_tab_count?$paged*$items_per_page:$tbacc_tab_count));?></span> of <span> <?php echo esc_html($tbacc_tab_count); ?></span> records
		            </div>
	    		</div>
	    		<!-- bottom left content ends -->
	    		
	    		<!-- bottom left content starts -->
	    		<div class="tbacc-bottom-right-content">
	    			<div class="tbacc-pagination">
	                	<?php
	                		$query_str ='';
	                		if(sizeof($_GET)>0):
	                			foreach($_GET as $key=>$value):
	                				if($key!='paged' && $key!='action' && $key!='tbacc' && $key!='page'):
	                					$query_str .= '&'.$key.'='.$value;
	                				endif;
	                			endforeach;
	                		endif;
	                		if($paged>1):
								echo '<a href="?page='.$_GET['page'].$query_str.'&paged='.($paged-1).'"  class="tbacc-previous" > &laquo; </a>';
							endif;

							$paged_mod = $paged%10;

							if($paged_mod ==0):
								$i = $paged;
							else:
								$i = $paged-($paged_mod-1);
							endif;
							if(($i+9)*$items_per_page < $tbacc_tab_count ):
								$count_limit = $i+9;
							else:
								$count_limit = ceil($tbacc_tab_count/$items_per_page);
							endif;

							if($count_limit>1):

								while($i <= $count_limit):
										if(!empty($i)):
											echo '<a href="?page='.$_GET['page'].$query_str.'&paged='.$i.'"  class="tbacc-page '.($paged==$i?"active":"").'">'.$i.'</a>';
										endif;
									$i++;
								endwhile;
							endif;

							if($paged*$items_per_page < $tbacc_tab_count):
								echo '<a href="?page='.$_GET['page'].$query_str.'&paged='.($paged+1).'" class="tbacc-next" >&raquo;</a>';
							endif;
						?>
	                </div>
	    		</div>
	    		<!-- bottom left content ends -->
              
                <div class="tbacc-clear"></div>
	    	</div>
	    	<!-- bottom content ends -->
	    </div>
	    <!--content wrapper ends-->
	</div>
	<!--content ends-->
</div>
<!--main container ends-->
