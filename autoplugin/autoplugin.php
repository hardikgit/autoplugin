<?php

/**
 * @since             1.0.0
 * @package           Auto_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Auto Plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hardik panchal
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-plugin
 * Domain Path:       /languages
 */
register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');


function html_page_permalink(){
	global $wp_rewrite;
 if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
 }
}
add_action('init', 'html_page_permalink', -1);

add_filter('user_trailingslashit', 'no_page_slash',66,2);
function no_page_slash($string, $type){
   global $wp_rewrite;
	if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
		return untrailingslashit($string);
  }else{
   return $string;
  }
}

function active() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
 }
  $wp_rewrite->flush_rules();
}	
	function deactive() {
		global $wp_rewrite;
		$wp_rewrite->page_structure = str_replace(".html","",$wp_rewrite->page_structure);
		$wp_rewrite->flush_rules();
	}
 
register_activation_hook( __FILE__, 'pagekey_db' );
register_activation_hook( __FILE__, 'country_db' );
register_activation_hook( __FILE__, 'state_db' );
register_activation_hook( __FILE__, 'city_db' );
register_activation_hook( __FILE__, 'country_sitemap' );
register_deactivation_hook( __FILE__, 'remove_database' );

//table table
function country_db() {
	global $wpdb;
	global $jal_db_version;
	$table_country = $wpdb->prefix . 'country';
	$charset_collate = $wpdb->get_charset_collate();
	if($wpdb->get_var("show tables like '$table_country'") != $table_country){
	$sql = "CREATE TABLE $table_country (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				country varchar(55) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version );
	}
}
function pagekey_db() {
	global $wpdb;
	global $jal_db_version;
	$table_key = $wpdb->prefix . 'pagekey';
	$charset_collate = $wpdb->get_charset_collate();
	if($wpdb->get_var("show tables like '$table_key'") != $table_key){
	$sql = "CREATE TABLE $table_key (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				pagekey varchar(200) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version);
	}
}
function state_db() {
	global $wpdb;
	global $jal_db_version;
	$table_state = $wpdb->prefix . 'state';
	$charset_collate = $wpdb->get_charset_collate();
	if($wpdb->get_var("show tables like '$table_state'") != $table_state){
	$sql = "CREATE TABLE $table_state (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				state varchar(200) DEFAULT '' NOT NULL,
				country varchar(200) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version);
	}
}

function city_db() {
	global $wpdb;
	global $jal_db_version;
	$table_city = $wpdb->prefix . 'city';
	$charset_collate = $wpdb->get_charset_collate();
	if($wpdb->get_var("show tables like '$table_city'") != $table_city){
	$sql = "CREATE TABLE $table_city (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				city varchar(200) DEFAULT '' NOT NULL,
				state varchar(200) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version);
	}
}


//data import
function jal_install_data(){
	global $wpdb;
	$table_country = $wpdb->prefix . 'country';
	$table_key = $wpdb->prefix . 'pagekey';
	$table_state = $wpdb->prefix . 'state';
	$table_city = $wpdb->prefix . 'city';
	$path_autolistcsv = dirname(__FILE__)."/autolist.csv";
	if(file_exists($path_autolistcsv)){
		$file = fopen($path_autolistcsv,"r");
			while(! feof($file))
			{
				$products1[] = fgetcsv($file);
			}
	}
	foreach ($products1[0] as $productcountry){ 
		if($productcountry==""){
			break;
		}
		$wpdb->insert($table_country, array('country' => $productcountry ));
		
		$path_autolistcsv = dirname(__FILE__)."/autolistcsv/".$productcountry.".csv";
		if(file_exists($path_autolistcsv)){
			$file1 = fopen( $path_autolistcsv,"r");
			while(!feof($file1))
			{
				$x[] = fgetcsv($file1);
			}
			for($k=1;$k<count($x[0]);$k++){
				if($x[1][$k]==""){
					break;
				}
				$wpdb->insert($table_state, array('state' => $x[0][$k],'country' => $productcountry ));
				for($i=1;$i<500;$i++){
					if($x[0][$k]==""){
						break;
					}
					$wpdb->insert($table_city, array('city' => $x[$i][$k],'state' => $x[0][$k] ));
				}
			}
			unset($x);
		}
		unset($productcountry);
	}
	foreach ($products1[1] as $productkey){ 
	if($productkey==""){
		break;
	}
		$wpdb->insert($table_key, array('pagekey' => $productkey ));
	}
}
register_activation_hook( __FILE__, 'jal_install_data' );

$plugin_dir_path = dirname(__FILE__);

//-------------------------------------------------------------------------------------------------------------------------------------------------------//
//Call Country,State,City Template
function get_sitemap_template() {
	global $post;
	global $wpdb;
	$table_country = $wpdb->prefix.'country';
	$table_state = $wpdb->prefix.'state';
	$table_city = $wpdb->prefix.'city';
	$table_pagekey = $wpdb->prefix.'pagekey';
	
	$country_result = $wpdb->get_results("SELECT country FROM $table_country");
	
	$pagekey_result = $wpdb->get_results("SELECT pagekey FROM $table_pagekey");
	
	$current_url  = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	$URI  = set_url_scheme( $_SERVER['REQUEST_URI'] );
	foreach($country_result as $row){
		$country = $row->country;
		$country_url = get_site_url()."/".$country."sitemap.html";
		if(strpos($current_url,"/".$country."/") !== FALSE){
			$state_result = $wpdb->get_results("SELECT state FROM $table_state WHERE country='$country'");
			foreach($state_result as $staterow){
				$state = $staterow->state;
				if(strpos($current_url,"/".$country."/".$state."sitemap.html") !== FALSE){
					//call city sitemap
					$single_template = dirname(__FILE__).'/template/template-citysitemap.php';
					include $single_template;
					exit;
				}
				if(strpos($current_url,"/".$country."/".$state."/") !== FALSE){
					$city_result = $wpdb->get_results("SELECT city FROM $table_city WHERE state='$state'");
					foreach($city_result as $cityrow){
						$city = $cityrow->city;
						if(strpos($current_url,"/".$country."/".$state."/".$city."sitemap.html") !== FALSE){
							//call city sitemap
							$single_template = dirname(__FILE__).'/template/template-city2sitemap.php';
							include $single_template;
							exit;
						}
					}
					foreach($pagekey_result as $pagekey1){
						$pagekeyd = $pagekey1->pagekey;
						$pagekey = trim(str_replace("-"," ",$pagekeyd));
						$state_page_url = get_site_url()."/".$country."/".$state."/".$pagekeyd."".$state.".html";
						if($state_page_url==$current_url) {
							$name1 = ucwords(str_replace("-"," ",$state));
							//Call Country Page Key
							$single_template = dirname(__FILE__).'/pages/'.str_replace(" ","-",$pagekey).'.php';
							include $single_template;
							exit;
						}
						foreach($city_result as $cityrow){
							$city = $cityrow->city;
							$city_page_url = get_site_url()."/".$country."/".$state."/".$pagekeyd."".$city.".html";
							if($city_page_url==$current_url) {
								$name1 = ucwords(str_replace("-"," ",$city));
							//Call city Page Key
								$single_template = dirname(__FILE__).'/pages/'.str_replace(" ","-",$pagekey).'.php';
								include $single_template;
								exit;
							}
						}
						
					}
				}
			}	
		}
		if ($country_url==$current_url) {
			//Call State Sitemap
			$single_template = dirname(__FILE__).'/template/template-statesitemap.php';
			include $single_template;
			exit;
		}
		
		foreach($pagekey_result as $pagekey1){
			$pagekeyd = $pagekey1->pagekey;
			$pagekey = trim(str_replace("-"," ",$pagekeyd));
			$country_page_url = get_site_url()."/".$country."/".$pagekeyd."".$country.".html";
			if($country_page_url==$current_url) {
				$name1 = ucwords(str_replace("-"," ",$country));
				//Call Country Page Key
				$single_template = dirname(__FILE__).'/pages/'.str_replace(" ","-",$pagekey).'.php';
				include $single_template;
				exit;
			}
		}
		
	}
	
}
add_filter( 'init', 'get_sitemap_template' );


function my_plugin_remove_database() {
	global $wpdb;
	$country_name = $wpdb->prefix . "country";
	$country = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($country);
	$state_name = $wpdb->prefix . "state";
	$state = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($country);
	$city_name = $wpdb->prefix . "city";
	$city = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($country);
	$pagekey_name = $wpdb->prefix . "pagekey";
	$pagekey = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($pagekey);
	wp_delete_post(get_option("country_sitemap_id"));
	delete_option("country_sitemap_title");
	delete_option("country_sitemap_name");
	delete_option("country_sitemap_id");
}


//menu
include 'auto-menu.php';
include 'country-page.php';
include 'state-page.php';
include 'city-page.php';
//include 'xml-sitemap.php';
?>
