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

$sqlpax = "SELECT * FROM ".$wpdb->prefix."goquoting_detalle_pasajero WHERE id_pedido = " . $_GET['id'];
$pasajeros = $wpdb->get_results($sqlpax);

$sqlext = "SELECT * FROM ".$wpdb->prefix."goquoting_detalle_extras WHERE id_pedido = " . $_GET['id'];
$extras = $wpdb->get_results($sqlext);

if ($pasajeros && !$extras){
    $travelers = 1;
    foreach ($pasajeros as $pasajero){
        
        foreach($_POST['services'][$travelers] as $key => $cantidad){
            if($cantidad != 0){
                $wpdb->insert(
                    $wpdb->prefix . 'goquoting_detalle_extras',
                    array(
                        'id_pedido' => $_GET['id'],
                        'id_pasajero' => $pasajero->id,
                        'id_servicio' => $key,
                        'cantidad' => $cantidad,
                    )
                );
            }
        }
        $travelers++;
    }
}

$registros = $wpdb->get_results($sqlext);

?>
<style>
    <?php echo file_get_contents( RUTA_PLUGIN_BOOKING . 'app/css/booking-system-common-styles.css', true ); ?>
    /* EXTRAS VIEW */
    .cart-footer-placeholder{
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: white;
        color: black;
        text-align: left;
        padding: 10px 0;
    }
    @media screen and (min-width: 768px){
        .car-body{
            padding-bottom: 100px;
        }
    }
    .checkout-title{
        color: #191919;
        text-transform: uppercase;
        font-weight: bold;
    }
    .summary-cruise-list{
        margin-bottom: 36px;
    }
    .offer-price{
        color: green;
    }
    .offer-price small{
        color: dimgray;
    }
    .submit-payment{
        display: block;
        margin: 0 auto;
    }
    #request-quote{
        background: dimgray;
        color: white;
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
<form id="checkout-form" role="form" method="post" action="<?= home_url('thank-you') ?>/">
    <input type="hidden" name="quote" value="<?= $_GET['id'] ?>">
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
    foreach($_POST['traveler'] as $key => $pax){
        foreach($pax as $index => $value){
            echo '<input type="hidden" name="traveler['.$key.']['.$index.']" value="'.$value.'">';
        }
    }
    foreach($_POST['services'] as $key => $pax){
        foreach($pax as $index => $value){
            echo '<input type="hidden" name="services['.$key.']['.$index.']" value="'.$value.'">';
        }
    }
    ?>
    <input type="hidden" name="go-request" value="1">
    <div class="cart-header">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="cart-title">5. <?= _e('Confirmation &amp; Payment', 'gogalapagos') ?></h1>
                    <p class="cart-subtitle"><?= _e('Enter your Payment details') ?></p>
                </div>
            </div>           
        </div>
    </div>
    <div class="car-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <h2 class="checkout-title text-center"><?= _e('Summary', 'gogalapagos')?></h2>
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
                    <div class="col-xs-12">
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
                                                echo '<div class="pull-right cabin-price">$ ' . $value['precioCabina'] . '</div>';
                                                echo '</li>';

                                                $precio = strval($value['precioCabina']);

                                                $subtotal_cabinas += (int)$precio;
                                            }
                                            ?>  
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <!-- LISTADO EXTRAS -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#extras" href="#extras" aria-expanded="true" aria-controls="collapseOne"><?= _e('Travelers Extras','gogalapagos') ?></a>
                                            <span class="fas fa-chevron-down pull-right"></span>
                                        </h4>
                                    </div>
                                    <div id="extras" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <ol class="sumary-cabins-list">
                                            <?php 
                                            foreach($_POST['services'] as $services){
                                                foreach($services as $servicio => $cantidad){
                                                    if($cantidad != 0){
                                                        echo '<li>';
                                                        echo '<h2 class="sumary-cabin-title">' . $servicio . '</h2>';
                                                        echo '<small class="sumary-cabin-features">Amount</small>';
                                                        echo '<div class="pull-right cabin-price"> ' . $cantidad . '</div>';
                                                        echo '</li>';
                                                    }
                                                }
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
                                                echo '<div class="pull-right cabin-price offer-price"><small>Save</small> $ ' . $special_price . '</div>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="checkout-title text-center"><?= _e('Billing Information', 'gogalapagos') ?></h2>
                        </div>
                        <div class="col-xs-12 text-right">
                            <div class="offer-search-filter-placeholder set-billing-info">
                                <label class="offer-search-filter-label" for="offer-kayat-traveler"><?= _e('Same as billing ') ?></label>
                                <input class="offer-search-filter-checkbox " type="checkbox" name="offer-kayat-traveler" id="same-billing">
                                <span></span>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><?= _e('Country', 'gogalapagos')?>*</label>
                                <select class="form-control" name="billing-country"required>
                                    <option value="0"><?= _e('Select Country', 'gogalapagos')?></option>
                                    <option value="AF">Afghanistan</option>
                                    <option value="AX">Åland Islands</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia, Plurinational State of</option>
                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="BN">Brunei Darussalam</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos (Keeling) Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo</option>
                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="CI">Côte d'Ivoire</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">Curaçao</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="VA">Holy See (Vatican City State)</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran, Islamic Republic of</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                    <option value="KR">Korea, Republic of</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Lao People's Democratic Republic</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macao</option>
                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia, Federated States of</option>
                                    <option value="MD">Moldova, Republic of</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="MP">Northern Mariana Islands</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PW">Palau</option>
                                    <option value="PS">Palestinian Territory, Occupied</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RE">Réunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russian Federation</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="BL">Saint Barthélemy</option>
                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="MF">Saint Martin (French part)</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                    <option value="SS">South Sudan</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syrian Arab Republic</option>
                                    <option value="TW">Taiwan, Province of China</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania, United Republic of</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TL">Timor-Leste</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="US">United States</option>
                                    <option value="UM">United States Minor Outlying Islands</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                    <option value="VN">Viet Nam</option>
                                    <option value="VG">Virgin Islands, British</option>
                                    <option value="VI">Virgin Islands, U.S.</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><?= _e('Street Address', 'gogalapagos')?>*</label>
                                <input type="text" class="form-control" name="billing-address" required>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label><?= _e('City', 'gogalapagos')?>*</label>
                                <input type="text" class="form-control" name="billing-city" required>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label><?= _e('State', 'gogalapagos')?>*</label>
                                <input type="text" class="form-control" name="billing-state" required>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label><?= _e('Zip Code', 'gogalapagos')?>*</label>
                                <input type="text" class="form-control" name="billing-zipcode" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="checkout-title text-center"><?= _e('Credit Card Information', 'gogalapagos') ?></h2>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><?= _e('Name on card', 'gogalapagos')?></label>
                                <input type="text" class="form-control" name="cd-name" >
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><?= _e('Card Number', 'gogalapagos')?></label>
                                <input type="number" max-length="16" class="form-control" name="cd-number" >
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label><?= _e('Exp. Month', 'gogalapagos')?></label>
                                        <select class="form-control" name="cd-exp-month" >
                                            <option value="0"></option>
                                            <option value="01"><?= _e('JAN', 'gogalapagos') ?></option>
                                            <option value="02"><?= _e('FEB', 'gogalapagos') ?></option>
                                            <option value="03"><?= _e('MAR', 'gogalapagos') ?></option>
                                            <option value="04"><?= _e('APR', 'gogalapagos') ?></option>
                                            <option value="05"><?= _e('MAY', 'gogalapagos') ?></option>
                                            <option value="06"><?= _e('JUN', 'gogalapagos') ?></option>
                                            <option value="07"><?= _e('JUL', 'gogalapagos') ?></option>
                                            <option value="08"><?= _e('AUG', 'gogalapagos') ?></option>
                                            <option value="09"><?= _e('SEP', 'gogalapagos') ?></option>
                                            <option value="10"><?= _e('OCT', 'gogalapagos') ?></option>
                                            <option value="11"><?= _e('NOV', 'gogalapagos') ?></option>
                                            <option value="12"><?= _e('DEC', 'gogalapagos') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label><?= _e('Exp. Year', 'gogalapagos')?></label>
                                            <?php
                                                $actual = date('Y');
                                                $vence = $actual + 6;
                                            ?>
                                        <select class="form-control" name="cd-exp-year" >
                                            <option value="0"></option>
                                            <?php for($i = $actual; $i <= $vence; $i++){ ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label><?= _e('CCV', 'gogalapagos')?></label>
                                        <input type="password" max-length="3" class="form-control" name="ccv-number" >
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
    </div>
    <div class="cart-footer-placeholder">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <button id="submit-payment" type="submit" class="btn submit-button pull-left btn-warning"><?= _e('Pay now', 'gogalapagos') ?></button><button type="buttom" id="send-as-quote" class="submit-button pull-right"><?= _e('Request a Quote') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>