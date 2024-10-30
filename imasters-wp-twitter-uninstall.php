<!-- Uninstall iMasters WP Retweet -->
<?php
    if( !current_user_can('install_plugins')):
        die('Access Denied');
    endif;

$base_name = plugin_basename('imasters-wp-twitter/imasters-wp-twitter.php');
$base_page = 'admin.php?page='.$base_name;
if (!empty($_GET['mode']))
    $mode = trim($_GET['mode']);
else
    $mode = '';
$iwptw_settings = array('imasters_wp_retweet_user','imasters_wp_retweet_password', 'imasters_wp_retweet_message', 'imasters_wp_retweet_btnaut');

//Form Process
if( isset( $_POST['do'], $_POST['uninstall_iwptw_yes'] ) ) :
    echo '<div class="wrap">';
    ?>
    <h2><img style="margin-right: 5px;" src="<?php echo plugins_url( 'imasters-wp-twitter/assets/images/imasters32.png' )?>" alt="imasters-ico"/><?php _e('Uninstall iMasters WP Twitter', 'iwptw-retweet') ?></h2>
    <?php
    switch($_POST['do']) {
        //  Uninstall iMasters WP Twitter
        case __('Uninstall iMasters WP Twitter', 'iwptw-retweet') :
        if(trim($_POST['uninstall_iwptw_yes']) == 'yes') :
        echo '<h3>'.__( 'Options', 'iwptw-retweet').'</h3>';
        echo '<ol>';
        foreach($iwptw_settings as $setting) :
            $delete_setting = delete_option($setting);
            if($delete_setting) {
            printf(__('<li>Option \'%s\' has been deleted.</li>', 'iwptw-retweet'), "<strong><em>{$setting}</em></strong>");
            }
            else {
                printf(__('<li>Error deleting Option \'%s\'.</li>', 'iwptw-retweet'), "<strong><em>{$setting}</em></strong>");
                }
        endforeach;
        echo '</ol>';
        echo '<br/>';
        $mode = 'end-UNINSTALL';
        endif;
        break;
    }
endif;
    switch($mode) {
    //  Deactivating Uninstall iMasters WP Twitter
    case 'end-UNINSTALL':
        $deactivate_url = 'plugins.php?action=deactivate&amp;plugin=imasters-wp-twitter/imasters-wp-twitter.php';
        if(function_exists('wp_nonce_url')) {
            $deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_imasters-wp-twitter/imasters-wp-twitter.php');
        }
    echo sprintf(__('<a href="%s" class="button-primary">Deactivate iMasters WP Twitter</a> Disable that plugin to conclude the uninstalling.', 'iwptw-retweet'), $deactivate_url);
    echo '</div>';
    break;
    default:
    ?>
    <!-- Uninstall iMasters WP Twitter -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo plugin_basename(__FILE__); ?>">
        <div class="wrap">
            <h2><img style="margin-right: 5px;" src="<?php echo plugins_url( 'imasters-wp-twitter/assets/images/imasters32.png' )?>" alt="imasters-ico"/><?php _e('Uninstall iMasters WP Twitter', 'iwptw-retweet'); ?></h2>
            <p><?php _e('Uninstaling this plugin the options used by iMasters WP Twitter will be removed.', 'iwptw-retweet'); ?></p>
            <div class="error">
            <p><?php _e('Warning:', 'iwptw-retweet'); ?>
            <?php _e('This process is irreversible. We suggest that you do a database backup first.', 'iwptw-retweet'); ?></p>
            </div>
            <table>
                <tr>
                    <td>
                    <?php _e('The following WordPress Options will be deleted:', 'iwptw-retweet'); ?>
                    </td>
                </tr>
            </table>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('WordPress Options', 'iwptw-retweet'); ?></th>
                    </tr>
                </thead>
                <tr>
                    <td valign="top">
                        <ol>
                        <?php
                        foreach($iwptw_settings as $settings)
                            printf( "<li>%s</li>\n", $settings );
                        ?>
                        </ol>
                    </td>
                </tr>
            </table>
            <p>
                <input type="checkbox" name="uninstall_iwptw_yes" id="uninstall_iwptw_yes" value="yes" />
                <label for="uninstall_iwptw_yes"><?php _e('Yes. Uninstall iMasters WP Twitter now', 'iwptw-retweet'); ?></label>
            </p>
            <p>
                <input type="submit" name="do" value="<?php _e('Uninstall iMasters WP Twitter', 'iwptw-retweet'); ?>" class="button-primary" />
            </p>
        </div>
    </form>
<?php
}
?>