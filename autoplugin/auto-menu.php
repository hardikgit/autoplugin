<?php
function save_data(){
	if(isset($_POST['auto-plugin-xml-sitemap-create']) && $_POST['auto-plugin-xml-sitemap']=="Y"){
		xml_sitemap();
	}
}
add_action('init', 'save_data');

add_action( 'admin_menu', 'main_menu' );
function main_menu() {
	add_menu_page( 'Auto Plugin', 'Auto Plugin', 'manage_options', 'auto-plugin', 'auto_main_page' );
}
function auto_main_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$tabs = array( 'country' => 'Country', 'state' => 'State' ,'city'=>'City','key' => 'Key');
	
	if ( isset ( $_GET['tab'] ) ){ $current = $_GET['tab']; }else{ $current = 'country';}


	echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h1 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab".$class."' href='?page=auto-plugin&tab=$tab'>$name</a>";
	}
	echo '</h1>';
	echo '<form method="post" action="?page=auto-plugin&tab='.$current.'">';
			
			
	if($_GET['page'] == 'auto-plugin'){
		echo '<table class="form-table">';
		switch ( $current ){
			case 'country' :
				?>
				 <tr>
					<th><label for="ilc_intro">Select Country Csv:</label></th>
					<td>
						<input type="file" name="country" >
						<input type="hidden" name="auto-plugin-country" value="Y" />
					</td>
				</tr>
				<?php
			break;

			case 'state' :
			//function file_replace() {

				$plugin_dir = plugin_dir_path( __FILE__ ) . 'template/template-country.php';
				$theme_dir = get_stylesheet_directory() . '/template-country.php';

				if (!copy( $theme_dir,$plugin_dir)) {
					echo "failed to copy $plugin_dir to $theme_dir...\n";
				}
			//}
			?>
			<tr>
				<th><label for="ilc_intro">Select Country:</label></th>
					<td>
						<select name="country">
							<option>1</option>
							<option>2</option>
							<option>3</option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="ilc_intro">Select State Csv:</label></th>
					<td>
						<input type="file" name="country" >
						<input type="hidden" name="auto-plugin-country" value="Y" />
					</td>
				</tr>
				 <?php
			  break;
			  
			  case 'city' :
			  
			?>
				<tr>
					<th><label for="ilc_intro">Select City Csv:</label></th>
					<td>
						<input type="file" name="country" >
						<input type="hidden" name="auto-plugin-country" value="Y" />
					</td>
				</tr>
				 <?php
			  break;
			  
			  case 'key' :
				?>
				<tr>
					<th><label for="ilc_intro">Select Key Csv:</label></th>
					<td>
						<input type="file" name="country" >
						<input type="hidden" name="auto-plugin-country" value="Y" />
					</td>
				</tr>
				 <?php
			 
			  break;
			  
			  
			}
		echo '</table>';
	
}
	echo '<p class="submit" style="clear: both;">
			<input type="submit" name="settings_submit"  class="button-primary" value="Update Settings" />
			</p>';
	echo '</form>';
}
	
?>
