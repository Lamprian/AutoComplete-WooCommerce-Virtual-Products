function auto_complete_virtual_orders( $payment_complete_status, $order_id, $order ) {
$current_status = $order->get_status();
// We only want to update the status to 'completed' if it's coming from one of the following statuses:
$allowed_current_statuses = array( 'on-hold', 'pending', 'failed' );

if ( 'processing' === $payment_complete_status && in_array( $current_status, $allowed_current_statuses ) ) {

$order_items = $order->get_items();

// Create an array of products in the order
$order_products = array_filter( array_map( function( $item ) {
// Get associated product for each line item
return $item->get_product();
}, $order_items ), function( $product ) {
// Remove non-products
return !! $product;
} );

if ( count( $order_products > 0 ) ) {
// Check if each product is 'virtual'
$is_virtual_order = array_reduce( $order_products, function( $virtual_order_so_far, $product ) {
return $virtual_order_so_far && $product->is_virtual();
}, true );

if ( $is_virtual_order ) {
$payment_complete_status = 'completed';
}
}
}
return $payment_complete_status;
}
