<?php
//-------------------------------------------------------------------------------------------------------------------------------------------------------//
//State Sitemap Html
function city_sitemap_html($state,$country){
	global $wpdb;
	$table_city = $wpdb->prefix . 'city';
	$result1 = $wpdb->get_results("SELECT city FROM $table_city WHERE state='$state'");
	foreach($result1 as $row){
		$result[0][] = $row->city;
	}
	include 'pagination.php';
	$pagination = new pagination;
	if (count($result)) {
	$productPages = $pagination->generate($result, 2);
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
						<?php $urllink = $state."/".$productArray . "sitemap.html"; ?>
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
//City Pages
function city2_page_html($city,$state,$country){
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
				<?php $urllink = $productArray."".$city.".html"; ?>
				<?php $productArray = ucwords(strtolower($productArray." ".$city)); ?>
				<a href="<?php echo $urllink; ?>" target="_blank"><?php echo str_replace("-"," ",$productArray); "<br />";?></a>
			</li>
			<?php
			}?>
		</ul>
	</div>
<?php
} 
?>
