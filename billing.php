<?php
/**
 * Plugin Name: Billing - Invoices and Clients Management
 * Description: A plugin to manage invoices and clients directly from WordPress.
 * Version: 1.0
 * Author: Andries Bester
 * Author URI: www.andriesbester.com
 */

// Enqueue billingstyles.css file
function enqueue_billing_styles() {
    wp_enqueue_style( 'billing-styles', plugin_dir_url( __FILE__ ) . 'billingstyles.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_billing_styles' );

//Invoice and Client Post Type
function create_custom_post_types() {
    // Labels for the Clients Post Type
    $labels_clients = array(
        'name'                  => _x('Clients', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Client', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Clients', 'textdomain'),
        'name_admin_bar'        => __('Client', 'textdomain'),
        'archives'              => __('Client Archives', 'textdomain'),
        'attributes'            => __('Client Attributes', 'textdomain'),
        'parent_item_colon'     => __('Parent Client:', 'textdomain'),
        'all_items'             => __('All Clients', 'textdomain'),
        'add_new_item'          => __('Add New Client', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'new_item'              => __('New Client', 'textdomain'),
        'edit_item'             => __('Edit Client', 'textdomain'),
        'update_item'           => __('Update Client', 'textdomain'),
        'view_item'             => __('View Client', 'textdomain'),
        'view_items'            => __('View Clients', 'textdomain'),
        'search_items'          => __('Search Client', 'textdomain'),
        'not_found'             => __('Not found', 'textdomain'),
        'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
        'featured_image'        => __('Client Image', 'textdomain'),
        'set_featured_image'    => __('Set client image', 'textdomain'),
        'remove_featured_image' => __('Remove client image', 'textdomain'),
        'use_featured_image'    => __('Use as client image', 'textdomain'),
        'insert_into_item'      => __('Insert into client', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this client', 'textdomain'),
        'items_list'            => __('Clients list', 'textdomain'),
        'items_list_navigation' => __('Clients list navigation', 'textdomain'),
        'filter_items_list'     => __('Filter clients list', 'textdomain'),
    );
    register_post_type('clients',
        array(
            'labels'        => $labels_clients,
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array('slug' => 'clients'),
            'supports'      => array('title', 'editor', 'thumbnail'),
            'show_in_rest'  => false, // Disable Gutenberg editor
            'menu_icon'     => 'dashicons-businessperson', // Custom icon for Clients
        )
    );

    // Labels for the Invoices Post Type
    $labels_invoices = array(
        'name'                  => _x('Invoices', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Invoice', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Invoices', 'textdomain'),
        'name_admin_bar'        => __('Invoice', 'textdomain'),
        'archives'              => __('Invoice Archives', 'textdomain'),
        'attributes'            => __('Invoice Attributes', 'textdomain'),
        'parent_item_colon'     => __('Parent Invoice:', 'textdomain'),
        'all_items'             => __('All Invoices', 'textdomain'),
        'add_new_item'          => __('Add New Invoice', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'new_item'              => __('New Invoice', 'textdomain'),
        'edit_item'             => __('Edit Invoice', 'textdomain'),
        'update_item'           => __('Update Invoice', 'textdomain'),
        'view_item'             => __('View Invoice', 'textdomain'),
        'view_items'            => __('View Invoices', 'textdomain'),
        'search_items'          => __('Search Invoice', 'textdomain'),
        'not_found'             => __('Not found', 'textdomain'),
        'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
        'featured_image'        => __('Invoice Image', 'textdomain'),
        'set_featured_image'    => __('Set invoice image', 'textdomain'),
        'remove_featured_image' => __('Remove invoice image', 'textdomain'),
        'use_featured_image'    => __('Use as invoice image', 'textdomain'),
        'insert_into_item'      => __('Insert into invoice', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this invoice', 'textdomain'),
        'items_list'            => __('Invoices list', 'textdomain'),
        'items_list_navigation' => __('Invoices list navigation', 'textdomain'),
        'filter_items_list'     => __('Filter invoices list', 'textdomain'),
    );
    register_post_type('invoices',
        array(
            'labels'        => $labels_invoices,
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array('slug' => 'invoices'),
            'supports'      => array('title', 'editor', 'thumbnail'),
            'show_in_rest'  => false, // Disable Gutenberg editor
            'menu_icon'     => 'dashicons-editor-insertmore', // Custom icon for Invoices
        )
    );
}
add_action('init', 'create_custom_post_types');

function billing_load_custom_templates( $template ) {
    global $post;

    if (isset($post) && !empty($post)) {
        if ('clients' === $post->post_type) {
            $template = plugin_dir_path(__FILE__) . 'single-clients.php';
        } elseif ('invoices' === $post->post_type) {
            $template = plugin_dir_path(__FILE__) . 'single-invoices.php';
        }
    }

    return $template;
}
add_filter('template_include', 'billing_load_custom_templates', 99);
