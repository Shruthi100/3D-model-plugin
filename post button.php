<?php
session_start();
class Post_Button
{
  private $version;
  public function __construct() 
  {
    add_action( 'admin_init', array( $this, 'add_editor_buttons' ) );
    // styling
    add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
    // scripts, only load when editor is available
    add_filter( 'tiny_mce_plugins', array( $this, 'admin_scripts' ) );
  }
	
  private function get_url()
  {
    return plugin_dir_url( __FILE__ );
  }

  public function get_version() 
   {
    if ( null === $this->version ) 
   {
    $this->version = $this->get_plugin_version( __FILE__ );
   }
    return $this->version;
   }

    private function get_plugin_version( $file ) 
   {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';

      $plugin = get_plugin_data( $file, false, false );

      return isset( $plugin['Version'] ) ? $plugin['Version'] : false;
   }

     public function admin_styles() 
   {
     if ( $this->has_permissions() && $this->is_edit_screen() )
     {
     wp_enqueue_style( 'admin', $this->get_url() . '/css/admin.css', array(), $this->get_version(), 'all' );

      }
   }

     public function admin_scripts( $plugins )
    {
      if ( $this->has_permissions() && $this->is_edit_screen() ) 
      {
        wp_enqueue_script( 'admin', $this->get_url() . '/js/admin.js', array( 'jquery' ), $this->get_version() );
      }

	return $plugins;
     }

     private function is_edit_screen() 
     {
	global $pagenow;

	$allowed_screens = apply_filters( 'cpsh_allowed_screens', array( 'post-new.php', 'page-new.php', 'post.php', 'page.php', 'profile.php', 'user-edit.php', 'user-new.php' ) );

	return in_array( $pagenow, $allowed_screens );
     }

     private function has_permissions() 
     {
	return current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' );
     }

      public function add_editor_buttons() 
     {
	if ( $this->has_permissions() && $this->is_edit_screen() ) 
	{
	add_action( 'media_buttons', array( $this, 'add_shortcode_button' ), 100 );
	}
     }

      public function add_shortcode_button( $page = null, $target = null )
    {
    ?>  
	<body>
		<form>
		<input type="button"  class ="mbutton" id="mainBtn" value="ADD 3D MODEL"></form>
		
		<!-- The Modal -->
		<div id="mainModal" class="modal">
		<!-- Modal content -->
		<center>
		 <div class="modal-content">
	        <div class = "modal-header">
		    <span class="close">&times;</span>
	        </div>
		<div class = "modal-body">	
           <?php
            global $wpdb;
			
	/*Display model list*/
$results = $wpdb->get_results ("SELECT * FROM wp_threed;");
echo '<table width="800" id="csstable"><tr><th> FILE NAME </th></tr>';
foreach ( $results as $result ) 
{
  echo '<tr height="30"><td width="800">'.$result->FILE_NAME.'</td><td width="800"><input type="button" class="selects" value="SELECT"  onclick="urlClick('."'".$_SESSION['baseurl'].$result->FILE_NAME."'".')"></td></tr>';
}
echo '</table>';
?>
</div>
	    </div>

	    </div>
	    </body>
<?php
}

}

new Post_Button();?>
<?php
