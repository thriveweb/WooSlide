<?php
/*
Plugin Name: WooSlide
Plugin URI: http://thriveweb.com.au/the-lab/wooslide/
Description: This is a image gallery plugin for WordPress built using <a href="http://photoswipe.com.au/">photoswipe</a> and <a href="https://github.com/nolimits4web/Swiper">Swiper</a>.

Author: Website Design
Author URI: https://thriveweb.com.au/
Version: 1.0
Text Domain: wooslide
*/

/*  Copyright 2010  Dean Oakley  (email : dean@thriveweb.com.au)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function cl ($data) {
  echo '<script>';
  echo 'console.log('. json_encode(  $data ) .') ';
  echo '</script>';
}

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Illegal Entry');
}

//============================== wooslide options ========================//
class wooslide_plugin_options {

	//Defaults
	public static function WooSlide_getOptions() {

		//Pull from WP options database table
		$options = get_option('wooslide_options');

		if (!is_array($options)) {

			$options['white_theme'] = false;

			update_option('wooslide_options', $options);
		}
		return $options;
	}


	public static function update() {

		if(isset($_POST['wooslide_save'])) {

			$options = wooslide_plugin_options::WooSlide_getOptions();

			if (isset($_POST['white_theme'])) {
				$options['white_theme'] = (bool)true;
			} else {
				$options['white_theme'] = (bool)false;
			}

			update_option('wooslide_options', $options);

		} else {
			wooslide_plugin_options::WooSlide_getOptions();
		}


	}


	public static function display() {

		$options = wooslide_plugin_options::WooSlide_getOptions();
		?>

		<div id="wooslide_admin" class="wrap">

			<h2>WooSlide Options</h2>

			<p>WooSlide is a WooCommerce gallery plugin for WordPress built using Photoswipe from  Dmitry Semenov.  <a href="http://photoswipe.com/">Photoswipe</a> and a href="https://github.com/nolimits4web/Swiper">Swiper</a></p>

			<p>More options coming soon. Edit your image sizes <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=products&section=display', 'http' ); ?> "> here </a></p>

			<p style="font-style:italic; font-weight:normal; color:grey " >Please note: Images that are already on the server will not change size until you regenerate the thumbnails. Use <a title="http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/" href="http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/">AJAX thumbnail rebuild</a> </p>

			<form method="post" action="#" enctype="multipart/form-data">

				<div class="ps_border" ></div>


				<p><label><input name="white_theme" type="checkbox" value="checkbox" <?php if($options['white_theme']) echo "checked='checked'"; ?> /> Use white theme?</label></p>


				<div class="ps_border" ></div>

				<p><input class="button-primary" type="submit" name="wooslide_save" value="Save Changes" /></p>

			</form>


		</div>

		<?php
	}
}


function WooSlide_getOption($option) {
	global $mytheme;
	return $mytheme->option[$option];
}

function wooslide_using_woocommerce() {
	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

// register functions
add_action('admin_menu', array('wooslide_plugin_options', 'update'));

$options = get_option('wooslide_options');

///////////
//Admin CSS
function wooslide_register_head() {

    $url = plugins_url( 'admin.css', __FILE__ );

    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'wooslide_register_head');

///////////
//Sub Menu
function register_wooslide_custom_submenu_page() {
    add_submenu_page( 'woocommerce', 'WooSlide', 'WooSlide', 'manage_options', 'wooslide-custom-submenu-page', array('wooslide_plugin_options', 'display') );
}
function wooslide_custom_submenu_page_callback() {
    echo '<h3>WooSlide Custom Submenu Page</h3>';
}
add_action('admin_menu', 'register_wooslide_custom_submenu_page',99);


//============================== insert HTML header tag ========================//

function wooslide_scripts_method() {

	$wooslide_wp_plugin_path =  plugins_url() . '/wooslide' ;
	$options = get_option('wooslide_options');

	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		wp_enqueue_style( 'pswp-css', $wooslide_wp_plugin_path . '/pswp/photoswipe.css'  );

	    if($options['white_theme']) wp_enqueue_style( 'white_theme', $wooslide_wp_plugin_path . '/pswp/white-skin/skin.css'  );
	    else wp_enqueue_style( 'pswp-skin', $wooslide_wp_plugin_path . '/pswp/default-skin/default-skin.css'  );
	    wp_enqueue_style( 'swiper-css', $wooslide_wp_plugin_path . '/swiper-4.1.6/css/swiper.min.css'  );

	    wp_enqueue_script( 'pswp', $wooslide_wp_plugin_path . '/pswp/photoswipe.min.js', null, null, true );
	    wp_enqueue_script( 'pswp-ui', $wooslide_wp_plugin_path . '/pswp/photoswipe-ui-default.min.js', null, null, true );

			wp_enqueue_script( 'swiper-js', $wooslide_wp_plugin_path .'/swiper-4.1.6/js/swiper.min.js', null, null, true );

			wp_enqueue_style( 'wooslide-css', $wooslide_wp_plugin_path . '/wooslide.css' );
	    wp_enqueue_script( 'wooslide-js', $wooslide_wp_plugin_path .'/wooslide.js', null, null, true );
	}
}
add_action('wp_enqueue_scripts', 'wooslide_scripts_method');

///////////////////////
// remove woo lightbox
add_action( 'wp_print_scripts', 'wooslide_deregister_javascript', 100 );
function wooslide_deregister_javascript() {
	wp_deregister_script( 'prettyPhoto' );
	wp_deregister_script( 'prettyPhoto-init' );
}
add_action( 'wp_print_styles', 'wooslide_deregister_styles', 100 );
function wooslide_deregister_styles() {
	wp_deregister_style( 'woocommerce_prettyPhoto_css' );
}

///////////////////////
//override Woo Gallery
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'wooslide_woocommerce_show_product_thumbnails', 20 );

function wooslide_woocommerce_show_product_thumbnails(){
	global $post, $woocommerce, $product;

	$images = [];
	do_action( 'wooslide_before_main' );
	$zoomed_image_size = array(1920, 1080);

	if ( has_post_thumbnail() ) {
		$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
		$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );

		$hq = wp_get_attachment_image_src( get_post_thumbnail_id(), apply_filters( 'wooslide_zoomed_image_size', $zoomed_image_size ) );
		$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),
			array(
				'title' => '',
				'data-hq' => $hq[0],
				'data-w' => $hq[1],
				'data-h' => $hq[2],
				'class' => 'swiper-slide'
				)
		);

		$images[] = $image;
	}

	if (method_exists($product, 'get_gallery_image_ids')) {
		$attachment_ids = $product->get_gallery_image_ids();
	} else {
		$attachment_ids = $product->get_gallery_attachment_ids();
	}

	if ( $attachment_ids ) {
		function addImageThumbnail($attachment_id, $zoomed_image_size) {
			global $post;
			$img       	= wp_get_attachment_image( $attachment_id, 'shop_thumbnail' );
			$hq       		= wp_get_attachment_image_src( $attachment_id, apply_filters( 'wooslide_zoomed_image_size', $zoomed_image_size ) );
			$med       		= wp_get_attachment_image_src( $attachment_id, 'shop_single' );

			$image = wp_get_attachment_image( $attachment_id, apply_filters( 'wooslide_zoomed_image_size', $zoomed_image_size ), false,
				array(
					'title' => '',
					'data-hq' => $hq[0],
					'data-w' => $hq[1],
					'data-h' => $hq[2],
					'class' => 'swiper-slide'
					)
			);

			return $image;
		}

		//add thumbnails
		foreach ( $attachment_ids as $attachment_id ) {
			$image_link = wp_get_attachment_url( $attachment_id );
			if ( !$image_link ) { continue; }
			$image = addImageThumbnail($attachment_id, $zoomed_image_size);
			$images[] = $image;
		}
	}

	if (count($images) > 0) {
		cl($images);

	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
	}

	?>

	<div class="WooSlide">
		<!-- Slider main container -->
		<div class="swiper-container WooSlide--gallery-top">
		    <!-- Additional required wrapper -->
		    <div class="swiper-wrapper">
		        <!-- Slides -->
						<?php foreach($images as $image) {
							echo $image;
						} ?>
		    </div>

		    <div class="swiper-button-prev">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
						<polyline points="15 18 9 12 15 6"></polyline>
					</svg>
				</div>
		    <div class="swiper-button-next">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
		</div>

		<div class="swiper-container WooSlide--gallery-thumbs">
		    <!-- Additional required wrapper -->
		    <div class="swiper-wrapper">
		        <!-- Slides -->
						<?php foreach($images as $image) {
							echo $image;
						} ?>
		    </div>
		</div>
		<?php


		// Hook After Wooswipe
		do_action( 'wooslide_after_main' );
		do_action( 'wooslide_after_thumbs' ); ?>
	</div>

	<!-- PSWP -->
	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="pswp__bg"></div>
	    <div class="pswp__scroll-wrap">
	        <div class="pswp__container">
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	        </div>
	        <div class="pswp__ui pswp__ui--hidden">
	            <div class="pswp__top-bar">
	                <div class="pswp__counter"></div>
	                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
	                <button class="pswp__button pswp__button--share" title="Share"></button>
	                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
	                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
	                <div class="pswp__preloader">
	                    <div class="pswp__preloader__icn">
	                      <div class="pswp__preloader__cut">
	                        <div class="pswp__preloader__donut"></div>
	                      </div>
	                    </div>
	                </div>
	            </div>
	            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
	                <div class="pswp__share-tooltip"></div>
	            </div>
	            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
	            </button>
	            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
	            </button>
	            <div class="pswp__caption">
	                <div class="pswp__caption__center"></div>
	            </div>
	        </div>
	    </div>
	</div>

<?php
}

add_action( 'after_setup_theme', 'wooslide_theme_setup' );

function wooslide_theme_setup() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

?>
