=== WooSlide WooCommerce Gallery ===
Contributors: deanoakley, jinksi
Author: Web Design Gold Coast
Author URI: https://thriveweb.com.au/
Plugin URI: https://thriveweb.com.au/the-lab/wooslide/
Tags: WooSlide, woocommerce, woocommerce gallery, products, product gallery, responsive
Requires at least: 4.9.1
Tested up to: 4.9.4
Stable tag: 1.0

A WooCommerce gallery plugin built using PhotoSwipe and Swiper.

== Description ==

A WooCommerce gallery plugin built using PhotoSwipe [photoswipe](http://photoswipe.com/ "PhotoSwipe") and [swiper](https://github.com/nolimits4web/Swiper "Swiper").

WooSlide should work out of the box with your WooCommerce gallery settings. Simply adjust your image sizes in WooCommerce > Settings > Products > Display. You may need to rebuild your thumbnails when changing image sizes.

* Responsive
* Very Mobile Friendly
* Keyboard control
* Full image size
* 2 colour options

Planned Features:
* Show titles or captions

Actions:
wooslide_before_main
wooslide_after_main

Filter:
wooslide_zoomed_image_size
add_filter( 'wooslide_zoomed_image_size', 'max_image_size', 10, 1 );
function max_image_size( $size ) {
	$size = "large";
	return $size;
}

[More Info here](http://thriveweb.com.au/the-lab/wooslide/ "WooSlide")

== Installation ==

1. Upload `/wooslide/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Check out your new gallery!

= 1.0 =
* Initial
