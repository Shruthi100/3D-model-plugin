<?php
require_once( plugin_dir_path( __FILE__ ) . 'post button.php' );

/*Create Databases*/
 global $wpdb;
 $results=$wpdb->get_results("CREATE TABLE WP_THREED(FILE_NAME VARCHAR(30) UNIQUE)");
 $result=$wpdb->get_results("CREATE TABLE WP_BASE(BASE_URL VARCHAR(300) UNIQUE)");
	   
 /*Create admin menu and sub-menus*/ 
   add_action('admin_menu', 'admin_menu');	
   function admin_menu()
  {
     add_menu_page( 'WordPress 3D','POINT105 AR','manage_options','three-d','library','dashicons-editor-contract',4 );
     add_submenu_page('three-d','Settings','Base URL','manage_options','set','base_url');
     add_submenu_page('three-d','Submenu2','Add New Model','manage_options','submen','add_new');
     add_submenu_page('three-d','Submenu1','Library','manage_options','sub','library');
  }

function add_new ()
{
  require_once( plugin_dir_path( __FILE__ ) . 'add-new.php' );
}
function library()
{
  require_once( plugin_dir_path( __FILE__ ) . 'library.php' );
}
function base_url()
{
  require_once( plugin_dir_path( __FILE__ ) . 'base_url.php' );
}
