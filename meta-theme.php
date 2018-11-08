<?php
/*
    Plugin Name: Meta Theme Color Picker
    Plugin Script: meta-theme.php
    Plugin URI:
    Description: Change the meta theme color of your Wordpress site.
    Author: Stray Cat Communication
    Donate Link: https://straycat.nu/
    License: GPL
    Version: 1.0
    Author URI: https://straycat.nu/
    Text Domain: myfogp
    Domain Path: languages/
*/

/*  Copyright 2017 Stray Cat Communication (https://straycat.nu/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Adds color to meta-color
add_action('wp_head', 'wpse_43672_wp_head');

function wpse_43672_wp_head(){
    //Close PHP tags
    ?>
    <meta name="theme-color" content=<?php echo get_option( 'mt_theme_color' ) ?>>
    <?php //Open PHP tags
}

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Menu editor
// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {
    // Add a new submenu under Settings:
    add_options_page(__('Meta Theme Color','menu-test'), __('Meta Theme Color','menu-test'), 'manage_options', 'metathemecolor', 'mt_settings_page');
}

// mt_settings_page() displays the page content for the Test Settings submenu
function mt_settings_page() {

    //must check that the user has the required capability
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names
    $opt_name = 'mt_theme_color';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_theme_color';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put a "settings saved" message on the screen

?>
<div class="updated"><p><strong><?php _e('Color updated.', 'menu-test' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Meta theme color settings', 'menu-test' ) . "</h2>";

    // settings form

    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><strong><?php _e("Set your theme color", 'menu-test' ); ?></strong><br></br>
<input type="text" class="my-color-field" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>">
</p><hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
<p><i>By Stray Cat</i></p>
</div>

<?php
}
?>
