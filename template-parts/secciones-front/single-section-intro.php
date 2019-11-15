<?php
if ( !defined( 'ABSPATH' ) ) {
die( 'Direct access is forbidden.' );
}

/*
Template Name: Intro
Template Post Type: section
*/

?>

<?php echo rwmb_meta( 'titulo' );

?>


<?= the_content( null, false );  ?>

<?= get_posts( 'post_content' ); ?>