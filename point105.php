<?php
/**
 * Plugin Name: POINT105 AR
 * Description: 3D plugin for wordpress
 * Author: Shruthi
 */
 
if ( ! defined( 'WPINC' ) ) 
{
  die;
} 
require_once( plugin_dir_path( __FILE__ ) . 'admin-menu.php' );
require_once( plugin_dir_path( __FILE__ ) . 'gltf-model-viewer.php');

