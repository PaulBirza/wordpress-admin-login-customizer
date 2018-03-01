<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( version_compare( $GLOBALS['wp_version'], '3.5', '<' ) ) {
    return;
}

// add a new admin menu item
function wpcustomize_add_pages() {
    // Add a new submenu under Settings:
    add_options_page(__('Customize','wp-customize-menu'), __('Customize','wp-customize-menu'), 'manage_options', 'settings', 'wpcustomize_settings_page');
}
add_action('admin_menu', 'wpcustomize_add_pages');

// wpcustomize_settings_page() displays the page content for the Test settings submenu
function wpcustomize_settings_page() {
    //must check that the user has the required capability
    if (!current_user_can('manage_options')) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    // variables for the field and option names
    $opt_name = 'wpcustomize_admin_footer_contents';
    $hidden_field_name = 'wpcustomize_submit_hidden';
    $data_field_name = 'wpcustomize_admin_footer_contents';
    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
    // set default checkbox values
    $wpcustomize_hide_register_forgot_links = ( isset( $_POST['wpcustomize_hide_register_forgot_links'] ) && $_POST['wpcustomize_hide_register_forgot_links'] == "1" ? "1" : "0" );
    $wpcustomize_hide_back_link = ( isset( $_POST['wpcustomize_hide_back_link'] ) && $_POST['wpcustomize_hide_back_link'] == "1" ? "1" : "0" );
    $wpcustomize_remember_me_by_default = ( isset( $_POST['wpcustomize_remember_me_by_default'] ) && $_POST['wpcustomize_remember_me_by_default'] == "1" ? "1" : "0" );
    $wpcustomize_remove_login_shake = ( isset( $_POST['wpcustomize_remove_login_shake'] ) && $_POST['wpcustomize_remove_login_shake'] == "1" ? "1" : "0" );
    $wpcustomize_admin_login_redirect = ( isset( $_POST['wpcustomize_admin_login_redirect'] ) && $_POST['wpcustomize_admin_login_redirect'] == "1" ? "1" : "0" );
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Save the posted value in the database
        update_option('wpcustomize_admin_page_title', htmlentities(stripslashes($_POST['wpcustomize_admin_page_title'])));
        update_option('wpcustomize_admin_logo_image_url', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_image_url'])));
        update_option('wpcustomize_admin_logo_link_url', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_link_url'])));
        update_option('wpcustomize_admin_logo_title', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_title'])));
        update_option('wpcustomize_admin_logo_width', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_width'])));
        update_option('wpcustomize_admin_logo_height', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_height'])));
        update_option('wpcustomize_admin_logo_area_width', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_area_width'])));
        update_option('wpcustomize_admin_logo_area_height', htmlentities(stripslashes($_POST['wpcustomize_admin_logo_area_height'])));
        update_option('wpcustomize_admin_bgcolor', htmlentities(stripslashes($_POST['wpcustomize_admin_bgcolor'])));
        update_option('wpcustomize_admin_linkcolor', htmlentities(stripslashes($_POST['wpcustomize_admin_linkcolor'])));
        update_option('wpcustomize_admin_linkhovercolor', htmlentities(stripslashes($_POST['wpcustomize_admin_linkhovercolor'])));
        update_option('wpcustomize_admin_loginstyles', htmlentities(stripslashes($_POST['wpcustomize_admin_loginstyles'])));
        update_option('wpcustomize_admin_footer_contents', htmlentities(stripslashes($_POST['wpcustomize_admin_footer_contents'])));
        update_option('wpcustomize_hide_register_forgot_links', htmlentities(stripslashes($wpcustomize_hide_register_forgot_links)));
        update_option('wpcustomize_hide_back_link', htmlentities(stripslashes($wpcustomize_hide_back_link)));
        update_option('wpcustomize_remember_me_by_default', htmlentities(stripslashes($wpcustomize_remember_me_by_default)));
        update_option('wpcustomize_custom_error_message', htmlentities(stripslashes($_POST['wpcustomize_custom_error_message'])));
        update_option('wpcustomize_remove_login_shake', htmlentities(stripslashes($wpcustomize_remove_login_shake)));
        update_option('wpcustomize_admin_login_redirect', htmlentities(stripslashes($wpcustomize_admin_login_redirect)));
        update_option('wpcustomize_admin_login_redirect_url', htmlentities(stripslashes($_POST['wpcustomize_admin_login_redirect_url'])));
        update_option('wpcustomize_admin_login_background_url', htmlentities(stripslashes($_POST['wpcustomize_admin_login_background_url'])));
        update_option('wpcustomize_admin_login_background_repeat', htmlentities(stripslashes($_POST['wpcustomize_admin_login_background_repeat'])));
        update_option('wpcustomize_admin_login_background_position', htmlentities(stripslashes($_POST['wpcustomize_admin_login_background_position'])));
        update_option('wpcustomize_admin_login_background_attachment', htmlentities(stripslashes($_POST['wpcustomize_admin_login_background_attachment'])));
        update_option('wpcustomize_admin_login_background_size', htmlentities(stripslashes($_POST['wpcustomize_admin_login_background_size'])));
        update_option('field_name_username', htmlentities(stripslashes($_POST['field_name_username'])));
        update_option('field_name_password', htmlentities(stripslashes($_POST['field_name_password'])));
        // Put an settings updated message on the screen
        ?><div class="updated fade"><p><strong><?php _e('Settings saved.', 'wp-customize-menu' ); ?></strong></p></div><?php
    }
    ?>
    <div class="wrap">
    <?php screen_icon(); ?>
    <h2>Customize</h2>
    <form name="wpcustomize_customize" method="post" action="">
        <?php //settings_fields('myoption-group'); ?>
        <?php //do_settings_fields('myoption-group'); ?>
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
        <hr />
        <h3>WordPress Admin Login</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e("Login Page Title:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="42" name="wpcustomize_admin_page_title" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_page_title'] ) && $_POST['wpcustomize_admin_page_title'] ? $_POST['wpcustomize_admin_page_title'] : html_entity_decode(get_option('wpcustomize_admin_page_title', htmlentities(get_option('wpcustomize_admin_page_title')))) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Logo Image:</th>
                <td>
                    <label for="wpcustomize_admin_logo_image_url">
                        <input id="wpcustomize_admin_logo_image_url" type="text" class="uploadfile" size="36" name="wpcustomize_admin_logo_image_url" value="<?php
                            echo (isset( $_POST['wpcustomize_admin_logo_image_url'] ) && $_POST['wpcustomize_admin_logo_image_url'] ? $_POST['wpcustomize_admin_logo_image_url'] : html_entity_decode(get_option('wpcustomize_admin_logo_image_url', htmlentities(get_option('wpcustomize_admin_logo_image_url')))) );
                        ?>" />
                        <input type="button" class="upload_button button" value="Upload Image" />
                        <br />Enter a URL or upload an image for the logo.
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Logo Link URL:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="42" name="wpcustomize_admin_logo_link_url" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_logo_link_url'] ) && $_POST['wpcustomize_admin_logo_link_url'] ? $_POST['wpcustomize_admin_logo_link_url'] : html_entity_decode(get_option('wpcustomize_admin_logo_link_url', htmlentities(get_option('wpcustomize_admin_logo_link_url')))) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Logo Title Attribute:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="42" name="wpcustomize_admin_logo_title" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_logo_title'] ) && $_POST['wpcustomize_admin_logo_title'] ? $_POST['wpcustomize_admin_logo_title'] : html_entity_decode(get_option('wpcustomize_admin_logo_title', htmlentities(get_option('wpcustomize_admin_logo_title')))) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Logo Image Size:", 'wp-customize-menu'); ?> </th>
                <td>
                Width: <input type="text" class="smallinput" size="5" name="wpcustomize_admin_logo_width" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_logo_width'] ) && $_POST['wpcustomize_admin_logo_width'] ? $_POST['wpcustomize_admin_logo_width'] : html_entity_decode(get_option('wpcustomize_admin_logo_width', htmlentities(get_option('wpcustomize_admin_logo_width')))) );
                ?>"> px.&nbsp;&nbsp;&nbsp;&nbsp;Height: <input type="text" class="smallinput" size="5" name="wpcustomize_admin_logo_height" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_logo_height'] ) && $_POST['wpcustomize_admin_logo_height'] ? $_POST['wpcustomize_admin_logo_height'] : html_entity_decode(get_option('wpcustomize_admin_logo_height', htmlentities(get_option('wpcustomize_admin_logo_height')))) );
                ?>"> px.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Logo Area Size:", 'wp-customize-menu'); ?> </th>
                <td>
                Width: <input type="text" class="smallinput" size="5" name="wpcustomize_admin_logo_area_width" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_logo_area_width'] ) && $_POST['wpcustomize_admin_logo_area_width'] ? $_POST['wpcustomize_admin_logo_area_width'] : html_entity_decode(get_option('wpcustomize_admin_logo_area_width', htmlentities(get_option('wpcustomize_admin_logo_area_width')))) );
                ?>"> px.&nbsp;&nbsp;&nbsp;&nbsp;Height: <input type="text" class="smallinput" size="5" name="wpcustomize_admin_logo_area_height" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_logo_area_height'] ) && $_POST['wpcustomize_admin_logo_area_height'] ? $_POST['wpcustomize_admin_logo_area_height'] : html_entity_decode(get_option('wpcustomize_admin_logo_area_height', htmlentities(get_option('wpcustomize_admin_logo_area_height')))) );
                ?>"> px.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Background Image:</th>
                <td>
                    <label for="wpcustomize_admin_login_background_url">
                        <input id="wpcustomize_admin_login_background_url" type="text" class="uploadfile" size="36" name="wpcustomize_admin_login_background_url" value="<?php
                            echo (isset( $_POST['wpcustomize_admin_login_background_url'] ) && $_POST['wpcustomize_admin_login_background_url'] ? $_POST['wpcustomize_admin_login_background_url'] : html_entity_decode(get_option('wpcustomize_admin_login_background_url', htmlentities(get_option('wpcustomize_admin_login_background_url')))) );
                        ?>" />
                        <input type="button" class="upload_button button" value="Upload Image" />
                        <br />Enter a URL or upload an image for the logo.
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Background Repeat:", 'wp-customize-menu'); ?> </th>
                <td>
                    <select name="wpcustomize_admin_login_background_repeat" class="selectbox">
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_repeat'] ) && $_POST['wpcustomize_admin_login_background_repeat'] == "repeat") || get_option('wpcustomize_admin_login_background_repeat') == "repeat" ) echo ' selected="selected"'; ?>>repeat</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_repeat'] ) && $_POST['wpcustomize_admin_login_background_repeat'] == "repeat-x") || get_option('wpcustomize_admin_login_background_repeat') == "repeat-x" ) echo ' selected="selected"'; ?>>repeat-x</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_repeat'] ) && $_POST['wpcustomize_admin_login_background_repeat'] == "repeat-y") || get_option('wpcustomize_admin_login_background_repeat') == "repeat-y" ) echo ' selected="selected"'; ?>>repeat-y</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_repeat'] ) && $_POST['wpcustomize_admin_login_background_repeat'] == "no-repeat") || get_option('wpcustomize_admin_login_background_repeat') == "no-repeat" ) echo ' selected="selected"'; ?>>no-repeat</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Background Position:", 'wp-customize-menu'); ?> </th>
                <td>
                    <select name="wpcustomize_admin_login_background_position" class="selectbox">
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "left top") || get_option('wpcustomize_admin_login_background_attachment') == "left top" ) echo ' selected="selected"'; ?>>left top</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "left center") || get_option('wpcustomize_admin_login_background_attachment') == "left center" ) echo ' selected="selected"'; ?>>left center</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "left bottom") || get_option('wpcustomize_admin_login_background_attachment') == "left bottom" ) echo ' selected="selected"'; ?>>left bottom</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "right top") || get_option('wpcustomize_admin_login_background_attachment') == "right top" ) echo ' selected="selected"'; ?>>right top</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "right center") || get_option('wpcustomize_admin_login_background_attachment') == "right center" ) echo ' selected="selected"'; ?>>right center</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "right bottom") || get_option('wpcustomize_admin_login_background_attachment') == "right bottom" ) echo ' selected="selected"'; ?>>right bottom</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "center top") || get_option('wpcustomize_admin_login_background_attachment') == "center top" ) echo ' selected="selected"'; ?>>center top</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "center center") || get_option('wpcustomize_admin_login_background_attachment') == "center center" ) echo ' selected="selected"'; ?>>center center</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_position'] ) && $_POST['wpcustomize_admin_login_background_position'] == "center bottom") || get_option('wpcustomize_admin_login_background_attachment') == "center bottom" ) echo ' selected="selected"'; ?>>center bottom</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Background Attachment:", 'wp-customize-menu'); ?> </th>
                <td>
                    <select name="wpcustomize_admin_login_background_attachment" class="selectbox">
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_attachment'] ) && $_POST['wpcustomize_admin_login_background_attachment'] == "scroll") || get_option('wpcustomize_admin_login_background_attachment') == "scroll" ) echo ' selected="selected"'; ?>>scroll</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_attachment'] ) && $_POST['wpcustomize_admin_login_background_attachment'] == "fixed") || get_option('wpcustomize_admin_login_background_attachment') == "fixed" ) echo ' selected="selected"'; ?>>fixed</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_attachment'] ) && $_POST['wpcustomize_admin_login_background_attachment'] == "local") || get_option('wpcustomize_admin_login_background_attachment') == "local" ) echo ' selected="selected"'; ?>>local</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Background Size:", 'wp-customize-menu'); ?> </th>
                <td>
                    <select name="wpcustomize_admin_login_background_size" class="selectbox">
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_size'] ) && $_POST['wpcustomize_admin_login_background_size'] == "auto") || get_option('wpcustomize_admin_login_background_attachment') == "auto" ) echo ' selected="selected"'; ?>>auto</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_size'] ) && $_POST['wpcustomize_admin_login_background_size'] == "contain") || get_option('wpcustomize_admin_login_background_attachment') == "contain" ) echo ' selected="selected"'; ?>>contain</option>
                        <option<?php if( (isset( $_POST['wpcustomize_admin_login_background_size'] ) && $_POST['wpcustomize_admin_login_background_size'] == "cover") || get_option('wpcustomize_admin_login_background_attachment') == "cover" ) echo ' selected="selected"'; ?>>cover</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Background Color:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="6" id="wpcustomize_admin_bgcolor" name="wpcustomize_admin_bgcolor" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_bgcolor'] ) && $_POST['wpcustomize_admin_bgcolor'] ? $_POST['wpcustomize_admin_bgcolor'] : html_entity_decode(get_option('wpcustomize_admin_bgcolor', '000')) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Links Text Color:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="6" id="wpcustomize_admin_linkcolor" name="wpcustomize_admin_linkcolor" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_linkcolor'] ) && $_POST['wpcustomize_admin_linkcolor'] ? $_POST['wpcustomize_admin_linkcolor'] : html_entity_decode(get_option('wpcustomize_admin_linkcolor', 'fff')) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Links Text Hover Color:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="6" id="wpcustomize_admin_linkhovercolor" name="wpcustomize_admin_linkhovercolor" value="<?php
                    echo (isset( $_POST['wpcustomize_admin_linkhovercolor'] ) && $_POST['wpcustomize_admin_linkhovercolor'] ? $_POST['wpcustomize_admin_linkhovercolor'] : html_entity_decode(get_option('wpcustomize_admin_linkhovercolor', 'cfcfcf')) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Custom Error Message:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="42" name="wpcustomize_custom_error_message" value="<?php
                    echo (isset( $_POST['wpcustomize_custom_error_message'] ) && $_POST['wpcustomize_custom_error_message'] ? $_POST['wpcustomize_custom_error_message'] : html_entity_decode(get_option('wpcustomize_custom_error_message', htmlentities(get_option('wpcustomize_custom_error_message')))) );
                ?>"><br>
                (Default: Incorrect login details. Please try again.)
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Redirect on login?", 'wp-customize-menu'); ?> </th>
                <td>
                    <input type="checkbox" id="wpcustomize_admin_login_redirect" name="wpcustomize_admin_login_redirect" value="1"<?php
                        if(
                            (
                                isset( $_POST['wpcustomize_admin_login_redirect'] )
                                && $_POST['wpcustomize_admin_login_redirect'] == "1"
                            ) || (
                                html_entity_decode(get_option('wpcustomize_admin_login_redirect')) == "1"
                            )
                        ) {
                            echo ' checked="checked"';
                        }
                    ?>>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Redirect URL:", 'wp-customize-menu'); ?> </th>
                <td>
                    <input type="text" size="42" name="wpcustomize_admin_login_redirect_url" value="<?php
                        echo (isset( $_POST['wpcustomize_admin_login_redirect_url'] ) && $_POST['wpcustomize_admin_login_redirect_url'] ? $_POST['wpcustomize_admin_login_redirect_url'] : html_entity_decode(get_option('wpcustomize_admin_login_redirect_url', htmlentities(get_option('wpcustomize_admin_login_redirect_url')))) );
                    ?>"><br>
                    (Leave blank to redirect to the Site URL)
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Hide the Register and Forgot Password links?", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="checkbox" id="wpcustomize_hide_register_forgot_links" name="wpcustomize_hide_register_forgot_links" value="1"<?php
                    if(
                        (
                            isset( $_POST['wpcustomize_hide_register_forgot_links'] )
                            && $_POST['wpcustomize_hide_register_forgot_links'] == "1"
                        ) || (
                            html_entity_decode(get_option('wpcustomize_hide_register_forgot_links')) == "1"
                        )
                    ) {
                        echo ' checked="checked"';
                    }
                ?>>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Hide the Back to Blog link?", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="checkbox" id="wpcustomize_hide_back_link" name="wpcustomize_hide_back_link" value="1"<?php
                    if(
                        (
                            isset( $_POST['wpcustomize_hide_back_link'] )
                            && $_POST['wpcustomize_hide_back_link'] == "1"
                        ) || (
                            html_entity_decode(get_option('wpcustomize_hide_back_link')) == "1"
                        )
                    ) {
                        echo ' checked="checked"';
                    }
                ?>>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Check remember me by default?", 'wp-customize-menu'); ?> </th>
                <td>
                    <input type="checkbox" id="wpcustomize_remember_me_by_default" name="wpcustomize_remember_me_by_default" value="1"<?php
                        if(
                            (
                                isset( $_POST['wpcustomize_remember_me_by_default'] )
                                && $_POST['wpcustomize_remember_me_by_default'] == "1"
                            ) || (
                                html_entity_decode(get_option('wpcustomize_remember_me_by_default')) == "1"
                            )
                        ) {
                            echo ' checked="checked"';
                        }
                    ?>>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Remove the login form shake?", 'wp-customize-menu'); ?> </th>
                <td>
                    <input type="checkbox" id="wpcustomize_remove_login_shake" name="wpcustomize_remove_login_shake" value="1"<?php
                        if(
                            (
                                isset( $_POST['wpcustomize_remove_login_shake'] )
                                && $_POST['wpcustomize_remove_login_shake'] == "1"
                            ) || (
                                html_entity_decode(get_option('wpcustomize_remove_login_shake')) == "1"
                            )
                        ) {
                            echo ' checked="checked"';
                        }
                    ?>>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Username Field Name:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="42" name="field_name_username" value="<?php
                    echo (isset( $_POST['field_name_username'] ) && $_POST['field_name_username'] ? $_POST['field_name_username'] : html_entity_decode(get_option('field_name_username', 'user_login')) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Password Field Name:", 'wp-customize-menu'); ?> </th>
                <td>
                <input type="text" size="42" name="field_name_password" value="<?php
                    echo (isset( $_POST['field_name_password'] ) && $_POST['field_name_password'] ? $_POST['field_name_password'] : html_entity_decode(get_option('field_name_password', 'user_pass')) );
                ?>">
                </td>
            </tr>
            <tr valign="top">
                <td colspan="2"><strong>Custom Login Page CSS:</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Custom Login Page CSS:", 'wp-customize-menu'); ?> </th>
                <td>
                    <textarea cols="42" rows="8" name="wpcustomize_admin_loginstyles" id="custom_login_css[custom_css]"><?php
                        echo html_entity_decode(get_option('wpcustomize_admin_loginstyles',htmlentities(get_option('wpcustomize_admin_loginstyles'))));
                    ?></textarea>
                </td>
            </tr>
        </table>
        <hr />
        <h3>WordPress Admin Footer</h3>
        <table class="form-table">
            <tr valign="top">
                <td colspan="2"><strong>Admin Footer HTML:</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Admin Footer HTML:", 'wp-customize-menu'); ?> </th>
                <td>
                    <textarea cols="42" rows="8" name="wpcustomize_admin_footer_contents" id="custom_footer_html[custom_html]"><?php
                        echo html_entity_decode(get_option('wpcustomize_admin_footer_contents',htmlentities(get_option('wpcustomize_admin_footer_contents'))));
                    ?></textarea>
                </td>
            </tr>
        </table>
        <hr />
        <?php submit_button(); ?>
    </form>
    </div>
    <?php
}