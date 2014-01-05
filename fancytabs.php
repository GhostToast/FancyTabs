<?php
/*
Plugin Name: FancyTabs
Plugin URI: https://github.com/GhostToast/FancyTabs
Description: Shortcode driven in-page jQuery tab navigation
Version: 1.1.0
Author: Gustave F. Gerhardt
Author URI: http://ghosttoa.st
*/

function fancytabs_scripts() {
	if(!is_admin()){
		
		if ( file_exists( get_stylesheet_directory()."/fancytabs.css" ) ) {
			wp_enqueue_style( 'Fancy-Tabs-Styles', get_stylesheet_directory_uri() . '/fancytabs.css', array(), '1.0' );
		}
	
		elseif ( file_exists( get_template_directory()."/fancytabs.css" ) ) {
			wp_enqueue_style( 'Fancy-Tabs-Styles', get_template_directory_uri() . '/fancytabs.css', array(), '1.0' );
		}
	
		else {
			wp_enqueue_style( 'Fancy-Tabs-Styles', plugins_url('/fancytabs.css', __FILE__), array(), '1.0' );
		}

		wp_register_script('fancy_tabs_js', plugin_dir_url(__FILE__).'fancytabs.js', array( 'jquery' ));
		wp_enqueue_script('fancy_tabs_js');
	}
}
add_action('wp_enqueue_scripts', 'fancytabs_scripts');

add_shortcode( 'fancytabs', 'fancytabs_group' );
function fancytabs_group( $atts, $content ){
	$GLOBALS['tab_count'] = 0;
	
	do_shortcode( $content );
	
	if( is_array( $GLOBALS['tabs'] ) ){
		$i = 1;
		foreach( $GLOBALS['tabs'] as $tab ){
			$tabs[] = '<li><a class="link-catch-all" data-counter="'.$i.'">'.$tab['title'].'</a></li>';
			$panes[] = '<div data-counter="'.$i.'" class="tabs-catch-all">'.$tab['content'].'</div>'."\n";
			$i++;
		}
		$return  = '<div id="fancy-tabs">';
		$return .= '<ul class="tabs">'.implode( "\n", $tabs ).'</ul>';
		$return .= '</div>';
		$return .= implode( "\n", $panes );
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
