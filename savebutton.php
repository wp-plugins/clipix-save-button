<?php
/*
Plugin Name: Clipix "Save" Button
Plugin URL: http://www.clipix.com
Version: 0.1.1
Author: clipix.com
Author URL: http://www.clipix.com
Description: The "Save" Button powered by Clipix.com
*/
register_activation_hook( __FILE__, 'clipix_activate' );
add_action('admin_menu', 'Clipix_plugin_setup_menu');
wp_register_style( 'clipix_admin', plugins_url( 'css/admin.css', __FILE__ )  );

function clipix_activate() {
		$clipix_options = get_option('clipix_setting'); 
	
		$defaults = array(
		'clipix_position_ckb_top' => '1',
		'clipix_position_ckb_bottom' => '0',
		'clipix_size' => 'medium',
		'clipix_color' => 'gray',
		'language' => 'en'
		);
		foreach ( $defaults as $k => $v )
    {
        $clipix_options[$k]=$v;
    }
	/*$clipix_options = get_option('clipix_setting', $defaults);*/
	

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
function Clipix_plugin_setup_init(){ 
wp_enqueue_style('clipix_admin'); ?>
<div class="wrap">
<div class="clipix-settings-content">
 <h2><img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/clipix_logo32.png" style="vertical-align: bottom;" /> Clipix "Save" Button Settings</h2>
  <form method="post" action="options.php">
  <?php
  settings_fields('clipix_options_group');
 
  $clipix_options = get_option('clipix_setting')

  
  ?>
<table class="form-table">   
   <tbody>

   <tr>
      <th scope="row" style="vertical-align: initial;   width: 200px; text-align: left;">Button Placement</th>
      <td>
         <label for="clipix_setting[clipix_position_ckb_top]" class="clipix-checkbox-label">
		 <input name="clipix_setting[clipix_position_ckb_top]" id="clipix_setting[clipix_position_ckb_top]" type="checkbox" value="1" <?php checked('1', $clipix_options['clipix_position_ckb_top']); ?> />
         Above Content</label>
		 <label for="clipix_setting[clipix_position_ckb_bottom]" class="clipix-checkbox-label">
		 <input name="clipix_setting[clipix_position_ckb_bottom]" id="clipix_setting[clipix_position_ckb_bottom]" type="checkbox" value="1" <?php checked('1', $clipix_options['clipix_position_ckb_bottom']); ?> />
         Below Content</label>
		
      </td>
   </tr>  
   <tr> 
      <th scope="row" style="vertical-align: initial;   width: 200px; text-align: left;">Size</th>
      <td>
        <label for="clipix_setting[clipix_size][small]" class="clipix-radio-label">
      	<input  type="radio" name="clipix_setting[clipix_size]" value="small" <?php checked('small', $clipix_options['clipix_size']); ?> />Small (20px)</label>
  		<label for="clipix_setting[clipix_size][medium]" class="clipix-radio-label">
  		<input type="radio" name="clipix_setting[clipix_size]" value="medium" <?php checked('medium', $clipix_options['clipix_size']); ?> />Medium (24px)</label> 
  		<label for="clipix_setting[clipix_size][large]" class="clipix-radio-label">
  		<input type="radio" name="clipix_setting[clipix_size]" value="large" <?php checked('large', $clipix_options['clipix_size']); ?> />Large (32px)<br /></label> 
   </tr>
   <tr> 
      <th scope="row" style="vertical-align: initial;   width: 200px; text-align: left;">Color Style</th>
      <td>
      	<label for="clipix_setting[clipix_color][gray]" class="clipix-radio-label">
      	<input type="radio" name="clipix_setting[clipix_color]" value="gray" <?php checked('gray', $clipix_options['clipix_color']); ?> />Gray</label>
      	<label for="clipix_setting[clipix_color][white]" class="clipix-radio-label">
  		<input type="radio" name="clipix_setting[clipix_color]" value="white" <?php checked('white', $clipix_options['clipix_color']); ?> />White</label>  
  		<label for="clipix_setting[clipix_color][orange]" class="clipix-radio-label">
  		<input type="radio" name="clipix_setting[clipix_color]" value="orange" <?php checked('orange', $clipix_options['clipix_color']); ?> />Orange</label>
      </td>
   </tr>
      <tr> 
      <th scope="row" style="vertical-align: initial;   width: 200px; text-align: left;">Language</th>
      <td>
<select name="clipix_setting[language]" id="clipix_setting[language]"  >
<?php 
    $language = array(
        'en' => 'English',
        'es' => 'español',
        'fr' => 'français',
        'he' => 'עברית',
        'zh' => '中文',
        'it' => 'italiano',
        'tr' => 'Türkçe',
        'de' => 'Deutsch',
        'pt' => 'Português',
        'ko' => '한국어',
        'ja' => '日本語',
        'ru' => 'русский'
        
    );
    $selectedLan = esc_attr( $language );
    foreach ($language as $code => $label) {
        echo '<option value="'.$code.'"';
        if ($selectedLan == $code||$clipix_options['language']== $code) {
        	$clipix_options['language'] = $selectedLan ;
            echo ' selected="selected"';
        }
        echo '>' . $label . '</option>';
    }
?>
</select>
      </td>
   </tr>
</tbody>
</table>     
  <?php submit_button(); ?>
  </form>
</div>
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
		$result = $content;
		if(is_single() && !is_page( ) && file_exists( $fileName )){

			/*open the template file and read */
			$theFile = fopen( $fileName, "r");
			$msg = fread( $theFile, filesize( $fileName ));
			fclose( $theFile );
			$clipix_options = get_option('clipix_setting');
			$positionTop=$clipix_options['clipix_position_ckb_top'];
			$positionBottom=$clipix_options['clipix_position_ckb_bottom'];
			$color = $clipix_options['clipix_color'];
			$size = $clipix_options['clipix_size'];
			
						
			if ($msg != NULL)
			{

				$msg = str_replace("{ln}", $clipix_options['language'], $msg);
				
				switch ($color) {
				    case "gray":
				    	switch ($size)
				    		{
				    			case "small":
				    			$msg = str_replace("{opt}", 1, $msg);
				    			break;
				    			case "medium":
				    			$msg = str_replace("{opt}", 2, $msg);
				    			break;
				    			case "large":
				    			$msg = str_replace("{opt}", 3, $msg);
				    			break;							
							}
				        break;
				    case "orange":
				    	switch ($size)
				    		{
				    			case "small":
				    			$msg = str_replace("{opt}", 4, $msg);
				    			break;
				    			case "medium":
				    			$msg = str_replace("{opt}", 5, $msg);
				    			break;
				    			case "large":
				    			$msg = str_replace("{opt}", 6, $msg);
				    			break;							
							}
				        break;
				   case "white":
				    	switch ($size)
				    		{
				    			case "small":
				    			$msg = str_replace("{opt}", 7, $msg);
				    			break;
				    			case "medium":
				    			$msg = str_replace("{opt}", 8, $msg);
				    			break;
				    			case "large":
				    			$msg = str_replace("{opt}", 9, $msg);
				    			break;							
							}
				        break;
				}
				
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
			
		
	}
	return $result;
}
}
$_addClipixSaveButton = new Add_Clipix_Save_Button();

?>