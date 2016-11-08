<?php
//Create Country Page And Assign Template
function country_sitemap() {
		global $wpdb;
		$the_page_title = 'Country Sitemap';
		$the_page_name = 'Country Sitemap';
		// the menu entry...
		delete_option("country_sitemap_title");
		add_option("country_sitemap_title", $the_page_title, '', 'yes');
		// the slug...
		delete_option("country_sitemap_name");
		add_option("country_sitemap_name", $the_page_name, '', 'yes');
		// the id...
		delete_option("country_sitemap_id");
		add_option("country_sitemap_id", '0', '', 'yes');
		$the_page = get_page_by_title( $the_page_title );
		if ( ! $the_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = $the_page_title;
			$_p['post_content'] = "";
			$_p['post_status'] = 'publish';
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			// Insert the post into the database
			$the_page_id = wp_insert_post( $_p );
			//update_post_meta( $the_page_id, '_wp_page_template ', 'template-countrysitemap.php' );
		}else {
		// the plugin may have been previously active and the page may just be trashed...
			$the_page_id = $the_page->ID;
			//make sure the page is not trashed...
			$the_page->post_status = 'publish';
			$the_page_id = wp_update_post( $the_page );
		}
	delete_option( 'country_sitemap_id' );
	add_option( 'country_sitemap_id', $the_page_id );
	//update_post_meta( $the_page_id, '_wp_page_template ', 'template-countrysitemap.php' );
}

add_filter( 'template_include', 'portfolio_page_template', 99 );
function portfolio_page_template( $template ) {
$country_url = get_site_url()."/country-sitemap.html";
	$current_url  = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	if ( $country_url== $current_url ) {
			$new_template = dirname(__FILE__). '/template/template-countrysitemap.php';
			return $new_template ;
	}
	return $template;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------//
//Country Sitemap Html
function country_sitemap_html(){
	global $wpdb;
	$table_country = $wpdb->prefix . 'country';
	$result1 = $wpdb->get_results("SELECT country FROM $table_country");
	foreach($result1 as $row){
		$result[0][] = $row->country;
	}
	include 'pagination.php';
	$pagination = new pagination;
	if (count($result)) {
	$productPages = $pagination->generate($result, 10);
		if (count($productPages) != 0) {
			echo $pageNumbers = '<div class="numbers">'.$pagination->links().'</div>';
			echo '<br />';
		   ?>
			<div class="sitemaplist" style="width:100%">
				<ul>
					<?php
					foreach ($productPages[0] as $productArray){ 
						if($productArray==""){
							break;
						}
					?>
					<li class="sitemaplist" style="color:#000000;" > 
						<?php $urllink = $productArray . "sitemap.html"; ?>
						<?php $productArray = ucwords(strtolower($productArray)); ?>
						<a href="<?php echo $urllink; ?>" target="_blank">HTML SiteMap of <?php echo str_replace("-"," ",$productArray); "<br />";?></a>
					</li>
					<?php 
					} 
					?>
				</ul>
			</div>
			<?php  
			echo $pageNumbers;
			echo '<br />';
		}
	}
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------//
//Country Pages
function country_page_html($country){
	global $wpdb;
	$table_pagekey = $wpdb->prefix . 'pagekey';
	$result1 = $wpdb->get_results("SELECT pagekey FROM $table_pagekey");
	foreach($result1 as $row){
		$result[0][] = $row->pagekey;
	}
	?>
	<div class="sitemaplist" style="width:100%">
		<ul>
			<?php
			foreach ($result[0] as $productArray){ 
				if($productArray==""){
					break;
				}
			?>
			<li class="sitemaplist" style="color:#000000;" > 
				<?php $urllink = $country."/".$productArray."".$country.".html"; ?>
				<?php $productArray = ucwords(strtolower($productArray." ".$country)); ?>
				<a href="<?php echo $urllink; ?>" target="_blank"><?php echo str_replace("-"," ",$productArray); "<br />";?></a>
			</li>
			<?php
			}?>
		</ul>
	</div>
<?php
} 
?>
