<?php
/*
Plugin Name: Post Announcement
Plugin URI: http://buffercode.com/wordpress-post-announcement-plugin/
Description: Through this plugin, user can able to show the announcement or notice to users based on each post.
Version: 1.0
Author: vinoth06
Author URI: http://buffercode.com/
License: GPLv2
*/

include('post-announcement-menu.php');
register_activation_hook(__FILE__,'buffercode_post_announcement_install');

function buffercode_post_announcement_install(){
add_option('buffercode_post_announcement_custom_heading','Announcement List');
add_option('buffercode_post_announcement_bg_color','');
add_option('buffercode_post_announcement_font_color','');
}

function buffercode_post_announcement() {
wp_enqueue_script( 'captcha-script',plugins_url('js\jscolor.js',__FILE__) );
}

add_action( 'admin_init', 'buffercode_post_announcement',1 );

function buffercode_post_announcement_mode() {
# placing our meta box in three locations namely attachment, post and page.
    $buffercode_PA_location = array( 'attachment', 'post', 'page');

    foreach ( $buffercode_PA_location as $buffercode_PA_locations ) {
       add_meta_box(
            'buffercode_post_announcement_mode_id',
            __( 'Post Announcement', 'buffercode_post_announcement_domain' ),
            'buffercode_post_announcement_mode_post',
            $buffercode_PA_locations
        );
    }
}
#registering our meta boxes in admin dash board.
add_action( 'add_meta_boxes', 'buffercode_post_announcement_mode' );


function buffercode_post_announcement_mode_post( $post ) {
  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'buffercode_post_announcement_post', 'buffercode_post_announcement_mode_nonce' );

  /*
   Get the value previous value from the database to display in the admin dashboard
   */

 ?>
<textarea placeholder="Make your Announcement Here.." name="buffercode_post_announcement_summary" rows="5" cols="82"><?php echo html_entity_decode(get_post_meta( $post->ID, 'buffercode_post_announcement_summary', true )); ?></textarea>
<p><b>No HTML Please..</b></p>
  <?php

  }

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function buffercode_post_announcement_save( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['buffercode_post_announcement_mode_nonce'] ) )
    return $post_id;

  $nonce = $_POST['buffercode_post_announcement_mode_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'buffercode_post_announcement_post' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  
  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['buffercode_post_announcement_summary'] );
  
  // Update the meta field in the database.
  update_post_meta( $post_id, 'buffercode_post_announcement_summary', $mydata );
  
 }
add_action( 'save_post', 'buffercode_post_announcement_save' );


function buffercode_post_announcement_display_logic($content){
global $post;
$buffercode_post_announcement_font_color=get_option('buffercode_post_announcement_font_color');
$buffercode_post_announcement_bg_color=get_option('buffercode_post_announcement_bg_color');
$buffercode_post_announcement_custom_heading=get_option('buffercode_post_announcement_custom_heading');
$buffercode_post_announcement_summary = get_post_meta( $post->ID, 'buffercode_post_announcement_summary', true );
if(is_single()){
$content.= '<!-- Buffercode.com Post Announcement --><div style="padding:5px; color:#'.$buffercode_post_announcement_font_color.';background:#'.$buffercode_post_announcement_bg_color.';"><b><!-- Buffercode.com Post Announcement -->'.$buffercode_post_announcement_custom_heading.'</b><br><marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">'.$buffercode_post_announcement_summary.'</marquee></div>';
} 
	  return $content;
}
add_filter('the_content','buffercode_post_announcement_display_logic');

?>