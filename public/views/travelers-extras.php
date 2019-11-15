<?php 
if (get_option( 'goquoting-link-system' ) != 'on'){
    wp_redirect( home_url('request-a-quote'), 302 );
    exit;
}
require_once PATH_PLUGIN_BOOKING . '/app/functions/booking-functions.php';
global $wpdb;
$prefix = 'gg_';
if (!isset($_GET['id']) && !isset($_COOKIE['goquoting_cookie'])){
    empezarProceso();
}

$total_pax = $_POST['adults'] +  $_POST['children'];

$sql = "SELECT * FROM ".$wpdb->prefix."goquoting_detalle_pasajero WHERE id_pedido = '".$_GET['id']."'";
$registro = $wpdb->get_results($sql);

if (!$registro){
    $ti = 0;
    foreach ($_POST['traveler'] as $traveler){
        $ppal = 0;
        if($ti == 0){
            $ppal = 1;
        }
        if 
        (
            !empty($traveler['fname']) ||
            !empty($traveler['lname'])
        ){
        
            $wpdb->insert(
                $wpdb->prefix . 'goquoting_detalle_pasajero',
                array(
                    'id_pedido' => $_GET['id'],
                    'principal' => $ppal,
                    'titulo' => $traveler['title'],
                    'nombre' => $traveler['fname'],
                    'apellido' => $traveler['lname'],
                    'genero' => $traveler['gender'],
                    'telefono' => $traveler['phone'],
                    'email' => $traveler['email'],
                    'dia' => $traveler['dirthdate'],
                    'mes' => $traveler['dirthmonth'],
                    'anio' => $traveler['dirthyear'],
                    'pais' => $traveler['country'],
                    'provincia' => $traveler['citystate'],
                    'calle' => $traveler['streetaddress'],
                    'ciudad' => $traveler['city'],
                    'codigo_postal' => $traveler['zipcode'],
                    'dieta' => $traveler['diet'],
                    'cond_medica' => $traveler['medical'],
                )
            );
        }
        $ti++;
    }
    $registro = $wpdb->get_results($sql);
}


$args = array(
    'post_type' => 'ggonboardservices',
    'posts_per_page' => -1,    
);
$onboardservices = get_posts($args);

//mostrarArreglo($onboardservices);
//$slugs = get_the_terms($onboardservices[0]->ID, 'onboard-service-package');
//echo '<pre>';
//var_dump( $slugs[0] );
//echo '</pre>';
//die();


?>
<style>
    <?php echo file_get_contents( RUTA_PLUGIN_BOOKING . 'app/css/booking-system-common-styles.css', true ); ?>
    /* EXTRAS VIEW */
    .square-box{
        position: relative;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        min-width: 48px;
        min-height: 48px;
        background: dimgray;
        margin-bottom: 10px;
    }
    .main-contact-text{
        font-size: 10px;
    }
    /* duration */
    .duration-list{
        display: flex;
        justify-content: space-between;
    }
    .pack-placeholder-date{
        font-size: 24px;
        line-height: 24px;
        background: #1f1f1f;
        margin: 0px;
    }
    .pack-placeholder.open .pack-placeholder-date{
        background: #1f1f1f;
        background: darkorange;
        box-shadow: 0px 2px 8px rgba(105, 105, 105, 0.3);
    }
    .days-word{
        font-size: 12px;
        line-height: 1;
    }
    .pack-placeholder-info-box{
        display: none;
        margin: 0 auto;
        margin-top: -5px;
        width: 44px;
        height: 32px;
        background: #be6902;
        align-items: center;
        justify-content: space-around;
        border-radius: 6px;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
        transition: top ease-in .2s;
        padding-top: 5px;
        color: white;
        cursor: pointer;
    }
    .pack-placeholder.open+.pack-placeholder-info-box{
        display: flex;
    }
    .btn-skip-extra{
        margin: 18px auto;
        color: #636363;
        display: block;
        border-radius: 6px;
        border: 2px solid #636363;
        background: transparent;
    }
    .duration-list{
        justify-content: flex-start
    }
    .offer-search-filter-placeholder label{
        transform: translateY(0);
        font-size: 18px;
    }
    .offer-search-filter-placeholder label div{
        font-size: 12px;
        color: gray;
    }
    .offer-search-filter-placeholder span{
        display: inline-flex;
        border: 1px solid #ccc;
        width: 34px;
        height: 34px;
        margin-left: 12px;
        padding: 5px;
        align-content: center;
        justify-content: center;
        background: white;
    }
    .offer-search-filter-placeholder span:before{
        content: '';
        position: relative;
        width: 100%;
        height: 100%;
        background: darkorange;
        display: none;

    }
    .offer-search-filter-placeholder.selected span:before{
        display:block;
    }
    .offer-search-filter-checkbox{
        display: none;
    }
    .aditional-activities-list{
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .additional-service-item{
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 18px 0;
    }
    .show-resume{
        padding: 16px;
        position: relative;
        background: #484848;
        color: white;
        margin-bottom: 36px; 
    }
    .show-resume:after{
        content: '';
        display: block;
        position: absolute;
        right: 12px;
        top: 14px;
        width: 10px;
        height: 10px;
        background: lime;
        border-radius: 100%;
    }
    .modal-footer.text-center{
        text-align: center !important;
    }
    .counter-controller{
        display: flex;
        justify-content: center;
        align-content: center;
        align-items: center;
        margin-bottom: 36px;
    }
    .counter-controller .pax-counter{
        max-width: 36px;
        margin: 0 36px;
        border: none;
        border-bottom: 1px solid black;
    }
    @media screen and (min-width: 768px){
        .extras-view .inside-box{
            width: 30%;
            margin: 10px;
            float: left;
            min-height: 220px;
        }
    }
</style>
<div class="main-sumary">
    <div class="main-sumary-item">
        <a href="<?= home_url('check-availability')?>/">
            <span class="fas fa-arrow-left"></span>
        </a>
    </div>
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
<div class="cart-header">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h1 class="cart-title">4. <?= _e('Extras', 'gogalapagos') ?></h1>
                <p class="cart-subtitle"><?= _e('Aditional Packages or your trip') ?></p>
            </div>
        </div>            
    </div>
</div>
<form id="extrax-form" role="form" method="post" action="<?= home_url('checkout') ?>?id=<?= $_GET['id'] ?>">
    <input type="hidden" name="ship" value="<?= $_POST['ship'] ?>">
    <input type="hidden" name="departure" value="<?= $_POST['departure'] ?>">
    <input type="hidden" name="promo" value="<?= $_POST['promo'] ?>">
    <input type="hidden" name="duration" value="<?= $_POST['duration'] ?>">
    <input type="hidden" name="adults" value="<?= $_POST['adults'] ?>">
    <input type="hidden" name="children" value="<?= $_POST['children'] ?>">
    <?php 
    foreach($_POST['cabins-selected'] as $key => $cabina){
        echo '<input type="hidden" name="cabins-selected['.$key.'][idCabina]" value="'.$cabina['idCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][codigoCabina]" value="'.$cabina['codigoCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][nombreCabina]" value="'.$cabina['nombreCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][acomodacionTexto]" value="'.$cabina['acomodacionTexto'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][personasEnCabina]" value="'.$cabina['personasEnCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][precioCabina]" value="'.$cabina['precioCabina'].'">';
    } 
    ?>
    <?php 
    foreach($_POST['traveler'] as $key => $pax){
        foreach($pax as $index => $value){
            echo '<input type="hidden" name="traveler['.$key.']['.$index.']" value="'.$value.'">';
        }
    } 
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <!-- Nav tabs -->
                <ul class="booking-details-tabs" role="tablist">
                    <?php for($i = 1; $i <= $_POST['adults']; $i++){ ?>
                    <li role="presentation" class="booking-details-tab <?= $i == 1 ? 'active' : '' ?>"><a href="#pax-extra-<?= $i ?>" aria-controls="pax-detail-<?= $i ?>" role="tab" data-toggle="tab"><?php printf( _e('Traveler', 'gogalalagps') . ' %s', $i) ?> <?= $i == 1 ? '<span class="main-contact-text">' . _x('Main Contact', 'gogalapagos') . '</span>' : '';
                        ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-default btn-skip-extra"><?= _e('Skip this Step') ?></button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="tab-content extras-view">
                    <?php for($i = 1; $i <= $_POST['adults']; $i++){ ?>
                    <div id="pax-extra-<?= $i ?>" class=" <?= $i > 1 ? 'fade' : '' ?> <?= $i == 1 ? 'active' : '' ?>">
                        <div class="inside-box">
                            <h3><?= _e('Select your drinks packages', 'gogalapagos')?></h3>
                            <ul class="list-inline duration-list">
                                <?php 
    //for($z = 1; $z <= 4; $z++){ 
                                $y = 1;
                                foreach($onboardservices as $servicio){
                                    $categoria = get_the_terms($servicio->ID, 'onboard-service-package');
                                    if ($categoria[0]->slug == 'drink-packages'){
                                ?>
                                <li>
                                    <div class="pack-placeholder" data-packcode="<?= $servicio->ID ?>" data-pax="<?= $i ?>">
                                        <div class="square-box pack-placeholder-date">
                                            <div class="days-word"><?= _e('Pack', 'gogalapagos') ?></div>
                                            <span><?= $y ?></span>
                                        </div>
                                        <input type="hidden" name="pax-extra-<?= $i ?>-drinkspackages-amount-<?= $servicio->ID ?>" data-packcode="<?= $servicio->ID ?>" data-pax="<?= $i ?>">
                                    </div>
                                    <div class="pack-placeholder-info-box" data-packcode="<?= $servicio->ID ?>" data-pax="<?= $i ?>">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                </li>
                                <?php 
                                    $y++; 
                                    } //Fin si
                                } // fin foreach
                                ?>
                            </ul>
                        </div>
                        <div class="inside-box internet-service-box">
                            <h3><?= _e('Choose your internet package', 'gogalapagos')?></h3>
                            <ul class="list-inline duration-list">
                                <?php 
                                    //for($z = 1; $z <= 4; $z++){ 
                                    $y = 1;
                                    foreach($onboardservices as $servicio){
                                        $categoria = get_the_terms($servicio->ID, 'onboard-service-package');
                                        if ($categoria[0]->slug == 'internet-packages'){
                                ?>
                                <li>
                                    <div class="pack-placeholder" data-packcode="<?= $z ?>">
                                        <div class="square-box pack-placeholder-date">
                                            <div class="days-word"><?= _e('Pack', 'gogalapagos') ?></div>
                                            <span><?= $y ?></span>
                                        </div>
                                    </div>
                                    <div class="pack-placeholder-info-box" data-packcode="<?= $servicio->ID ?>" data-pax="<?= $i ?>">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                </li>
                                <?php 
                                        $y++; 
                                        } //Fin si
                                    } // fin foreach
                                ?>
                            </ul>
                        </div>
                        <div class="inside-box">
                            <h3><?= _e('Additional Activities', 'gogalapagos')?></h3>
                            <ul class="aditional-activities-list">
                                <li>
                                    <div class="offer-search-filter-placeholder additional-service-item">
                                        <label class="offer-search-filter-label" for="kayak-service"><?= _e('Kayak') ?> <div>$ 50</div></label>
                                        <input id="offer-kayat-traveler-<?= $i ?>" class="offer-search-filter-checkbox " type="checkbox" name="offer-kayat-traveler-<?= $i ?>" id="offer-search-filter">
                                        <span></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="offer-search-filter-placeholder additional-service-item">
                                        <label class="offer-search-filter-label" for="wetsuit-service"><?= _e('Wetsuit') ?></label>
                                        <input id="offer-wetsuit-traveler-<?= $i ?>" class="offer-search-filter-checkbox" type="checkbox" name="offer-wetsuit-traveler-<?= $i ?>" id="offer-search-filter">
                                        <span></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="inside-box empty-box">
                            <button id="shoping-status" type="button" class="submit-button left show-resume"><i class="fas fa-2x fa-shopping-cart"></i></button>
                            <button id="set-date" name="availability" value="true" class="text-center submit-button right submit-button-extras" type="submit">
                                <span class="next-step"><?= _e('Next Step', 'gogalapagos') ?></span>
                                <span class="next-step-title"><?= _e('Confirmation', 'gogalapagos') ?></span>
                            </button>
                        </div>
                    </div>
                    <?php 
                                    foreach($onboardservices as $serviciomodal){
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="info-extra-<?= $i ?>-<?= $serviciomodal->ID ?>" data-pax="<?= $i ?>" tabindex="-1" role="dialog" aria-labelledby="cabinSumary">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-chevron-left"></i></span></button>
                                    <h4 class="modal-title text-center" id="myModalLabel"><?= $serviciomodal->post_title ?></h4>
                                </div>
                                <div class="modal-body">
                                    <h3>$ <?= get_post_meta($serviciomodal->ID, $prefix . 'onboard_service_price', true) ?></h3>
                                    <div id="sumary-content"><?= $serviciomodal->post_content ?></div>
                                </div>
                                <div class="modal-footer text-center">
                                    <h4><?= _e('Number of packages', 'gogalapagos') ?></h4>
                                    <div class="counter-controller">
                                        <div class="counter-operation counter-service counter-<?= $serviciomodal->ID ?> subtract" data-serviceid="<?= $serviciomodal->ID ?>" data-pax="<?= $i ?>">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <input id="counter-<?= $i ?>-<?= $serviciomodal->ID ?>" class="pax-counter" type="number" name="services[<?= $i ?>][<?= $serviciomodal->post_name ?>]" min="0" value="0" data-serviceid="<?= $serviciomodal->ID ?>">
                                        <div class="counter-operation counter-service counter-<?= $serviciomodal->ID ?> add" data-serviceid="<?= $serviciomodal->ID ?>" data-pax="<?= $i ?>">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                    </div>
                                    <button id="add-another-cabin-btn" type="button" class="btn btn-default submit-button center" data-dismiss="modal"><?= _e('Done', 'gogalapagos') ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cabinSumary" tabindex="-1" role="dialog" aria-labelledby="cabinSumary">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-chevron-left"></i></span></button>
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
                            <div class="panel-group" id="cabins-selected-accordion" role="tablist" aria-multiselectable="true">
                                <!-- LISTADO CABINAS -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#cabins" href="#cabins" aria-expanded="true" aria-controls="collapseOne"><?= _e('Selected cabins','gogalapagos') ?></a>
                                            <span class="fas fa-chevron-down pull-right"></span>
                                        </h4>
                                    </div>
                                    <div id="cabins" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <ol class="sumary-cabins-list">
                                            <?php 
                                            foreach($_POST['cabins-selected'] as $key => $value){
                                                echo '<li>';
                                                echo '<h2 class="sumary-cabin-title">' . $value['nombreCabina'] . '</h2>';
                                                echo '<small class="sumary-cabin-features">' . $value['acomodacionTexto'] . '</small>';
                                                echo '<div class="pull-right cabin-price">' . $value['precioCabina'] . '</div>';
                                                echo '</li>';

                                                $precio = strval($value['precioCabina']);

                                                $subtotal_cabinas += (int)$precio;
                                            }
                                            ?>  
                                            </ol>

                                        </div>
                                    </div>
                                </div>
                                <!-- LISTADO SPECIAL DEALS -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#deals" href="#deals" aria-expanded="true" aria-controls="collapseOne"><?= _e('Special deals','gogalapagos') ?></a>
                                            <span class="fas fa-chevron-down pull-right"></span>
                                        </h4>
                                    </div>
                                    <div id="deals" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <?php
                                                $special = get_post($_POST['promo']);
                                                $special_price = 750;
                                                echo '<h2 class="sumary-cabin-title">' . esc_html( $special->post_title ) . '</h2>';
                                                echo '<div class="pull-right cabin-price">$ ' . $special_price . '</div>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="add-another-cabin-btn" type="button" class="btn btn-add-cabin pull-left" data-dismiss="modal"><?= _e('Add more extras', 'gogalapagos') ?></button>
                    <button id="submit-accommodation" type="button" class="btn submit-button pull-right"><?= _e('Confirmation', 'gogalapagos') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>