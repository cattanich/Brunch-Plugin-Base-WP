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

$sql = "SELECT * FROM ".$wpdb->prefix."goquoting_detalle_cabina WHERE id_pedido = '".$_GET['id']."'";
$registro = $wpdb->get_results($sql);

if (!$registro){
    
    foreach ($_POST['cabins-selected'] as $cabin){
        $wpdb->insert(
            $wpdb->prefix . 'goquoting_detalle_cabina',
            array(
                'id_pedido' => $_GET['id'],
                'cabina' => $cabin['codigoCabina'],
                'acomodacion' => $cabin['acomodacionTexto'],
                'tarifa' => $cabin['precioCabina'],
            )
        );
    }
    $registro = $wpdb->get_results($sql);
}

?>
<style>
    <?php echo file_get_contents( RUTA_PLUGIN_BOOKING . 'app/css/booking-system-common-styles.css', true ); ?>
    /* TRAVELERS DETAILS VIEW */
    .birth-date .form-control{
        display: inline-block;
        max-width: 32.3333%;
    }
    .main-contact-text{
        font-size: 10px;
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
                <h1 class="cart-title">3. <?= _e('Details', 'gogalapagos') ?></h1>
                <p class="cart-subtitle"><?= _e('Complete travelers details') ?></p>
            </div>
        </div>            
    </div>
</div>
<form id="accommodation-form" role="form" method="post" action="<?= home_url('extras') ?>?id=<?= $_GET['id'] ?>">
    <input type="hidden" name="ship" value="<?= $_POST['ship'] ?>">
    <input type="hidden" name="departure" value="<?= $_POST['departure'] ?>">
    <input type="hidden" name="promo" value="<?= $_POST['promo'] ?>">
    <input type="hidden" name="duration" value="<?= $_POST['duration'] ?>">
    <input type="hidden" name="adults" value="<?= $_POST['adults'] ?>">
    <input type="hidden" name="children" value="<?= $_POST['children'] ?>">
    <?php 
    foreach($_POST['cabins-selected'] as $key => $cabina){
        
//        echo '<pre>';
//        print_r($cabina);
//        echo '</pre>';
        
        echo '<input type="hidden" name="cabins-selected['.$key.'][idCabina]" value="'.$cabina['idCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][codigoCabina]" value="'.$cabina['codigoCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][nombreCabina]" value="'.$cabina['nombreCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][acomodacionTexto]" value="'.$cabina['acomodacionTexto'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][personasEnCabina]" value="'.$cabina['personasEnCabina'].'">';
        echo '<input type="hidden" name="cabins-selected['.$key.'][precioCabina]" value="'.$cabina['precioCabina'].'">';
    } 
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <!-- Nav tabs -->
                <ul class="booking-details-tabs" role="tablist">
                    <?php for($i = 1; $i <= $total_pax; $i++){ ?>
                    <li role="presentation" class="booking-details-tab <?= $i == 1 ? 'active' : '' ?>"><a href="#pax-detail-<?= $i ?>" aria-controls="pax-detail-<?= $i ?>" role="tab" data-toggle="tab"><?php printf( _e('Traveler', 'gogalalagps') . ' %s', $i) ?> <?= $i == 1 ? '<span class="main-contact-text">' . _x('Main Contact', 'gogalapagos') . '</span>' : '';
                        ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <div class="tab-content">
                    <?php for($i = 1; $i <= $total_pax; $i++){ ?>
                    <div id="pax-detail-<?= $i ?>" class=" <?= $i == 1 ? 'active' : '' ?>">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label><?= $i ?> <?= _e('Title', 'gogalapagos')?>*</label>
                                    <select class="form-control" name="traveler[<?= $i ?>][title]" <?= $i == 1 ? 'required' : '' ?>>
                                        <option value="Mr."><?= _e('Mr.', 'gogalapagos') ?></option>
                                        <option value="Mrs."><?= _e('Mrs.', 'gogalapagos') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label><?= _e('First Name', 'gogalapagos')?>*</label>
                                    <input type="text" class="form-control" name="traveler[<?= $i ?>][fname]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label><?= _e('Last Name', 'gogalapagos')?>*</label>
                                    <input type="text" class="form-control" name="traveler[<?= $i ?>][lname]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label><?= _e('Gender', 'gogalapagos')?>*</label>
                                    <select class="form-control" name="traveler[<?= $i ?>][gender]" <?= $i == 1 ? 'required' : '' ?>>
                                        <option value="m"><?= _e('Male', 'gogalapagos') ?></option>
                                        <option value="f"><?= _e('Female', 'gogalapagos') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label><?= _e('Phone Number', 'gogalapagos')?>*</label>
                                    <input type="phone" class="form-control" name="traveler[<?= $i ?>][phone]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label><?= _e('E-mail Address', 'gogalapagos')?>*</label>
                                    <input type="email" class="form-control" name="traveler[<?= $i ?>][email]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group birth-date">
                                    <label><?= _e('Birth Date', 'gogalapagos')?>*</label>
                                    <br />
                                    <select class="form-control" name="traveler[<?= $i ?>][dirthdate]" <?= $i == 1 ? 'required' : '' ?>>
                                        <?php
                                            for ($d = 1; $d <= 31; $d++){
                                                echo '<option value="' . $d . '">' . $d . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <select class="form-control" name="traveler[<?= $i ?>][dirthmonth]" <?= $i == 1 ? 'required' : '' ?>>
                                        <?php
                                            for ($m = 0; $m <= 11; $m++){
                                                echo '<option value="' . $m . '">' . devolverMes($m) . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <?php
                                          $date = date('Y');
                                          $startdate = strtotime ( '-100 year' , strtotime ( $date ) ) ;
                                          $startdate = date ( 'Y' , $startdate );
                                    ?>
                                    <select class="form-control" name="traveler[<?= $i ?>][dirthyear]" <?= $i == 1 ? 'required' : '' ?>>
                                        <?php
                                        for ($y = $startdate; $y <= $date; $y++){
                                            echo '<option value="' . $y . '">' . $y . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label><?= _e('Country', 'gogalapagos')?>*</label>
                                    <select class="form-control" name="traveler[<?= $i ?>][country]" <?= $i == 1 ? 'required' : '' ?>>
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
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label><?= _e('City State', 'gogalapagos')?>*</label>
                                    <input type="text" class="form-control" name="traveler[<?= $i ?>][citystate]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label><?= _e('Street Address', 'gogalapagos')?>*</label>
                                    <input type="text" class="form-control" name="traveler[<?= $i ?>][streetaddress]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label><?= _e('City', 'gogalapagos')?>*</label>
                                    <input type="text" class="form-control" name="traveler[<?= $i ?>][city]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label><?= _e('Zip Code', 'gogalapagos')?>*</label>
                                    <input type="text" class="form-control" name="traveler[<?= $i ?>][zipcode]" <?= $i == 1 ? 'required' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <strong><?= _e('Special Requirements') ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label><?= _e('Diet', 'gogalapagos')?></label>
                                    <select class="form-control" name="traveler[<?= $i ?>][diet]">
                                        <option value="d0"><?= _e('Select Diet Requirements', 'gogalapagos') ?></option>
                                        <option value="d1"><?= _e('Diet 1', 'gogalapagos') ?></option>
                                        <option value="d2"><?= _e('Diet 2', 'gogalapagos') ?></option>
                                        <option value="d3"><?= _e('Diet 3', 'gogalapagos') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label><?= _e('Medical', 'gogalapagos')?></label>
                                    <select class="form-control" name="traveler[<?= $i ?>][medical]">
                                        <option value="m0"><?= _e('Select Medical Condition', 'gogalapagos') ?></option>
                                        <option value="m1"><?= _e('Medical 1', 'gogalapagos') ?></option>
                                        <option value="m2"><?= _e('Medical 2', 'gogalapagos') ?></option>
                                        <option value="m3"><?= _e('Medical 3', 'gogalapagos') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <button id="set-travelers-details" name="travelers-details" value="true" class="text-center submit-button center submit-button-cabin" type="submit">
                    <span class="next-step"><?= _e('Next Step', 'gogalapagos') ?></span>
                    <span class="next-step-title"><?= _e('Extras for your trip', 'gogalapagos') ?></span>
                </button>
            </div>
        </div>
    </div>
</form>