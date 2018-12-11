<?php
class WP_gltf
{
  static $add_script = false;
    
  // Constructor
  public function __construct()
  {
    add_shortcode( '3D', array('WP_gltf', 'shortcode_gltf') );
    add_filter('mime_types',array('WP_gltf', 'add_custom_mime_types'));
    add_action('init', array(__CLASS__, 'register_scripts'));
    add_action('wp_footer', array(__CLASS__, 'print_scripts'));
    add_filter( 'ajax_query_attachments_args', array(__CLASS__,'show_model_attachments'), 10, 1 );
  }

   function show_model_attachments( $query = array() ) {
     $query['post_mime_type'] = array('image', 'text', 'model');
     return $query;
    }
	
   function add_custom_mime_types($mimes) {
     return array_merge($mimes,array (
	'glb'   => 'model/x-binary',
        'bin'   =>'application/octet-stream',
	));
	}
		
   public function register_scripts() {
     wp_register_script('threejs', plugins_url('js/three.js', __FILE__), array(),'1.1', false);
     wp_register_script('gltf-loader', plugins_url('js/loaders/GLTFLoader.js', __FILE__), array(),'1.1', false);
     wp_register_script('orbital', plugins_url('js/controls/OrbitControls.js', __FILE__), array(),'1.1', false);
     wp_register_script('wpgltf', plugins_url('js/gltf-model-viewer.js', __FILE__), array(),'1.1', false);
   }
	
   public function print_scripts() {
     if ( ! self::$add_script )
       return;

     wp_print_scripts('my-script');
     wp_print_scripts('threejs');
     wp_print_scripts('orbital');
     wp_print_scripts('gltf-loader');
     wp_print_scripts('wpgltf');
   }
	
	
   // The GLTF shortcode
   public function shortcode_gltf($atts, $content = null) {
     global $wpdb, $post;
     self::$add_script = true;
     $result = $wpdb->get_col($wpdb->prepare("SELECT guid FROM $wpdb->posts WHERE guid LIKE '%%%s' and post_parent=%d;", $atts['model'], $post->ID ));
       if (count($result)==null) {
         $result = $wpdb->get_col($wpdb->prepare("SELECT guid FROM $wpdb->posts WHERE guid LIKE '%%%s';", $atts['model'] ));
	}
	if (count($result)==null) {
          $model = $atts['model'];
	}
	else {
          $model = $result[0];
        }
		
    	$directional = explode(':', self::get($atts['directional'], '1,1,1:ddeeff'));
    	
		$options = array(
    		'width' => self::get($atts['width'], '300'),
    		'height' => self::get($atts['height'], '300'),
    		'background' => hexdec(self::get($atts['background'], 'ddeeff')),
    		'opacity' => self::get($atts['opacity'], 1),
    		'modelPosition' => self::createVector($atts['modelposition'], '1,1,1'),
    		'modelScale' => self::createVector($atts['modelscale'], '1,1,1'),
    		'ambient' => hexdec(self::get($atts['ambient'], '000000')),
    		'directionalPosition' => self::createVector($directional[0]),
    		'directionalColor' => hexdec(self::get($atts['directional'], 'ffffff')),
    		'cssClass' => self::get($atts['class']),
    		'cssStyle' => self::get($atts['style']),
    		'id' => self::get($atts['id'], 'stage'),
    		'fps' => self::get($atts['fps'], 30),
		    'camera' => self::createVector($atts['camera'], '50,50,30'),
    		'fov' => self::get($atts['fov'], 50),
		    'material' => self::get($atts['material']), 
	    );		
		ob_start();
		require('output.php');
		return ob_get_clean(); 
	}
	
	private static function get(&$var, $default=null) {
	    return isset($var) ? $var : $default;
	}	
	
	private static function createVector(&$string, $default="0,0,0") {
	    $string = self::get($string, $default);
	    $array = explode(',', $string);
	    $result = array();
	    foreach ($array as $coord)
	      $result[] = (int) $coord;
	    return $result;
	}
	
	
}
$wpgltf = new WP_gltf();
