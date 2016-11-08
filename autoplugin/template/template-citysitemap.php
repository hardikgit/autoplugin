<?php
/*
Template Name: COUNTRY Sitemap
*/
?>
<?php 
    $flag = "autositemappg";
    get_header(); 
?>
<style>
#archivebox {
  width: 50%;
  float: left;
}
</style>
<div class="l-submain">
	<div class="l-submain-h g-html i-cf">
		<div class="">
			<div id="archivebox">
			<h1>City Sitemap Of <?php echo ucwords(str_replace("-"," ",$state)) ?></h1>   
				<?php  
				city_sitemap_html($state,$country); ?>
			</div>
			<div id="archivebox">
			<h1>Page Of <?php echo ucwords(str_replace("-"," ",$state)) ?> Sitemap</h1>   
				<?php  
				state_page_html($state,$country); ?>
			</div>
		</div>
	</div>
<?php //include (TEMPLATEPATH . "/auto/sidebar/sidebar.php"); ?>
</div>
<?php
get_footer();
?>
