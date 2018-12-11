<?php
session_start();
$html='<form action = "" method="post">
       <p>BASE URL<input type="text" name="base" id="base"> </p>
	   <p><button type="submit" name="submit">Submit</button></p>
       </form>';
echo $html;
   
/*Insert Base url into Database*/
if ( isset( $_POST['submit'] ) )
{
  global $wpdb;
  $tablename = $wpdb->prefix.'base';
  $wpdb->insert( $tablename, array(
            'BASE_URL' => $_POST['base']),
            array('%s') 
        );
		
/*Display BASE URL*/
$urls= $wpdb->get_results("SELECT BASE_URL FROM wp_base");
foreach ($urls as $url)
{
  $u = $url->BASE_URL;
  $_SESSION['baseurl'] = $u;
}	
echo $_SESSION['baseurl'];
}
?>
