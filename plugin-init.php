<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( version_compare( $GLOBALS['wp_version'], '3.5', '<' ) ) {
    return;
}

function wp_customize_install() {

    global $wpdb;

    // set up variables for the login page
    $the_page_title = 'Log In';
    $the_page_name = 'login';

    // the menu entry...
    delete_option("wp_customize_page_title");
    add_option("wp_customize_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wp_customize_page_name");
    add_option("wp_customize_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wp_customize_page_id");
    add_option("wp_customize_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $new_post = array();
        $new_post['post_title'] = $the_page_title;
        $new_post['post_name'] = $the_page_name;
        $new_post['post_content'] = "";
        $new_post['post_status'] = 'publish';
        $new_post['post_type'] = 'page';
        $new_post['comment_status'] = 'closed';
        $new_post['ping_status'] = 'closed';
        $new_post['post_category'] = array(1); // the default 'Uncategorized'

        // Insert the post into the database
        $the_page_id = wp_insert_post( $new_post );

    } else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    // create the new page
    delete_option( 'wp_customize_page_id' );
    add_option( 'wp_customize_page_id', $the_page_id );
    // assign the page template to the new page
    update_post_meta( $the_page_id, "_wp_page_template", "template-login.php" );

    /**
     * Configure server name
     */
    $servername = str_replace(".", "\.", $_SERVER['SERVER_NAME']);

    /**
     * Configure apache rewrite rules
     */
    $site_url_parts = explode('wp-admin/', $_SERVER['REQUEST_URI']);
    $site_path = $site_url_parts[0] . 'login/';
    $rel_site_path = substr($site_url_parts[0], 1);
    $rewrite_rules = <<< EOD
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_METHOD} POST
RewriteCond %{QUERY_STRING} !(?:^)action=register
RewriteCond %{QUERY_STRING} !(?:^)action=lostpassword
RewriteCond %{HTTP_REFERER} !^http://(.*)?$servername [NC]
RewriteCond %{REQUEST_URI} ^(.*)?{$rel_site_path}wp-login\.php(.*)$ [OR]
RewriteCond %{REQUEST_URI} ^(.*)?{$rel_site_path}wp-admin$
RewriteRule ^(.*)$ $site_path [R=301,L]
</IfModule>
EOD;

    // add our rewrite rules to Apache .htaccess
    insert_apache_rewrite_rules( $rewrite_rules );

}

function wp_customize_remove() {

    global $wpdb;

    $the_page_title = get_option( "wp_customize_page_title" );
    $the_page_name = get_option( "wp_customize_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wp_customize_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    // delete the page
    delete_option("wp_customize_page_title");
    delete_option("wp_customize_page_name");
    delete_option("wp_customize_page_id");

    // remove our rewrite rules from Apache .htaccess
    remove_apache_rewrite_rules();

}

/**
 * Insert apache rewrite rules
 */
function insert_apache_rewrite_rules( $rewrite_rules, $marker = 'WP-Customize', $before = '# BEGIN WordPress' ) {
    // get path to htaccess file
    $htaccess_file = get_home_path() . '.htaccess';
    // check if htaccess file exists
    $htaccess_exists = file_exists( $htaccess_file );
    // if htaccess file exists, get htaccess contents
    $htaccess_content = $htaccess_exists ? file_get_contents( $htaccess_file ) : '';
    // remove any previously added rules from htaccess contents, to avoid duplication
    $htaccess_content = preg_replace( "/# BEGIN $marker.*# END $marker\n*/is", '', $htaccess_content );

    // add new rules to htaccess contents
    if ( $before && $rewrite_rules ) {

        $rewrite_rules = is_array( $rewrite_rules ) ? implode( "\n", $rewrite_rules ) : $rewrite_rules;
        $rewrite_rules = trim( $rewrite_rules, "\r\n " );

        if ( $rewrite_rules ) {
            // No WordPress rules? (as in multisite)
            if ( false === strpos( $htaccess_content, $before ) ) {
                // The new content needs to be inserted at the begining of the file.
                $htaccess_content = "# BEGIN $marker\n$rewrite_rules\n# END $marker\n\n\n$htaccess_content";
            }
            else {
                // The new content needs to be inserted before the WordPress rules.
                $rewrite_rules = "# BEGIN $marker\n$rewrite_rules\n# END $marker\n\n\n$before";
                $htaccess_content = str_replace( $before, $rewrite_rules, $htaccess_content );
            }
        }
    }

    // Update the .htaccess file
    return (bool) file_put_contents( $htaccess_file , $htaccess_content );
}

/**
 * Remove apache rewrite rules
 */
function remove_apache_rewrite_rules( $marker = 'WP-Customize' ) {
    // get path to htaccess file
    $htaccess_file = get_home_path() . '.htaccess';
    // check if htaccess file exists
    $htaccess_exists = file_exists( $htaccess_file );
    // if htaccess file exists, get htaccess contents
    $htaccess_content = $htaccess_exists ? file_get_contents( $htaccess_file ) : '';
    // remove the added rules from htaccess contents
    $htaccess_content = preg_replace( "/# BEGIN $marker.*# END $marker\n*/is", '', $htaccess_content );

    // Update the .htaccess file
    return (bool) file_put_contents( $htaccess_file , $htaccess_content );
}