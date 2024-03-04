<?php
/**
 * The template for displaying all single posts of type 'clients'
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package Andries
 */

global $wpdb;

get_header();

if (!is_user_logged_in()) {
    wp_redirect('https://www.andriesbester.com'); // Direct URL
    exit;
}

while (have_posts()): the_post();

    $current_client_id = get_the_ID();
    $client_logo = get_field('client_logo');
    $client_name = get_field('client_name') ?: get_the_title(); // Fallback to post title if no client name set
    $email_address = get_field('email_address');
    $phone_number = get_field('phone_number');
    $address = get_field('address');
    $client_since = get_field('client_since');
    $client_since_formatted = $client_since ? date("d/m/Y", strtotime($client_since)) : '';
    $logo_url = $client_logo['url'] ?? ''; // Default to empty string if no logo

    // Query invoices related to this client
    $invoice_ids = $wpdb->get_col($wpdb->prepare("
        SELECT pm.post_id
        FROM $wpdb->postmeta pm
        INNER JOIN $wpdb->posts p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'associated_client'
        AND pm.meta_value LIKE %s
        AND p.post_status = 'publish'
    ", '%' . $wpdb->esc_like(':"' . $current_client_id . '";') . '%'));

    // Organize invoices by status
    $invoices_by_status = [];

    if (!empty($invoice_ids)) {
        foreach ($invoice_ids as $invoice_id) {
            $status = get_post_meta($invoice_id, 'invoice_status', true);
            $invoice_post = get_post($invoice_id); // Get the invoice post object
            $invoices_by_status[$status][] = [
                'ID' => $invoice_id,
                'title' => $invoice_post->post_title,
                'permalink' => get_permalink($invoice_id),
                'due_date' => get_post_meta($invoice_id, 'due_date', true),
            ];
        }
    }

    // Status colors
    $status_colors = [
        'Draft' => '#808080',
        'Sent' => '#0000FF',
        'Viewed' => '#FFD700',
        'Paid' => '#008000',
        'Overdue' => '#FF4500',
        'Cancelled' => '#FF0000',
    ];
    ?>

    <div class="client-container">
        <div class="client">
            <div class="logo"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($client_name); ?> Logo"></div>
            <div class="details">
                <p>Name: <span><?php echo esc_html($client_name); ?></span></p>
                <p>Email: <span><?php echo esc_html($email_address); ?></span></p>
                <p>Phone: <span><?php echo esc_html($phone_number); ?></span></p>
                <p>Address: <span><?php echo esc_html($address); ?></span></p>
                <p>Client Since: <span><?php echo esc_html($client_since_formatted); ?></span></p>
            </div>
            <?php foreach ($invoices_by_status as $status => $invoices): ?>
                <div class="invoices-group">
                    <div class="status"><h3>Status: <span style="background-color:<?php echo esc_attr($status_colors[$status] ?? '#000000'); ?>; color: #ffffff;"><?php echo esc_html($status); ?></span></h3></div>
                    <div class="invoice-list">
                        <?php foreach ($invoices as $invoice): ?>
                            <?php
                            // Initialize the total amount due for this invoice
                            $total_amount_due = 0;
                            
                            // Check if the invoice has services provided and calculate the total
                            if (have_rows('services_provided', $invoice['ID'])): 
                                while (have_rows('services_provided', $invoice['ID'])): the_row(); 
                                    $quantity = get_sub_field('quantity');
                                    $price = get_sub_field('price');
                                    $total_amount_due += $quantity * $price; // Calculate the line total and add to total amount due
                                endwhile;
                            endif;
                            ?>
                            <div class="item">
                                <div class="left">
                                    <p><a href="<?php echo esc_url($invoice['permalink']); ?>"><?php echo esc_html($invoice['title']); ?></a></p>
                                </div>
                                <div class="right">
                                    <p>Due Date: <?php echo esc_html(date("d F Y", strtotime($invoice['due_date']))); ?></p>
                                    <p>Amount Due: R<?php echo esc_html(number_format($total_amount_due, 2, '.', ',')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endwhile; ?>
<?php get_footer(); ?>
