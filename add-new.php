<?php
$html='<form action = "" method="post">
    <p>FILE NAME 
    <input type="text" autocomplete="off" name="f_name" id="f_name"> </p>
    <p><button type="submit" name="submit">Submit</button></p>
    </form>';
echo $html;

/*Check the validity of URL*/
if ( isset( $_POST['submit'] ) )
{
  global $wpdb;
  $rows = $wpdb->get_results ("SELECT BASE_URL FROM wp_base");
  if(count($rows)==0)
  {
    echo "Please set Base URL";
  }
  else
  {
    foreach($rows as $row)
    {	
      $test = $row->BASE_URL;
      $url = $test.$_POST['f_name'];
      $headers = @get_headers($url);
      if(strpos($headers[0],'404') === false)
      {
        echo "  URL is valid";
        $tablename = $wpdb->prefix.'threed';
        $wpdb->insert( $tablename, array(
	  'FILE_NAME' => $_POST['f_name']),
           array('%s') 
           );
      }
      else
       {
        echo "URL Not valid";
       }
     }
		 
   }
 }
	
