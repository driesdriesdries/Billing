<?php
/**
 * The template for displaying all single posts of type 'invoices'
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package Andries
 */

get_header();
if (!is_user_logged_in()) {
    wp_redirect('https://www.andriesbester.com'); // Direct URL
    exit;
}
?>

<div class="invoice-container">
    <div class="invoice">
        <div class="header">
            <h2>Invoice</h2>
            <p>Andries Bester</p>
            <p>13 Firdale Avenue</p>
            <p>Gardens</p>
            <p>Cape Town</p>
            <p>Mobile: +27712914643</p>
            <p><a href="www.andriesbester.com">andriesbester.com</a></p>
        </div>
        <div class="details">
            <div class="left">
                <h4>Bill To:</h4>
                <?php
                $associated_clients = get_field('associated_client');
                if ($associated_clients):
                    $client_post = $associated_clients[0];
                    if ($client_post):
                        $client_link = get_permalink($client_post->ID);
                ?>
                        <p><a href="<?php echo esc_url($client_link); ?>"><?php echo esc_html($client_post->post_title); ?></a></p>
                <?php
                    endif;
                endif;
                ?>
            </div>
            <div class="right">
                <h4>Invoice Number: <?php the_field('invoice_number'); ?></h4>
                <p>Invoice Date: <?php the_field('invoice_date'); ?></p>
                <p>Payment Due: <?php the_field('due_date'); ?></p>
                <?php
                $invoice_status = get_field('invoice_status');
                ?>
                <p>Status: <span><?php echo esc_html($invoice_status); ?></span></p>
            </div>
        </div>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Items</th>
                        <th class="quantity-column">Quantity</th>
                        <th class="price-column">Price</th>
                        <th class="amount-column">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_amount_due = 0;
                    if (have_rows('services_provided')):
                        while (have_rows('services_provided')): the_row();
                            $item = get_sub_field('item');
                            $quantity = get_sub_field('quantity');
                            $price = get_sub_field('price');
                            $amount = $price * $quantity;
                            $total_amount_due += $amount;
                    ?>
                    <tr>
                        <td><?php echo esc_html($item); ?></td>
                        <td class="quantity-column"><?php echo esc_html($quantity); ?></td>
                        <td class="price-column">R<?php echo number_format($price, 2, '.', ','); ?></td>
                        <td class="amount-column">R<?php echo number_format($amount, 2, '.', ','); ?></td>
                    </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
        <div class="total-container">
            <span class="label">Total Amount Due:</span>
            <span class="amount">R<?php echo number_format($total_amount_due, 2, '.', ','); ?></span>
        </div>
        <div class="footer">
            <h4>Notes</h4>
            <p>Account Holder: AG Bester</p>
            <p>Bank: FNB</p>
            <p>Account Number: 62597120098</p>
            <p>Branch Code: 250665</p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
