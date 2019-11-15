<?php

//add_action( 'plugins_loaded', 'ggCreateCheckAvailabilityFile', 11 );
function ggCreateCheckAvailabilityFile(){
    //die();
    //obtener los contenidos de la página de booking
    $booking_page = get_page_by_path('booking');
    
    //obtener la ruta del tema activo
    $theme_activo = get_template_directory();
    
    $bookingfile = fopen( $theme_activo . '/page-booking.php', "w") or die("Unable to open file!");    
    //creat contenido del archivo
    ob_start();
    echo file_get_contents( $theme_activo . '/header.php' );
    ?>
    <?php
    $my_page_content = apply_filters('the_content', get_post_field('post_content', $booking_page->ID));
    do_shortcode($my_page_content);
    ?>

    <?php
    echo file_get_contents( $theme_activo . '/footer.php' );
    $page_content = ob_get_clean();

    // crear el archivo php para el page-booking.php
    fwrite($bookingfile, $page_content);
    fclose($bookingfile);

}