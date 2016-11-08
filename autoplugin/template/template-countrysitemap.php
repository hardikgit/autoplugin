<?php
/*
Template Name: COUNTRY Sitemap
*/
?>
<?php 
    $flag = "autositemappg";
    get_header(); 
?>
<div class="l-submain">
	<div class="l-submain-h g-html i-cf">
		<div class="">
			<div id="archivebox">
			<h1><?php the_title(); ?></h1>        
				<?php $s = the_title('','',false); ?>
				<?php  country_sitemap_html(); 
				echo $_SERVER['REQUEST_URI']."<br>";
				echo get_template_directory_uri()."<br>";
				echo get_template_directory()."<br>";
				
				?>
				
			</div><!--/archivebox-->
		</div>
	</div>
<?php //include (TEMPLATEPATH . "/auto/sidebar/sidebar.php"); ?>
</div>
<?php
get_footer();
?>
