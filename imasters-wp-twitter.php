<?php
/*
Plugin Name: iMasters WP Twitter
Plugin URI: http://code.imasters.com.br/wordpress/plugins/imasters-wp-twitter/
Description: With iMasters WP Twitter you can add a Retweet button to yours WordPress posts and update your Twitter status when publish some post.
Author: Apiki
Version: 0.1.3
Author URI: http://apiki.com/
*/

/*  Copyright 2009  Apiki (email : leandro@apiki.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 *
 */
class iMasters_WP_Twitter {
    /**
     *
     */
    function iMasters_WP_Twitter()
    {
        //install the options in DB
        add_action( 'activate_imasters-wp-twitter/imasters-wp-twitter.php', array( &$this, 'install' ) );
        //add the menu IWP-Retweet in admin
        add_action( 'admin_menu', array( &$this, 'menu' ) );
        //add Polls Header
        add_action( 'wp_head', array( &$this, 'iwptw_header' ) );
        //add new post in twitter
        add_action( 'publish_post', array( &$this, 'post_to_twitter' ) );
        //Define translation for admin
        add_action( 'init', array( &$this, 'textdomain' ) );
        //Verify if user is imasters
        add_action( 'init', array( &$this, 'verify_user' ) );
        //Call the function to insert the JavaScript for admin
        add_action( 'wp_print_scripts', array( &$this, 'scripts' ) );
        // Start this plugin once all other files and plugins are fully loaded
        add_action( 'plugins_loaded', array(&$this, 'after_plugins_loaded'));
    }
    /**
     *
     */
    function install()
    {
        add_option( 'imasters_wp_retweet_user', 'twitter-username' );
        add_option( 'imasters_wp_retweet_password', 'insertyourpass', 'yes' );
        add_option( 'imasters_wp_retweet_message', 'Post: [title] [shorturl]', 'yes' );
        add_option( 'imasters_wp_retweet_btnaut' , 0 );
    }

    /**
     * Create menu in Wordpress admin sidebar
     */
    function menu()
    {
        add_menu_page( 'iMasters WP Twitter', 'iMasters WP Twitter', 'manage_iwptw', 'imasters-wp-twitter/imasters-wp-twitter-settings.php', '' , plugins_url( 'imasters-wp-twitter/assets/images/imasters.png' ) );
        add_submenu_page( 'imasters-wp-twitter/imasters-wp-twitter-settings.php', __('Settings','iwptw-retweet'), __('Settings','iwptw-retweet'), 'manage_iwptw', 'imasters-wp-twitter/imasters-wp-twitter-settings.php' );
        add_submenu_page( 'imasters-wp-twitter/imasters-wp-twitter-settings.php', __('Uninstall','iwptw-retweet'), __('Uninstall','iwptw-retweet'), 'manage_iwptw', 'imasters-wp-twitter/imasters-wp-twitter-uninstall.php' );
    }

    /**
     *
     * @global <type> $text_direction
     */
    function iwptw_header()
    {
        $user_name = get_option( 'imasters_wp_retweet_user' );
        if( !empty( $user_name ) ){
            if( ($user_name != 'twitter-username' ) )
                {
                    $iwptw_btn = plugins_url( 'imasters-wp-twitter/assets/images/iwptw_btn.png' );

                    echo '<!--Begin style design by imasters-wp-twitter  -->'."\n";
                        global $text_direction;
                        echo '<style type="text/css">'."\n";
                            echo '.iwptw-retweet-button span { display: block; text-indent: -2000em; }' . "\n";
                            echo '.iwptw-retweet-button { outline: none; display: block; width: 110px; height: 30px; background:url(' . $iwptw_btn .') no-repeat 0 0 ; }' . "\n";
                            echo '.iwptw-retweet-button:hover, .iwptw-retweet-button:focus { background:url(' . $iwptw_btn . ') no-repeat 0 -30px; }' . "\n";
                        echo '</style>'."\n";
                    echo '<!-- End style design by imasters-wp-twitter  -->'."\n";
                }
        }
    }

    /**
     *
     * @global native wordpress variable $post referer to permanlink from post
     * @param <type> $content
     * @return String Insert in content the retweet button
     */
    function iwptw_button($content)
    {
        global $post;
        $user_name = get_option( 'imasters_wp_retweet_user' );
        if( !empty( $user_name ) ){
            if( $user_name <> 'twitter-username' )
            {
                $longUrl = get_permalink($post->ID);
                $twitterUserName = get_option( 'imasters_wp_retweet_user' );
                $button = sprintf( '<a href="http://zip.li/api?method=retweet&amp;longUrl=%s&amp;twitterUsername=%s" class="iwptw-retweet-button"><span>%s</span></a>',
                    $longUrl,
                    $twitterUserName,
                    __( 'Retweet this post', 'iwptw-retweet' )
                );

                if( get_option( 'imasters_wp_retweet_btnaut' ) ) :
                    return $content . $button;
                else :
                    return $content;
                endif;
            }
        }
    }

    /**
     *
     *Create the textdomain for translation language
     */
    function textdomain()
    {
        load_plugin_textdomain( 'iwptw-retweet',false, 'wp-content/plugins/imasters-wp-twitter/assets/languages' );
    }

    function post_to_twitter( $postID )
    {
        $user_name = get_option( 'imasters_wp_retweet_user' );
        if( !empty( $user_name ) ){
            if( $user_name <> 'twitter-user-name' )
            {
                $post = get_post( $postID );
                $message = $this->retweet_get_message( $postID );
                $this->post_retweet( get_option( 'imasters_wp_retweet_user' ), get_option( 'imasters_wp_retweet_password' ), $message );
            }
        }
    }

    function retweet_get_message( $postID )
    {
            require_once( ABSPATH . 'wp-includes/class-snoopy.php');

            global $post;

            $snoopy = new Snoopy();
            $url_post = get_permalink( $postID );
            $result = $snoopy->fetch('http://zip.li/api?longUrl='. $url_post .'');

            $proto = get_option( 'imasters_wp_retweet_message' );
            $post  = get_post( $postID );
            $proto = str_replace( "[title]", $post->post_title, $proto );
            $proto = str_replace( "[shorturl]", $snoopy->results, $proto );
            return $proto;
    }

    //Standard curl function, handles actual submission of message to twitter
    function post_retweet( $twitter_username, $twitter_password, $message )
    {
            $url = 'http://twitter.com/statuses/update.xml';
            $curl_handle = curl_init();
            curl_setopt( $curl_handle, CURLOPT_URL, "$url" );
            curl_setopt( $curl_handle, CURLOPT_CONNECTTIMEOUT, 2 );
            curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $curl_handle, CURLOPT_POST, 1 );
            curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, "status=$message&source=twitpress" );
            curl_setopt( $curl_handle, CURLOPT_USERPWD, "$twitter_username:".str_rot13( $twitter_password ) );
            $buffer = curl_exec( $curl_handle );
            curl_close( $curl_handle );
    }

    /**
    * This function insert JS in admin plugin
    */
    function scripts()
    {
        if (!empty($_GET['page']))
            if ( strpos( $_GET['page'], 'imasters-wp-twitter' ) !== false ) :
                $iwptw_scripts = filemtime( dirname( __FILE__ ) . '/assets/javascript/iwptw-backend-scripts.js' );
                wp_enqueue_script( 'iwptw.scripts', WP_PLUGIN_URL . '/imasters-wp-twitter/assets/javascript/iwptw-backend-scripts.js', array( 'jquery' ), $iwptw_scripts );
            endif;
    }

    /**
     * Show message alert from user about user name in plugin page
     */
    function check_user_iwptw()
    {
        $user_name = get_option( 'imasters_wp_retweet_user' );
        //Show message only plugins page
            if ( $user_name == 'twitter-username' ) {
                echo sprintf( "<div class = 'error'><p>%s <strong>iMasters WP Twitter</strong> %s <a href = 'admin.php?page=imasters-wp-twitter/imasters-wp-twitter-settings.php'>%s</a></p></div>" , __( 'Warning:', 'iwptw-retweet' ), __( 'Change the User Name', 'iwptw-retweet' ), __( 'Plugin Settings', 'iwptw-retweet' ) );
            }
            if( empty( $user_name ) ){
                echo sprintf( "<div class = 'error'><p>%s <strong>iMasters WP Twitter</strong> %s <a href = 'admin.php?page=imasters-wp-twitter/imasters-wp-twitter-settings.php'>%s</a></p></div>" , __( 'Warning:', 'iwptw-retweet' ), __( 'Change the User Name', 'iwptw-retweet' ), __( 'Plugin Settings', 'iwptw-retweet' ) );
            }
    }

    /**
     * Call the function after all plugins are loaded
     */
    function after_plugins_loaded()
    {
        // hook the admin notices action
        add_action( 'admin_notices', array(&$this,'check_user_iwptw') );
    }

    function verify_user()
    {
        $user_name = get_option( 'imasters_wp_retweet_user' );
        if( $user_name == 'imasters' )
        {
            update_option( 'imasters_wp_retweet_user', 'twitter-username' );
        }
        if( $user_name <> 'twitter-username' )
            //insert the retweet button on the content of blog
            add_filter( 'the_content', array( &$this,'iwptw_button' ) );
    }

}

$role = get_role('administrator');
	if(!$role->has_cap('manage_iwptw')) {
		$role->add_cap('manage_iwptw');
        }

$imasters_wp_twitter = new iMasters_WP_Twitter();
?>
