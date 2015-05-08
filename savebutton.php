<?php
/*
Plugin Name: Clipix "Save" Button
Plugin URL: http://www.clipix.com
Version: 0.1.0
Author: clipix.com
Author URL: http://www.clipix.com
Description: The "Save" Button powered by Clipix.com
*/
register_activation_hook( __FILE__, 'clipix_activate' );
add_action('admin_menu', 'Clipix_plugin_setup_menu');
function clipix_activate() {

		$defaults = array(
		'clipix_position_ckb_top' => '1',
		);
	add_option('clipix_setting', $defaults);


}

function Clipix_plugin_setup_menu(){
		//create new top-level menu
        add_menu_page( 'Clipix Plugin Page', 'Save Button', 'manage_options', 'test-plugin', 'Clipix_plugin_setup_init' , plugins_url( 'assets/Clipix_logo16.png', __FILE__ ) );
		//call register settings function
		add_action( 'admin_init', 'Clipix_settings' );
}

function Clipix_settings() {
	//register our settings
	register_setting('clipix_options_group', 'clipix_setting', 'clipix_validate');
	
} 

function clipix_validate($input) {
  return array_map('wp_filter_nohtml_kses', (array)$input);
} 
function Clipix_plugin_setup_init(){ ?>
<div>
  <h2>Clipix "Save" Button position</h2>
  <form method="post" action="options.php">
  <?php
  settings_fields('clipix_options_group');
 
  $clipix_options = get_option('clipix_setting')

  
  ?>
<table>   
   <tbody>

   <tr>
      <th scope="row" style="vertical-align: initial;   width: 200px; text-align: left;">Button Placement</th>
      <td>
         <label for="clipix_setting[clipix_position_ckb_top]" class="pib-checkbox-label">
		 <input name="clipix_setting[clipix_position_ckb_top]" id="clipix_setting[clipix_position_ckb_top]" type="checkbox" value="1" <?php checked('1', $clipix_options['clipix_position_ckb_top']); ?> />
         Above Content</label><br/>
		 <label for="clipix_setting[clipix_position_ckb_bottom]" class="pib-checkbox-label">
		 <input name="clipix_setting[clipix_position_ckb_bottom]" id="clipix_setting[clipix_position_ckb_bottom]" type="checkbox" value="1" <?php checked('1', $clipix_options['clipix_position_ckb_bottom']); ?> />
         Below Content</label>
		
      </td>
   </tr>
</tbody>
</table>     
  <?php submit_button(); ?>
  </form>
</div>
<?php } 



class Add_Clipix_Save_Button {
	public function __construct() {
		if( !function_exists("add_Clipix_Save_Button")){
			add_filter("the_content", array($this, "add_Clipix_Save_Button"));
		}
	}
	
	
	function add_Clipix_Save_Button($content)
	{
		/*template file */
		$fileName = dirname(__FILE__) ."/Clipix_Save_Button_Template.txt";
		
		if(is_single() && !is_page( ) && file_exists( $fileName )){

			/*open the template file and read */
			$theFile = fopen( $fileName, "r");
			$msg = fread( $theFile, filesize( $fileName ));
			fclose( $theFile );
			$clipix_options = get_option('clipix_setting');
			$positionTop=$clipix_options['clipix_position_ckb_top'];
			$positionBottom=$clipix_options['clipix_position_ckb_bottom'];
			$result = $content;
			if( $positionTop == '1')
			{
				$result = stripslashes( $msg ) . $content;
				
			}
			/*append the text file contents to the end of `the_content` */
			if( $positionBottom == '1')
			{ 
				$result = $result . stripslashes( $msg );
			}
		}
			return $result;
		
	}
}

$_addClipixSaveButton = new Add_Clipix_Save_Button();

?>