<?php
//-------------------------------------------------------------------------------------------------------------------------------------------------------//
//State Sitemap Html
function state_sitemap_html($country){
	global $wpdb;
	$table_state = $wpdb->prefix . 'state';
	$result1 = $wpdb->get_results("SELECT state FROM $table_state WHERE country='$country'");
	foreach($result1 as $row){
		$result[0][] = $row->state;
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
						<?php $urllink = $country."/".$productArray . "sitemap.html"; ?>
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
//State Pages
function state_page_html($state,$country){
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
				<?php $urllink = $state."/".$productArray."".$state.".html"; ?>
				<?php $productArray = ucwords(strtolower($productArray." ".$state)); ?>
				<a href="<?php echo $urllink; ?>" target="_blank"><?php echo str_replace("-"," ",$productArray); "<br />";?></a>
			</li>
			<?php
			}?>
		</ul>
	</div>
<?php
} 
?>
