<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://blog.thepowers.us
 * @since      1.0.0
 *
 * @package    Churches_Map
 * @subpackage Churches_Map/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Churches_Map
 * @subpackage Churches_Map/public
 * @author     Bradley Powers <bradley@thepowers.us>
 */
class Churches_Map_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Churches_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Churches_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/churches-map-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Churches_Map_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Churches_Map_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_script( $this->plugin_name.'google-maps', '//maps.googleapis.com/maps/api/js?key=AIzaSyCh8OOUmwjEkt5cv53jKS4mS2uOCR4K3F8&callback=gmaps_results_initialize', array('jquery'), $this->version, false);
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/churches-map-public.js', array( 'jquery' ), $this->version, false );
                
	}
        
        public function display_map( $args ) {

            function parseToXML($htmlStr) {
                $xmlStr=str_replace('<','&lt;',$htmlStr);
                $xmlStr=str_replace('>','&gt;',$xmlStr);
                $xmlStr=str_replace('"','&quot;',$xmlStr);
                $xmlStr=str_replace("'",'&#39;',$xmlStr);
                $xmlStr=str_replace("&",'&amp;',$xmlStr);
                return $xmlStr;
            }
            
            // Start XML file, create parent node
            $xmlstring = '<?xml version="1.0"?><markers>';

            global $wpdb;
            $sql = "SELECT * FROM wp_pods_church";
            $result = $wpdb->get_results($sql);
            if(!empty($result)) 
			{
                //$locations = array();
                //echo "Listing churches<br />";
                foreach($result as $row) 
				{
                    
                    $latlong = explode(",",$row->geocode);
                    
                    if(count($latlong) < 2 || empty($latlong[0]) || empty($latlong[1])) {
                        //echo "Lat and Long not provided<br />";
                        //echo "Geocode = " . $row->geocode . "<br />";
                        //print_r($latlong);
                        //echo "<br />";
                    } else {
						// Add to XML document node
                    	$xmlstring .= '<marker ';
                    	$xmlstring .= 'id="' . $row->id . '" ';
                    	$xmlstring .= 'name="' . parseToXML($row->name) . '" ';
                    	$xmlstring .= 'address="' . parseToXML($row->address) . '" ';
                    	$xmlstring .= 'city="' . $row->city .'" ';
                    	$xmlstring .= 'postcode="' . $row->zip_code . '" ';
                    	$xmlstring .= 'phone="' . $row->office_phone . '" ';
                    	$xmlstring .= 'website="' . $row->website . '" ';
                    	$xmlstring .= 'lat="' . $latlong[0] . '" ';
                    	$xmlstring .= 'lng="' . $latlong[1] . '" ';
                    	$xmlstring .= '/>';
					}
                }
            } else 
			{
                echo "No churches listed<br />";
            }
            
            // End XML file
            $xmlstring .= '</markers>';
            // Open or create a file (this does it in the same dir as the script)
            $my_file = fopen("churches.xml", "w");

            // Write the string's contents into that file
            fwrite($my_file, $xmlstring);

            // Close 'er up
            fclose($my_file);

            $output = '<div id="map-canvas"></div><!-- #map-canvas -->';
            
            return $output;
            
        }
}
