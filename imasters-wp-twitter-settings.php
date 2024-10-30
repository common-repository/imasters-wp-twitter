<?php
    //Verify if current user can access the settings from plugin
    if(!current_user_can('manage_iwptw')){
        die('denied access!');
    }

$show_btn = get_option('imasters_wp_retweet_btnaut');

if ( isset( $_POST['submit'] ) ) :
    update_option( 'imasters_wp_retweet_user', $_POST[ 'imasters_wp_retweet_user' ] );
    update_option( 'imasters_wp_retweet_password', str_rot13( $_POST[ 'imasters_wp_retweet_password' ] ) );
    update_option( 'imasters_wp_retweet_btnaut', $_POST[ 'imasters_wp_retweet_btnaut' ] );
?>
<div class="updated"><p><strong><?php _e('Settings Updated', 'iwptw-retweet' ); ?></strong></p></div>
<?php endif; ?>

<div class="wrap">
    <form name="iwptw_twitter_form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
	<h2><img style="margin-right: 5px;" src="<?php echo plugins_url( 'imasters-wp-twitter/assets/images/imasters32.png' )?>" alt="imasters-ico"/><?php _e('Settings', 'iwptw-retweet'); ?></h2>
	<table class="form-table">
            <tbody>
             <tr valign="top">
                <th scope="row">
                    <label for="imasters_wp_retweet_btnaut"><?php _e('Retweet Button:', 'iwptw-retweet'); ?></label>
                </th>
                <td>
                    <select name="imasters_wp_retweet_btnaut" id="imasters_wp_retweet_btnaut">
                        <option value="0"<?php selected('0', $show_btn); ?>><?php _e('No', 'iwptw-retweet'); ?></option>
                        <option value="1"<?php selected('1', $show_btn); ?>><?php _e('Yes', 'iwptw-retweet'); ?></option>
                    </select>
                    <span class="description"><?php _e('Retweet button under the post that sends short post url to twitter','iwptw-retweet'); ?>.</span>
                </td>
             </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="imasters_wp_retweet_user"><?php _e('Twitter Username','iwptw-retweet'); ?> </label>
                    </th>
                    <td>
                        @<input type="text" name="imasters_wp_retweet_user" id="imasters_wp_retweet_user" value="<?php echo get_option( 'imasters_wp_retweet_user' ); ?>" />
                        <br/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="imasters_wp_retweet_password"><?php _e('Twitter Password','iwptw-retweet'); ?> </label>
                    </th>
                    <td>
                        <input id="imasters_wp_retweet_password" type="password" name="imasters_wp_retweet_password" value="<?php echo str_rot13( get_option( 'imasters_wp_retweet_password' ) ); ?>" />
                        <span class="description"><?php _e( '(If informed, sends a message for Twitter when a post is published)', 'iwptw-retweet' ); ?>.</span>
                        <br/>
                    </td>
                </tr>
            </tbody>
        </table>
	<p class="submit">
            <input class="button-primary" type="submit" name="submit" value="<?php _e( 'Save', 'iwptw-retweet' ) ?>" />
	</p>
    </form>
</div>