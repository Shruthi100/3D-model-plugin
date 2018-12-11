
<?php
global $wpdb;
/*Delete the model*/
if ( isset( $_POST['submit'] ) )
{
 $tablename = $wpdb->prefix.'threed';
 $wpdb->delete( $tablename,array('FILE_NAME' => $_POST['file_name']));
}

/*Display the list of models*/
$results = $wpdb->get_results ("SELECT * FROM wp_threed;");
if(count($results)==0)
{
  echo "<center><h1>PLEASE ADD A NEW MODEL USING THE ADD NEW MODEL SUBMENU</h1>";
}
else 
{
  echo '<center><table class="box" width="700" id="cssTable"><tr>
		<th> MODEL NAME </th>
		<th> DELETE </th></tr>
			';
foreach ( $results as $result )
{
  echo '<tr height="30"><td width="800">'.$result->FILE_NAME.'</td><td width="800"><form action="" method="post"><input type="text" class="hidden" value="'.$result->FILE_NAME.'" name="file_name"><input type="submit" name="submit" class="deletes" value="DELETE"></form></td></tr>';
}
echo '</table>';
		 }
/*Styling for the table*/
echo "
<style>
table, th, td {
    border: 1px solid black;
}
.box {
    height:100px;
    background-color:#ADD8E6;
    position:fixed;
    margin-left:-250px; /* half of width */
    margin-top:-200px;  /* half of height */
    top:50%;
    left:50%;
}
#cssTable td 
{
    text-align:center; 
    vertical-align:middle;
}
</style>
";


