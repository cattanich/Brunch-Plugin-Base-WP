<?php
if (get_option( 'goquoting-link-system' ) != 'on'){
wp_redirect( home_url('request-a-quote'), 302 );
exit;
}
require_once PATH_PLUGIN_BOOKING . '/app/functions/booking-functions.php';
$prefix = 'gg_';
$cookie = crearCookie();
global $wpdb;
$sql = "SELECT * FROM ".$wpdb->prefix."goquoting_pedido WHERE cookie_sesion = '".$cookie."'";
$registro = $wpdb->get_results($sql);
if (!$registro){
$wpdb->insert(
$wpdb->prefix . 'goquoting_pedido',
array(
'cookie_sesion' => $cookie,
'fecha' => date('Y-m-d h:i:s'),
'barco' => $_POST['ship'],
'f_salida' => $_POST['departure'],
'adultos' => $_POST['adults'],
'ninios' => $_POST['children'],
'duracion' => $_POST['duration'],
'promo' => $_POST['promo']
)
);
}
$registro = $wpdb->get_results($sql);
$total_pax = $_POST['adults'] +  $_POST['children'];
?>
<div class="section off-height section1 text-center pt-5">
    <div class="container pt-5">
        <div class="main-sumary">
            <div class="main-sumary-item">
                <span>Cruises</span>
            </div>
            <div class="main-sumary-item">
                <span><?= date('d M Y', strtotime($_POST['departure'])) ?></span>
            </div>
            <div class="main-sumary-item">
                <span><?= obtenerDatoBarcoPorCodigoDispo($_POST['ship'], 'post_title') ?></span>
            </div>
        </div>
        <div class="cart-header" style="background: linear-gradient(90deg, rgba(62,147,143,1) 0%, rgba(49,113,138,1) 50%, rgba(50,90,149,1) 100%);">
            <div class="container">
                <div class="row py-3 align-items-center justify-content-center">
                    <div class="col-1">
                        <a href="<?= home_url('check-availability')?>/">
                            <span class="h4 text-light fas fa-arrow-left"></span>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <h2 class="cart-title text-white">1. <?= _e('ACOMODATION', 'gogalapagos') ?></h2>
                        <p class="m-0 cart-subtitle text-light"><?= _e('Select your cabin acomodation', 'gogalapagos') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN DE LA SECCION -->
<div class="section off-height section1 text-center">
    <div class="container">
        <form id="accommodation-form" role="form" method="post" action="<?= home_url('traveler-details') ?>?id=<?= $registro[0]->id ?>" enctype='application/json'>
            <input type="hidden" name="cookie_sesion" value="<?= $cookie ?>">
            <input type="hidden" name="ship" value="<?= $_POST['ship'] ?>">
            <input type="hidden" name="departure" value="<?= $_POST['departure'] ?>">
            <input type="hidden" name="promo" value="<?= $_POST['promo'] ?>">
            <input type="hidden" name="duration" value="<?= $_POST['duration'] ?>">
            <input type="hidden" name="adults" value="<?= $_POST['adults'] ?>">
            <input type="hidden" name="children" value="<?= $_POST['children'] ?>">
            <?php
            $cabinas = obtenerCabinasPorBarco( obtenerDatoBarcoPorCodigoDispo($_POST['ship'], 'ID') );
            ?>
            <div class="row py-3 align-items-center justify-content-center">
                <div class="col-3 mx-1 card p-2 inside-box little-box">
                    <table class="table table-sm table-borderless m-0">
                        <td><?= _e('Show all cabin categories', 'gogalapagos') ?></td>
                        <td id="show-cabins-list-filter"><i class="far fa-plus-square"></i></td>
                        
                        <!-- <td id="filter-status"><i class="fas fa-check-square"></i></td> -->
                    </table>

</div>










                
                <div class="col-3 mx-1 card p-2 inside-box little-box">
                    <?php
                    $pending = _x('Pending Assignment', 'gogalapagos');
                    $gest_word = _x('guests', 'gogalapagos');
                    ?>
                    <table class="table table-sm table-borderless m-0">
                        <td><?= $pending ?>: <?= $total_pax  ?> <?= $gest_word ?></td>
                        
                        <td>
                            <div class="checkout-btn-placeholder" style="display: none;">
                                <button id="go-checkout" type="button" class="btn btn-warning"><?= _e('Proceed to checkout', 'gogalapagos') ?></button>
                            </div>
                            <div id="shoping-status" class="shoping-status"><i class="fas fa-shopping-cart"></i></div>
                        </td>
                    </table>
                </div>

            </div>


            <div id="cabins-list-placeholder" class="row mb-3 align-items-center justify-content-center p-1 inside-box little-box" style="display: none;">
    <ul id="cabins-list" class="list-group list-group-horizontal cabins-list" style="padding-inline-start: 0;">
        <?php
        foreach ($cabinas as $cabina){
        echo '<li class="list-group-item cabin-list-item border-0">';
            echo '<div class="cabin-list-title standard-cabin-interior small text-muted '. $cabina->post_name .'" >' . $cabina->post_title . '</div>';
            echo '<div class="cabin-list-status" data-cabinbox="' . $cabina->ID . '"><i class="fas fa-check-square" style="cursor: pointer;"></i></div>';
        echo '</li>';
        }
        ?>
    </ul>
</div>








            <div class="cabin-boxes-placeholder d-flex flex-wrap justify-content-center">
                <?php
                foreach ($cabinas as $cabina){
                // Cabina codigo dispo
                $cabina_dispo_code = get_post_meta( $cabina->ID, META_PREFIX . 'dispo_ID', true);
                ?>
                <div id="<?= $cabina->ID ?>" class="cabin-box col-4" data-dispocode="<?= $cabina_dispo_code ?>">
                    <div class="card mb-4 rounded">
                        
                        <img class="card-img-top" src="<?= get_the_post_thumbnail_url($cabina->ID) ?>" >
                        
                        <div class="inside-box card-body bg-light">
                            <div class="row no-gutters p-3 text-left position-relative price-box bg-white rounded shadow" style="bottom: 50px;">
                                <h5 class="col-5 cabin-name" data-cabinid="<?= $cabina->ID ?>"><span><i class="far fa-caret-square-down" style="cursor: pointer;"></i></span>&nbsp;<?= $cabina->post_title ?></h5>
                                <div class="col text-right border-dark border-bottom-0 border-left-0 border-top-0">
                                    <div class="cabin-price">$ <span class="price">2346</span></div>
                                    <div class="text-muted small"><?= _e('per adult', 'gogalapagos') ?></div>
                                </div>
                                <div class="vert"></div>
                                <div class="col text-right">
                                    <div class="cabin-price">$ <span class="price">1173</span></div>
                                    <div class="text-muted small"><?= _e('per children', 'gogalapagos') ?></div>
                                </div>
                            </div>
                            
                            <?php
                            $caracteristicas = get_post_meta($cabina->ID, META_PREFIX . 'cabin_featurelist', false);
                            if ( count($caracteristicas[0]) > 0){
                            echo '<ul class="mb-5 text-left" id="featured-'. $cabina->ID .'" style="display: none;">';
                                foreach($caracteristicas[0] as $catacteristica){
                                echo '<li>' . $catacteristica . '</li>';
                                }
                            echo '</ul>';
                            }
                            ?>
                            <h5 class="text-center"><?= _e('Accommodation for this Cabin', 'gogalapagos') ?></h5>
                            <select class="form-control accommodation-items" name="accommodation-for-<?= $cabina->ID ?>">
                                <option value="0" data-peopleincabin="0"><?= _e('Select Accommodation') ?></option>
                                <option value="1" data-peopleincabin="1">1 ADULT</option>
                                <option value="1" data-peopleincabin="2">2 ADULTS</option>
                                <option value="2" data-peopleincabin="2">1 ADULT &amp; 1 CHILD</option>
                                <option value="3" data-peopleincabin="3">2 ADULTS &amp; 1 CHILD</option>
                                <option value="4" data-peopleincabin="3">3 ADULTS</option>
                                <option value="5" data-peopleincabin="4">3 ADULTS &amp; 1 CHILD</option>
                            </select>
                            <div class="row info-accommodation" style="display: none;">
                                <div class="col-xs-12">
                                    <p class="text-info"><?= _e('Please choose the accommodation to add this cabin. Thank you.', 'gogalapagos') ?></p>
                                </div>
                            </div>
                            <button class="btn btn-secondary submit-button add-cabin-btn mt-4" type="button" data-dispocode="<?= $cabina_dispo_code ?>" data-addcabin="<?= $cabina->ID ?>"><?= _e('Add Cabin') ?></button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row pb-5">
                <div class="col">
                    <button id="details" name="details" value="true" class="text-left btn btn-lg btn-secondary submit-button-details" type="submit">
                    <span class="next-step"><?= _e('Next Step', 'gogalapagos') ?>&nbsp;:</span>
                    <p class="m-0 next-step-title h4"><?= _e('TRAVELER DETAILS', 'gogalapagos') ?></p>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="cabinSumary" tabindex="-1" role="dialog" aria-labelledby="cabinSumary">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close p-4" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-chevron-left"></i></span></button>
                <h4 class="modal-title text-center" id="myModalLabel"><?= _e('Summary', 'gogalapagos') ?></h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-6">
                    <ul class="summary-cruise-list">
                        <li><strong><?= _e('Date', 'gogalapagos') ?></strong> <?= $_POST['departure'] ?></li>
                        <li><strong><?= _e('Ship', 'gogalapagos') ?></strong> <?= obtenerDatoBarcoPorCodigoDispo($_POST['ship'], 'post_title') ?></li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <ul class="summary-cruise-list">
                        <li><strong><?= _e('Itinerary', 'gogalapagos') ?></strong> </li>
                        <li><strong><?= _e('Duration', 'gogalapagos') ?></strong> <?= $_POST['duration'] . ' - ' . $_POST['duration'] - 1 ?></li>
                    </ul>
                </div>
                <div class="col-sm-12">
                    <div id="sumary-content">
                        <div class="panel-group" id="cabins-selected-accordion" role="tablist" aria-multiselectable="true"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="add-another-cabin-btn" type="button" class="btn btn-add-cabin pull-left" data-dismiss="modal"><?= _e('Add another cabin', 'gogalapagos') ?></button>
                <button id="submit-accommodation" type="button" class="btn submit-button pull-right" style="display: none;"><?= _e('Book now', 'gogalapagos') ?></button>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>