<?php
if(get_option( 'goquoting-link-system' ) != 'on'){
wp_redirect( home_url('request-a-quote'), 302 );
exit;
}
$prefix = 'gg_';
// ELIMINAR COOKIE
unset($_COOKIE['goquoting_cookie']);
setcookie('goquoting_cookie', '', time() - ( 15 * 60 ) , COOKIEPATH, COOKIE_DOMAIN);
?>
<div class="section off-height section1 text-center pt-5">
    <div class="py-4"></div>
    <div class="container pt-5">
        <form method="post">
            <div class="row justify-content-center">
                <div class="col-4">
                    <div class="row no-gutters">
                        <div class="col">
                            <div class="input-daterange input-group">
                                
                                
                                <input class="datepicker input-sm form-control" type="text" name="date-start" placeholder="<?= date('m/d/Y', strtotime("+37 days")) ?>" value="<?= $_POST['date-start'];?>" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-info"><i class="fa fa-calendar-alt"></i></button>
                                </div>
                            </div>
                            <p class="ok"><?= _e('From', 'gogalapagos') ?>&nbsp;<span class="text-muted small">(m/d/Y)</span></p>
                        </div>
                        
                        
                        <div class="col">
                            <div class="input-daterange input-group">
                                <input class="datepicker input-sm form-control" type="text" name="date-end" placeholder="<?php _e( 'Select end date', 'gogalapagos' ); ?>" value="<?= $_POST['date-end'];?>" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-info"><i class="fa fa-calendar-alt"></i></button>
                                </div>
                                
                            </div>
                            <p><?= _e('To', 'gogalapagos') ?>&nbsp;<span class="text-muted small">(m/d/Y)</span></p>
                        </div>
                        
                        
                    </div>
                </div>
                
                <div class="col-4">
                    <div class="row">
                        <div class="col">
                            <input type="number" value="<?= $_POST['adults'] ?>" min="1" max="9" step="1" name="adults" placeholder="<?= $_POST['adults'] ?>"/>
                            <p><?= _e('Adults', 'gogalapagos') ?></p>
                        </div>
                        <div class="col">
                            <input type="number" value="<?= $_POST['children'] ?>" min="0" max="6" step="1" name="children" placeholder="<?= $_POST['children'] ?>" />
                            <p><?= _e('Children', 'gogalapagos') ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-2">
                    <div class="row">
                        <div class="col">
                            <a id="more-than-nine" class="btn btn-outline-danger hidden" href="<?= home_url() ?>"><?= _e('Are you more than 9?', 'gogalapagos') ?></a>
                            <button class="btn btn-primary" id="search-dates" type="submit"><?= _e('Search', 'gogalapagos') ?></button>
                            <button class="btn btn-default" id="more-filters" type="button"><i class="fas fa-sliders-h"></i></button>
                        </div>
                    </div>
                    <div class="col">
                        <div id="filter-controls-placeholder" class="filter-controls-placeholder" style="display: none;">
                            <select name="duration-search-filter" class="form-control filter-control-select">
                                <option value="0"><?= _e('All Cruises Lengths', 'gogalapagos') ?></option>
                                <option value="4">4 <?= _e('Days', 'gogalapagos') ?></option>
                                <option value="8">8 <?= _e('Days', 'gogalapagos') ?></option>
                                <option value="12">12 <?= _e('Days', 'gogalapagos') ?></option>
                            </select>
                            <select name="ship-search-filter" class="form-control filter-control-select">
                                <option value="0"><?= _e('All Ships', 'gogalapagos') ?></option>
                                <option value="BAR003">Galapagos Legend</option>
                                <option value="BAR001">Coral Yacths</option>
                            </select>
                            <div class="form-group text-right">
                                <div class="offer-search-filter-placeholder">
                                    <label class="offer-search-filter-label" for="offer-search-filter"><?= _e('Offers Only') ?></label>
                                    <input class="offer-search-filter-checkbox" type="checkbox" name="offer-search-filter" id="offer-search-filter">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE LA SECCION -->
<div class="section off-height section1 text-center">
    <div class="container mt-2">
        <form id="set-date-form" method="post" action="<?= home_url('accommodation') ?>/">
            <?php
            $args = array(
            'post_type' => 'ggships',
            'posts_per_page' => 2,
            'orger_by' => 'post_date',
            'order' => 'DESC'
            );
            $barcos = get_posts($args);
            ?>
            
            <div class="cart-header" style="background: linear-gradient(90deg, rgba(62,147,143,1) 0%, rgba(49,113,138,1) 50%, rgba(50,90,149,1) 100%);">
                <div class="container">
                    <div class="row py-3 align-items-center justify-content-center">
                        <div class="col-3 text-center">
                            <h2 class="cart-title text-white">1. <?= _e('SHIPS', 'gogalapagos') ?></h2>
                            <p class="m-0 cart-subtitle text-light"><?= _e('Choose your ship and departure', 'gogalapagos') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <input id="input-ship" type="hidden" name="ship" value="">
            <input id="input-departure" type="hidden" name="departure" value="">
            <input id="input-promo" type="hidden" name="promo" value="">
            <input id="input-duration" type="hidden" name="duration" value="">
            <div class="row ships-container mt-4">
                <?php foreach ($barcos as $barco){ ?>
                <?php
                $args = array(
                'post_type' => 'ggcabins',
                'posts_per_page' => -1,
                'meta_query' => array(
                array(
                'key'     => $prefix . 'cabin_ship_id',
                'value'   => $barco->ID,
                'compare' => 'LIKE',
                ),
                ),
                );
                $cabinas = get_posts($args);
                ?>
                <div id="bar<?= $barco->ID ?>" class="ship-container col" data-shipcode="<?= get_post_meta($barco->ID, $prefix . 'dispo_ID', true) ?>">
                    <div class="ship-thumbnail">
                        <img src="<?= get_the_post_thumbnail_url( $barco->ID, 'medium_large' ) ?>" class="img-fluid rounded" alt="<?= $barco->post_title ?>">
                    </div>
                    <div class="position-relative" style="bottom: 46px;">
                        <div class="col">
                            <div class="bg-white rounded p-3 inside-box ship-departure-dates">
                                <h4 class="ship-title text-left"><?= $barco->post_title ?></h4>
                                <h5 class="text-left text-dark"><?= _e('Departure Dates', 'gogalapagos') ?></h5>
                                <!--                                 <p class="text-right"><span class="see-all-dates"><?= _e('See all Dates', 'gogalapagos') ?></span><span class="hide-all-dates text-right hidden"><?= _e('Hide Dates', 'gogalapagos') ?></span></p>
                                -->                                <div class="d-flex flex-wrap  ship-departure-dates-list">
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-06-05" data-promo="1207">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jun', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">5</div>
                                            
                                        </div>
                                        <i class="promo small fa fa-star"></i>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-06-12" data-promo="1207">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jun', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">12</div>
                                            
                                        </div>
                                        <i class="promo small fa fa-star"></i>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-06-18">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jun', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">18</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-06-25">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jun', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">25</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-06-30" data-promo="1218">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jun', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">30</div>
                                            
                                        </div>
                                        <i class="promo small fa fa-star"></i>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-07-02" data-promo="1207">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jul', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">2</div>
                                        </div>
                                        <i class="promo small fa fa-star"></i>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-07-08">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jul', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">8</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-07-16" data-promo="1218">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jul', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">16</div>
                                            
                                        </div>
                                        <i class="promo small fa fa-star"></i>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-07-23">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Jul', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">23</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="p-1 square-box departure-placeholder rounded" data-departure="2018-07-30">
                                            <div class="bg-secondary mb-1 p-1 departure-placeholder-month"><?= _e('Apr', 'gogalapagos') ?></div>
                                            <div class="departure-placeholder-date">30</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="promo-name" class="promo-name"></div>
                            </div>
                        </div>
                        <div class="col mt-3 text-left">
                            <div class="bg-white rounded p-3 inside-box duration-box hidden">
                                <h5 class="text-dark"><?= _e('Cruise Length', 'gogalapagos')?></h5>
                                <div class="d-flex flex-wrap justify-content-center duration-list text-center">
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="pt-2 duration-placeholder open" data-duration="4">
                                            <div class="mb-2 square-box duration-placeholder-date">
                                                <span class="h3 m-0">4</span>
                                                <div class="small days-word"><?= _e('Days', 'gogalapagos') ?></div>
                                            </div>
                                            <div class="bg-primary py-1 durarion-placeholder-info-box">
                                                <i class="fa fa-info-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="pt-2 duration-placeholder" data-duration="8">
                                            <div class="mb-2 square-box duration-placeholder-date">
                                                <span class="h3 m-0">8</span>
                                                <div class="small days-word"><?= _e('Days', 'gogalapagos') ?></div>
                                            </div>
                                            <div class="bg-primary py-1 durarion-placeholder-info-box">
                                                <i class="fa fa-info-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <div class="pt-2 duration-placeholder" data-duration="12">
                                            <div class="mb-2 square-box duration-placeholder-date">
                                                <span class="h3 m-0">12</span>
                                                <div class="small days-word"><?= _e('Days', 'gogalapagos') ?></div>
                                            </div>
                                            <div class="bg-primary py-1 durarion-placeholder-info-box">
                                                <i class="fa fa-info-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 list-inline-item position-relative bg-dark rounded text-light" style="width: 65px; cursor: pointer;">
                                        <a href="#" data-toggle="modal" data-target="#more-days-info">
                                            <div id="more-days-btn" class="text-light pt-4 duration-placeholder open">
                                                <div class="mt-2 square-box duration-placeholder-date">
                                                    <i class="h4 fas fa-plus"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <i class="h5 text-dark m-0 fa fa-arrow-circle-down" aria-hidden="true"></i>
                        <div class="col">
                            <div class="bg-white p-3 rounded text-left inside-box cabins-box hidden">
                                <h5 class="text-dark mb-4"><?= _e('Cabins available on this date', 'gogalapagos') ?></h5>
                                <ul class="list-group cabins-available-list">
                                    <?php foreach( $cabinas as $cabina ){ ?>
                                    <li class="list-group-item cabin-list-item border-0 p-1"><span class="pull-left cabin-list-name"><?= $cabina->post_title ?></span> <span class="float-right pr-1 cabin-list-price">$ 3250</span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } // FIN bucle barcos ?>
            </div>
            
            
            
            <div id="error-message" class="container" style="display: none;">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 text-center text-danger">
                        <div id="message-placeholder" class="message-placeholder">
                            <p><strong><?= _e('Please check the selection of the cruise departure date and duration. Thank you.','gogalapagos') ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-5">
                <div class="col">
                    <button id="set-date" name="availability" value="true" class="text-left btn btn-lg btn-danger submit-button-cabin" type="submit">
                    <span class="next-step"><?= _e('Next Step', 'gogalapagos') ?>&nbsp;:</span>
                    <p class="m-0 next-step-title h4"><?= _e('SELECT YOUR CABIN', 'gogalapagos') ?></p>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE LA SECCION -->
<!-- Modal MORE DAYS INFO -->
<div class="modal fade" id="more-days-info" tabindex="-1" role="dialog" aria-labelledby="moreDaysInfo">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-chevron-left"></i></span></button>
                <h4 class="modal-title" id="myModalLabel"><?= _e('13 days or more', 'gogalapagos') ?></h4>
            </div>
            <div class="modal-body text-center">
                <?= _e('If you want to consult itineraries of 13 days or more, please fill out the following form and one of our agent will contact you.','gogalapagos') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary text-center"><?= _e('Go to the form', 'gogalapagos')?></button>
            </div>
        </div>
    </div>
</div>
<?php
// RECUPERAR LAS PROMOCIONES
$args = array(
'post_type'         => 'ggspecialoffer',
'posts_per_page'    => -1
);
$ofertas = get_posts($args);
foreach($ofertas as $oferta){
?>
<!-- Modal PROMO PLACEHOLDER -->
<div class="modal fade ggspecialoffer" id="<?= $oferta->ID ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $oferta->ID ?>">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-chevron-left"></i></span></button>
                <h4 class="modal-title" id="offerTitle<?= $oferta->ID ?>"><?= $oferta->post_title ?></h4>
            </div>
            <div class="modal-body text-center">
                <p><?= esc_html($oferta->post_excerpt) ?></p>
                <img style="margin: 0 auto;" class="img-responsive" src="<?= get_the_post_thumbnail_url( $oferta->ID, 'medium' ) ?>" alt="<?= $oferta->post_title ?>">
            </div>
        </div>
    </div>
</div>
<?php } // Fin bucle modales para ofertas ?>