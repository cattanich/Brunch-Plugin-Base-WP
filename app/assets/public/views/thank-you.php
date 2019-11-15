<?php 
if (get_option( 'goquoting-link-system' ) != 'on'){
    wp_redirect( home_url('request-a-quote'), 302 );
    exit;
}
require_once PATH_PLUGIN_BOOKING . '/app/functions/booking-functions.php';
global $wpdb;
$prefix = 'gg_';


?>
<style>
    <?php echo file_get_contents( RUTA_PLUGIN_BOOKING . 'app/css/booking-system-common-styles.css', true ); ?>
    /* THANK YOU VIEW */
    .message-box{
        background: white;
        border-radius: 6px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);        
        max-width: 380px;
        margin: 48px auto;
    }
    .fa{
        font-size: 80px;
        margin-top: 48px;
    }
    .success .fa{
        color: #B2D06D;
    }
    .error .fa{
        color: #F26463;
    }
    .submit-button{
        text-transform: uppercase;
        text-align: center;
        max-width: 100px;
        margin: 36px auto;
        display: block;
    }
</style>
<?php 

//    echo '<pre>';
//    print_r($_POST);
//    echo '</pre>';
    $pagorealizado = true;
    if($pagorealizado){
        realizarPedido($_POST);
        // ELIMINAR COOKIE
        unset($_COOKIE['goquoting_cookie']);
        setcookie('goquoting_cookie', '', time() - ( 15 * 60 ) , COOKIEPATH, COOKIE_DOMAIN);
?>
<div class="message-box success">    
    <div class="row">
        <div class="col-xs-12 text-center">
            <i class="fa fa-3x fa-check text-success text-center"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1><?= _e('Thank you for your booking!', 'gogalapagos')?></h1>
            <p><?= _e('A confirmation email  will be sent to you shortly', 'gogalapagos') ?></p>
            <a href="<?= home_url('check-availability') ?>" class="btn submit-button"><?= _e('New Quote') ?></a>
        </div>
    </div>
</div>
<?php }else{ ?>
<div class="message-box error">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <i class="fa fa-3x fa-times text-success text-center"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <h1><?= _e('Internal Server Error', 'gogalapagos')?></h1>
                <p><?= _e('Wow! Something went wrong', 'gogalapagos') ?>.</p>
                <p><?= _e('Try to reload this page or do not hesitate to contact us if the problem persists', 'gogalapagos') ?></p>
                
            </div>
        </div>
    </div>
</div>
<?php } ?>