<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_menu', 'buffercode_post_announcement_menu');

function buffercode_post_announcement_menu() {

	add_options_page( 'Post Announcement', 'Post Announcement', 'manage_options', __FILE__, 'buffercode_post_announcement_settings' );

	//call register settings function
	add_action( 'admin_init', 'buffercode_post_announcement_register_settings' );
}

function buffercode_post_announcement_register_settings() {
	//register both settings Text Field and Combo box
	register_setting( 'buffercode-post-announcement-settings-group', 'buffercode_post_announcement_custom_heading' );
	register_setting( 'buffercode-post-announcement-settings-group', 'buffercode_post_announcement_bg_color' );
	register_setting( 'buffercode-post-announcement-settings-group', 'buffercode_post_announcement_font_color' );
}

function buffercode_post_announcement_settings() {
?>
	<!-- Buffercode.com Post Announcement Selection --> 
<div class="wrap">
<h2>Post Announcement Setting Page</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'buffercode-post-announcement-settings-group' ); ?>
    <?php do_settings_sections( 'buffercode-post-announcement-settings-group' );?>
	<!-- Buffercode.com Post Announcement Selection --> 
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Custom Announcement Title</th>
        <td><input type="text" maxlength="25" name="buffercode_post_announcement_custom_heading" value="<?php echo get_option('buffercode_post_announcement_custom_heading'); ?>" /></td>
		</tr>
          	
		<tr valign="top">
        <th scope="row">Background Color</th>
        <td><input type="text" class="color {required:false,pickerClosable:true}"  name="buffercode_post_announcement_bg_color"  value="<?php echo get_option('buffercode_post_announcement_bg_color'); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Font Color</th>
        <td><input type="text" class="color {required:false,pickerClosable:true}"  name="buffercode_post_announcement_font_color"  value="<?php echo get_option('buffercode_post_announcement_font_color'); ?>" /></td>
        </tr>
		
		
		 <tr valign="top">
        <th scope="row">Designed by - <a href="http://buffercode.com">Buffercode</a></th>
        </tr>
    </table>
	<!-- Buffercode.com Post Announcement Selection --> 
	
        <?php submit_button(); ?>

</form>
</div>
<?php } ?>