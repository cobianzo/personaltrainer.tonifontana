<?php

function ux_export_cdata( $str ) {
	if ( seems_utf8( $str ) == false )
		$str = utf8_encode( $str );

	// $str = ent2ncr(esc_html($str));
	$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

	return $str;
}


function ux_export_layerslider(){
	global $wpdb;
	$table_layerslider = $wpdb->prefix . "layerslider";
	$sql = "CREATE TABLE $table_layerslider (
			  id int(10) NOT NULL AUTO_INCREMENT,
			  name varchar(100) NOT NULL,
			  data mediumtext NOT NULL,
			  date_c int(10) NOT NULL,
			  date_m int(11) NOT NULL,
			  flag_hidden tinyint(1) NOT NULL DEFAULT 0,
			  flag_deleted tinyint(1) NOT NULL DEFAULT 0,
			  PRIMARY KEY  (id)
			);";
			
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	$get_layerslider = $wpdb->get_results( "SELECT * FROM $table_layerslider" );
	
	if($get_layerslider){
		if(count($get_layerslider) > 0){
			foreach($get_layerslider as $layerslider){
				echo "\t<layerslider>";
				echo "<id>" . $layerslider->id . "</id>";
				echo "<name>" . $layerslider->name . "</name>";
				echo "<data>" . ux_export_cdata($layerslider->data) . "</data>";
				echo "<date_c>" . $layerslider->date_c . "</date_c>";
				echo "<date_m>" . $layerslider->date_m . "</date_m>";
				echo "<flag_hidden>" . $layerslider->flag_hidden . "</flag_hidden>";
				echo "<flag_deleted>" . $layerslider->flag_deleted . "</flag_deleted>";
				echo "</layerslider>\n";
				
			}
		}
	}
}
add_action( 'rss2_head', 'ux_export_layerslider' );

function ux_export_theme_option(){
	global $wpdb;
	$table_options = $wpdb->prefix . "options";
	$get_option = $wpdb->get_results( "SELECT * FROM $table_options WHERE option_name LIKE '%theme_option_%'" );
	if(count($get_option) > 0){
		foreach($get_option as $option){
			echo "\t<theme_option>";
			echo "<option_name>" . $option->option_name . "</option_name>";
			echo "<option_value>" . ux_export_cdata($option->option_value). "</option_value>";
			echo "</theme_option>\n";
		}
	}
}
add_action( 'rss2_head', 'ux_export_theme_option' );

function ux_export_theme_front_page(){
	$show_on_front = get_option('show_on_front'); 
	$page_on_front = get_option('page_on_front');
	
	echo "\t<theme_front_page>";
	if($show_on_front){ echo "<show_on_front>" .$show_on_front. "</show_on_front>"; }
	if($page_on_front || $page_on_front == '0'){ echo "<page_on_front>" .$page_on_front. "</page_on_front>"; }
	echo "</theme_front_page>\n";
}
add_action( 'rss2_head', 'ux_export_theme_front_page' );

function ux_export_theme_mods(){
	$nav_menu_locations = get_theme_mod('nav_menu_locations');
	if($nav_menu_locations){
		foreach($nav_menu_locations as $menu_name => $menu_id){
			if($menu_id != 0){
				$menu = get_term( $menu_id, 'nav_menu' );
				echo "\t<nav_menu_locations>";
				echo "<menu_name>" . $menu_name . "</menu_name>";
				echo "<menu_slug>" . $menu->slug . "</menu_slug>";
				echo "</nav_menu_locations>\n";
			}
		}
	}
}
add_action( 'rss2_head', 'ux_export_theme_mods' );

function ux_export_theme_widgets(){
	global $wpdb;
	
	$sidebars_widgets       = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'sidebars_widgets'" );
	$widget_categories      = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_categories'" );
	$widget_text            = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_text'" );
	$widget_rss             = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_rss'" );
	$widget_search          = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_search'" );
	$widget_recent_posts    = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_recent-posts'" );
	$widget_recent_comments = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_recent-comments'" );
	$widget_archives        = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_archives'" );
	$widget_meta            = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_meta'" );
	$widget_calendar        = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_calendar'" );
	$widget_uxconatactform  = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_uxconatactform'" );
	$widget_nav_menu        = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_nav_menu'" );
	$widget_pages           = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_pages'" );
	$widget_uxsocialinons   = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_uxsocialinons'" );
	$widget_tag_cloud       = $wpdb->get_row( "SELECT * FROM $wpdb->options WHERE option_name = 'widget_tag_cloud'" );
	
	echo "\t<sidebars_widgets>";
	if($sidebars_widgets){
		echo ux_export_cdata($sidebars_widgets->option_value);
	}
	echo "</sidebars_widgets>\n";
	
	echo "\t<theme_widgets>";
	if($widget_categories){
		echo "<widget_categories>" . ux_export_cdata($widget_categories->option_value) . "</widget_categories>";
	}
	if($widget_text){
		echo "<widget_text>" . ux_export_cdata($widget_text->option_value) . "</widget_text>";
	}
	if($widget_rss){
		echo "<widget_rss>" . ux_export_cdata($widget_rss->option_value) . "</widget_rss>";
	}
	if($widget_search){
		echo "<widget_search>" . ux_export_cdata($widget_search->option_value) . "</widget_search>";
	}
	if($widget_recent_posts){
		echo "<widget_recent_posts>" . ux_export_cdata($widget_recent_posts->option_value) . "</widget_recent_posts>";
	}
	if($widget_recent_comments){
		echo "<widget_recent_comments>" . ux_export_cdata($widget_recent_comments->option_value) . "</widget_recent_comments>";
	}
	if($widget_archives){
		echo "<widget_archives>" . ux_export_cdata($widget_archives->option_value) . "</widget_archives>";
	}
	if($widget_meta){
		echo "<widget_meta>" . ux_export_cdata($widget_meta->option_value) . "</widget_meta>";
	}
	if($widget_calendar){
		echo "<widget_calendar>" . ux_export_cdata($widget_calendar->option_value) . "</widget_calendar>";
	}
	if($widget_uxconatactform){
		echo "<widget_uxconatactform>" . ux_export_cdata($widget_uxconatactform->option_value) . "</widget_uxconatactform>";
	}
	if($widget_nav_menu){
		echo "<widget_nav_menu>" . ux_export_cdata($widget_nav_menu->option_value) . "</widget_nav_menu>";
	}
	if($widget_pages){
		echo "<widget_pages>" . ux_export_cdata($widget_pages->option_value) . "</widget_pages>";
	}
	if($widget_uxsocialinons){
		echo "<widget_uxsocialinons>" . ux_export_cdata($widget_uxsocialinons->option_value) . "</widget_uxsocialinons>";
	}
	if($widget_tag_cloud){
		echo "<widget_tag_cloud>" . ux_export_cdata($widget_tag_cloud->option_value) . "</widget_tag_cloud>";
	}
	echo "</theme_widgets>\n";
}
add_action( 'rss2_head', 'ux_export_theme_widgets' );

//add_action( 'export_filters', 'ux_export_layerslider' );

?>
