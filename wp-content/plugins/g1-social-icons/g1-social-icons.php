<?php
/*
Plugin Name: G1 Social Icons
Plugin URI: http://www.bringthepixel.com
Description: Social Icons
Author: bringthepixel
Version: 1.0.0
Author URI: http://www.bringthepixel.com
License: GPLv2 or later
*/

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php
if ( ! class_exists( 'G1_Social_Icons' ) ):



class G1_Social_Icons {
    private $version = '1.0.0';
    private static $option_name = 'g1_social_icons';


    public function __construct() {
        // Standard hooks for plugins
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        register_uninstall_hook( __FILE__, array( 'G1_Social_Icons', 'uninstall' ) );


        add_filter( 'plugins_url', array( $this, 'fix_plugin_url_symlink' ), 10, 3 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

        if ( is_admin() ) {
            require_once( plugin_dir_path( __FILE__ ) . 'admin.php' );
        } else {
            require_once( plugin_dir_path( __FILE__ ) . 'front.php' );
        }
    }


    public function enqueue() {
        wp_register_style(
            'g1-social-icons',
            trailingslashit( $this->get_plugin_dir_url() ) . 'css/main.css',
            array(),
            $this->get_version()
        );

        wp_enqueue_style( 'g1-social-icons' );
    }


    public function fix_plugin_url_symlink( $url, $path, $plugin ) {
        if ( strstr( $plugin, basename(__FILE__) ) )
            return str_replace( dirname(__FILE__), '/' . basename( dirname( $plugin ) ), $url );

        return $url;
    }

    public function get_plugin_dir_path() {
        return plugin_dir_path( __FILE__ );
    }

    public function get_plugin_dir_url() {
        return plugin_dir_url( __FILE__ );
    }

    public function get_version() {
        return $this->version;
    }

    public function get_option_name() {
        return self::$option_name;
    }

    public function activate() {

    }

    public function deactivate() {

    }

    public static function uninstall() {
        delete_option( self::$option_name );
    }

    public function get_items() {
        $result = array(
            '500px'             => '#444444',
            'aboutme'           => '#00405D',
            'alistapart'        => '#222222',
            'amazon'            => '#FF9900',
            'amazonwishlist'    => '#FF9900',
            'android'           => '#A4C639',
            'appdotnet'         => '#898D90',
            'apple'             => '#B9BFC1',
            'audioboo'          => '#AE006E',
            'aws'               => '#FF9900',
            'bebo'              => '#EE1010',
            'behance'           => '#1769FF',
            'blip'              => '#FF1919',
            'blogger'           => '#F57D00',
            'bootstrap'         => '#0088CC',
            'codepen'           => '#231F20',
            'codeschool'        => '#C68044',
            'codecademy'        => '#0088CC',
            'coderwall'         => '#3E8DCC',
            'conservatives'     => '#0087DC',
            'coursera'          => '#3A6D8E',
            'css3'              => '#0092BF',
            'delicious'         => '#3274D1',
            'designernews'      => '#1C52A2',
            'deviantart'        => '#4B5D50',
            'digg'              => '#14589E',
            'disqus'            => '#2E9FFF',
            'dribbble'          => '#EA4C89',
            'dropbox'           => '#2281CF',
            'drupal'            => '#0077C0',
            'ebay'              => '#0064D2',
            'email'             => '#666666',
            'eventstore'        => '#6BA300',
            'eventbrite'        => '#F3844C',
            'evernote'          => '#7AC142',
            'exfm'              => '#0097F8',
            'facebook'          => '#3B5998',
            'flickr'            => '#0063DB',
            'formspring'        => '#0076C0',
            'forrst'            => '#5B9A68',
            'foursquare'        => '#2398C9',
            'geeklist'          => '#8CC63E',
            'github'            => '#4183C4',
            'goodreads'         => '#5A471B',
            'google'            => '#245DC1',
            'googleplus'        => '#D14836',
            'govuk'             => '#231F20',
            'grooveshark'       => '#000000',
            'hackernews'        => '#FF6600',
            'heroku'            => '#6762A6',
            'html5'             => '#F06529',
            'imdb'              => '#F3CE00',
            'instagram'         => '#3F729B',
            'jquery'            => '#0867AB',
            'jqueryui'          => '#FEA620',
            'jsdb'              => '#DA320B',
            'jsfiddle'          => '#4679A4',
            'justgiving'        => '#78256D',
            'kickstarter'       => '#87C442',
            'klout'             => '#E24A25',
            'labour'            => '#C41230',
            'laravel'           => '#FB502B',
            'lastfm'            => '#D51007',
            'layervault'        => '#26AE90',
            'letterboxd'        => '#2C3641',
            'liberaldemocrats'  => '#F7B135',
            'linkedin'          => '#007FB1',
            'mediatemple'       => '#000000',
            'mendeley'          => '#B61F2F',
            'modernizr'         => '#D81A76',
            'myspace'           => '#008DDE',
            'nationalrail'      => '#003366',
            'newsvine'          => '#075B2F',
            'office'            => '#EB3C00',
            'orkut'             => '#ED2590',
            'outlook'           => '#0072C6',
            'path'              => '#E41F11',
            'php'               => '#6181B6',
            'pinboard'          => '#0000FF',
            'pingup'            => '#00B1AB',
            'pinterest'         => '#CB2027',
            'posterous'         => '#FFDD68',
            'protoio'           => '#40C8F4',
            'rails'             => '#A62C39',
            'readability'       => '#870000',
            'reddit'            => '#FF4500',
            'rss'               => '#FF8300',
            'simpleicons'       => '#BF1813',
            'skydrive'          => '#094AB1',
            'skype'             => '#00AFF0',
            'slideshare'        => '#009999',
            'smashingmagazine'  => '#E95C33',
            'soundcloud'        => '#FF6600',
            'spotify'           => '#80B719',
            'squarespace'       => '#000000',
            'stackexchange'     => '#1F5196',
            'stackoverflow'     => '#F47920',
            'stumbleupon'       => '#EB4924',
            'superuser'         => '#2DABE2',
            'ted'               => '#FF2B06',
            'trakt'             => '#222222',
            'treehouse'         => '#7FA24C',
            'tripadvisor'       => '#589442',
            'tumblr'            => '#2C4762',
            'twitter'           => '#39A9E0',
            'typo3'             => '#FF8700',
            'viadeo'            => '#F4982B',
            'vimeo'             => '#44BBFF',
            'vine'              => '#00A47A',
            'visualstudio'      => '#68217A',
            'w3c'               => '#0066B0',
            'windows'           => '#00BDF6',
            'wordpress'         => '#21759B',
            'yahoo'             => '#731A8B',
            'yelp'              => '#C93C27',
            'youtube'           => '#CD332D',
        );

        return $result;
    }
}
endif;

function G1_Social_Icons() {
    static $instance = null;

    if ( null === $instance )
        $instance = new G1_Social_Icons();

    return $instance;
}
// Fire in the hole :)
G1_Social_Icons();