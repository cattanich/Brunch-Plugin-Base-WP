<?php
// VALIDAR SI LA PAGINA DEL COTIZADOR ESTA CREADA SINO CREARLA
add_action( 'wp_loaded', 'gg_create_quote_pages', 10 );
function gg_create_quote_pages() {

$quote_page = get_page_by_path('check-availability');
if (!$quote_page){
// Create post object
$my_post = array(
'post_title'    => 'GOQuoting - Check Availability',
'post_name'     => 'check-availability',
'post_content'  => '[goquoting-check-availability]',
'post_status'   => 'publish',
'post_author'   => get_current_user_id(),
'post_type'     => 'page',
);
// Insert the post into the database
wp_insert_post( $my_post, '' );
}

$quote_page = get_page_by_path('accommodation');
if (!$quote_page){
// Create post object
$my_post = array(
'post_title'    => 'GOQuoting - Accommodation',
'post_name'     => 'accommodation',
'post_content'  => '[goquoting-accommodation]',
'post_status'   => 'publish',
'post_author'   => get_current_user_id(),
'post_type'     => 'page',
);
// Insert the post into the database
wp_insert_post( $my_post, '' );
}

$quote_page = get_page_by_path('traveler-details');
if (!$quote_page){
// Create post object
$my_post = array(
'post_title'    => 'GOQuoting - Travelers Details',
'post_name'     => 'traveler-details',
'post_content'  => '[goquoting-travelers-details]',
'post_status'   => 'publish',
'post_author'   => get_current_user_id(),
'post_type'     => 'page',
);
// Insert the post into the database
wp_insert_post( $my_post, '' );
}

$quote_page = get_page_by_path('extras');
if (!$quote_page){
// Create post object
$my_post = array(
'post_title'    => 'GOQuoting - Extras',
'post_name'     => 'extras',
'post_content'  => '[goquoting-extras]',
'post_status'   => 'publish',
'post_author'   => get_current_user_id(),
'post_type'     => 'page',
);
// Insert the post into the database
wp_insert_post( $my_post, '' );
}

$quote_page = get_page_by_path('checkout');
if (!$quote_page){
// Create post object
$my_post = array(
'post_title'    => 'GOQuoting - Checkout & Payment',
'post_name'     => 'checkout',
'post_content'  => '[gogabooking-checkout]',
'post_status'   => 'publish',
'post_author'   => get_current_user_id(),
'post_type'     => 'page',
);
// Insert the post into the database
wp_insert_post( $my_post, '' );
}

$quote_page = get_page_by_path('thank-you');
if (!$quote_page){
// Create post object
$my_post = array(
'post_title'    => 'GOQuoting - Thank you!',
'post_name'     => 'thank-you',
'post_content'  => '[gogabooking-thankyou]',
'post_status'   => 'publish',
'post_author'   => get_current_user_id(),
'post_type'     => 'page',
);
// Insert the post into the database
wp_insert_post( $my_post, '' );
}
}
add_action( 'plugins_loaded', 'ggAssingBookingTemplate', 11 );
function ggAssingBookingTemplate(){
$quote_page = get_page_by_path('thank-you');
update_post_meta( $quote_page->ID, '_wp_page_template', 'booking-system-template.php' );
}