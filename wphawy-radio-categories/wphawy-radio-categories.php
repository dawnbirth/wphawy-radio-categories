<?php
/**
Plugin Name: Wphawy radio categories
Description: This plugin will remove the default checkbox categories metabox from the "Add/Edit" post screen and replace it with another radio button categories.
Version: 1.0
Author: Aboelabbas
Author URI: http://www.ar-wp.com/forums/users/dawnbirth/
Plugin URI: http://www.wphawy.com
* This plugin depends on the Idea of the tutorial "How to Use Radio Buttons With Taxonomies" on wp.tutsplus.com .
*/

class WphawyRadioCats {

	function __construct() {
	
		add_action( 'admin_menu',     array( $this, 'remove_meta_box'  )  );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box'     )  );
		
	}
	
	/* =Remove the default categories metabox
	------------------------------------------------------------ */
	function remove_meta_box(){

	   remove_meta_box( 'categorydiv', 'post', 'side' );
	   
	} 


	/* =Add our new Radio categories metabox
	------------------------------------------------------------ */
	 function add_meta_box() {
	 
		 add_meta_box( 'wphawycatsdiv', __( 'Categories' ) , array( $this, 'categories_metabox' ), 'post' , 'side', 'core' ); 
		 
	 }
	 
	/* =Our function that creates the new radio button categories list
	-------------------------------------------------------------------- */
	function categories_metabox( $post ) {  

		$taxonomy  = 'category';
		
		$tax       = get_taxonomy( $taxonomy );
		 
		$name      = 'tax_input[' . $taxonomy . ']';  
		  
		$terms     = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );

		$popular   = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );
		
		$postterms = get_the_terms( $post->ID, $taxonomy  );
		
		$current   = ( $postterms ? array_pop( $postterms ) : false );
		
		$current   = ( $current   ? $current->term_id : 0 );
?>

		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

			<!-- Display tabs-->
			<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
				<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
				<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used' ); ?></a></li>
			</ul>

			<!-- Display taxonomy terms -->
			<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
				<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
					<?php   foreach ( $terms as $term ) {
						$id = $taxonomy . '-' . $term->term_id;
						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='radio' id='in-$id' name='{$name}'" . checked( $current, $term->term_id, false ) . "value='$term->term_id' />$term->name<br />";
					   echo "</label></li>";
					}?>
			   </ul>
			</div>

			<!-- Display popular taxonomy terms -->
			<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
				<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
					<?php   foreach( $popular as $term ) {
						$id = 'popular-' . $taxonomy . '-' . $term->term_id;
						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='radio' id='in-$id'" . checked( $current, $term->term_id, false )."value='$term->term_id' />$term->name<br />";
						echo "</label></li>";
					}?>
			   </ul>
		   </div>

		</div>
		<?php
	}
}

/* =Now Initialize our plugin
------------------------------- */
$wphawyRCats = new WphawyRadioCats ;
