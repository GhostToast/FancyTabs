<?php
/*
Plugin Name: FancyTabs
Plugin URI: https://github.com/GhostToast/FancyTabs
Description: Shortcode driven in-page jQuery tab navigation
Version: 1.0.2
Author: Gustave F. Gerhardt
Author URI: http://www.morningstarmediagroup.com
*/

function fancytabs_styles() {
        if ( is_readable( plugin_dir_path( __FILE__ ) . 'fancytabs.css' ) ) {
            wp_enqueue_style( 'Fancy-Tabs-Styles', plugin_dir_url( __FILE__ ) . 'fancytabs.css', array(), '0.1', 'screen' );
        }
}
add_action( 'wp_enqueue_scripts', 'fancytabs_styles' );

function fancytabs_scripts() {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'fancytabs_scripts');

add_shortcode( 'fancytabs', 'fancytabs_group' );
function fancytabs_group( $atts, $content ){
	$GLOBALS['tab_count'] = 0;
	
	do_shortcode( $content );
	
	if( is_array( $GLOBALS['tabs'] ) ){
		$int = 1;
		$color_on = '#AAA';
		$color_off = '#DDD';
		foreach( $GLOBALS['tabs'] as $tab ){
			$code[] = '$("#tabs-link-'.$int.'").click (function (event) {
							$(".link-catch-all").css("background-color", "'.$color_off.'");
							$("#tabs-link-'.$int.'").css("background-color", "'.$color_on.'");
							$(".tabs-catch-all").hide();
							$("#tabs-'.$int.'").show();
			});';
			$tabs[] = '<li><a class="link-catch-all" id="tabs-link-'.$int.'">'.$tab['title'].'</a></li>';
			$panes[] = '<div id="tabs-'.$int.'" class="tabs-catch-all">'.$tab['content'].'</div>'."\n";
			$int++;
		}
		$return = 	'<script type ="text/javascript">
						$(document).ready(function() {
							$(".link-catch-all").css("background-color", "'.$color_off.'");
							$("#tabs-link-1").css("background-color", "'.$color_on.'");
							$(".tabs-catch-all").hide();
							$("#tabs-1").show();
							'.implode( "\n", $code ).'
						});
					</script>
					<div id="fancy-tabs">
						<ul class="tabs">'.implode( "\n", $tabs ).'</ul>
					</div>'."\n"
					.implode( "\n", $panes );
	}
	return $return;
}

add_shortcode( 'tab', 'fancy_tab' );
function fancy_tab( $atts, $content ){
	extract(shortcode_atts(array(
			'title' => 'Tab %d', ), $atts) );
	
	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'content' => $content );
	$GLOBALS['tab_count']++;
}
?>