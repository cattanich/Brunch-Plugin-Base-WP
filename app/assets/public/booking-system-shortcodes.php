<?php
// CREAR EL SHORTCODE PARA INYECTAR LAS FUNCIONES DEL COTIZADOR
add_shortcode('goquoting-check-availability', 'goquotingCheckAvailability');
add_shortcode('goquoting-accommodation', 'goquotingAccommodation');
add_shortcode('goquoting-travelers-details', 'goquotingTravelersDetails');
add_shortcode('goquoting-extras', 'goquotingExtras');
add_shortcode('gogabooking-checkout', 'goquotingCheckout');
add_shortcode('gogabooking-thankyou', 'goquotingThanks');
function goquotingCheckAvailability(){
include 'views/check-availability.php';
}
function goquotingAccommodation(){
include 'views/accommodation.php';
}
function goquotingTravelersDetails(){
include 'views/travelers-details.php';
}
function goquotingExtras(){
include 'views/travelers-extras.php';
}
function goquotingCheckout(){
include 'views/checkout.php';
}
function goquotingThanks(){
include 'views/thank-you.php';
flush_rewrite_rules();
}