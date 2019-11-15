<?php
include PATH_PLUGIN_BOOKING . '/public/includes/booking-functions.php';
global $post;
add_action( 'admin_menu', 'add_gquote_menu_bubble' );
function add_gquote_menu_bubble() {
global $menu;
$args = array(
'post_type' => 'gquote',
'posts_per_page' => -1,
'meta_query' => array(
'relation' => 'AND',
array(
'key' => 'quote_status',
'value' => 0
)
)
);
$quotes = get_posts($args);
if ( $quotes ) {
foreach ( $menu as $key => $value ) {
if ( $menu[$key][2] == 'edit.php?post_type=gquote' ) {
$menu[$key][0] .= ' <span class="gquote-not">' . count($quotes) . '</span>';
return;
}
}
}
}
add_action( 'admin_init' , 'inicializa_columnas' );
function inicializa_columnas(){
add_filter('manage_posts_columns', 'pedidos_columnas');
}
add_action('manage_posts_custom_column', 'pedidos_columnas_contenido', 10, 2);
function pedidos_columnas( $defaults )
{
if ( $_GET['post_type'] == 'gquote' ){
$defaults['title'] = 'Quote REF';
$defaults['country'] = __('Country', 'gogalapagos');
$defaults['quoting_status'] = __('Status', 'gogalapagos');
/*<img src="<?= RUTA_PLUGIN_BOOKING .'images/flags/'. strtolower(get_post_meta($post->ID, 'quote_billing_country', true)) ?>.png" alt="<?= $countries[get_post_meta($post->ID, 'quote_billing_country', true)] ?>">*/
//$defaults['quoting_total'] = __('Total', 'gogalapagos');
unset($defaults['language']);
unset($defaults['ratings']);
}
return $defaults;
}
function pedidos_columnas_contenido($column_name, $post_ID){
if ($column_name == 'quoting_status') {
echo ($cabin_eligos_id = get_post_meta( $post_ID, 'quote_status', TRUE )) == 0 ? '<p class="bg-info text-center">FOLLOW</p>' : '<p class="bg-success text-center">CONFIRMED</p>';
}
if ($column_name == 'country') {
echo '<img src="'
. RUTA_PLUGIN_BOOKING .'images/flags/'. strtolower(get_post_meta($post_ID, 'quote_billing_country', true))
. '.png" title="'. devolverPais(get_post_meta($post_ID, 'quote_billing_country', true)).'">';
}
}
function gquote_get_bootstrap(){
global $post_type;
if( 'gquote' == $post_type ){
wp_enqueue_style( 'gquote-bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
wp_enqueue_script( 'gquote-bootstrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );
wp_enqueue_script( 'gquote-admin-script', RUTA_PLUGIN_BOOKING . 'admin/scripts/goquoting-admin.js' );
}
?>
<link href="https://fonts.googleapis.com/css?family=Didact+Gothic" rel="stylesheet">
<style>
#adminmenu .gquote-not{
display: inline-block;
vertical-align: top;
margin: 1px 0 0 2px;
padding: 0 5px;
min-width: 7px;
height: 17px;
border-radius: 11px;
background-color: orange;
color: #000;
font-size: 9px;
line-height: 17px;
text-align: center;
z-index: 26;
}
.column-country{
width: 100px;
}
#print-area{
font-family: 'Didact Gothic', sans-serif;
font-size: 16px;
}
#sync-text-msg .dashicons{
margin-bottom: 18px;
}
#sync-text-msg .dashicons-image-rotate{
-webkit-animation:spin 1s linear infinite;
-moz-animation:spin 1s linear infinite;
animation:spin 1s linear infinite;
}
.estimated-total{
font-size: 28px;
font-weight: bold;
color: forestgreen;
}
.bg-info{
background: rgba(147,206,222,1);
background: -moz-linear-gradient(-45deg, rgba(147,206,222,1) 0%, rgba(117,189,209,1) 41%, rgba(73,165,191,1) 100%);
background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(147,206,222,1)), color-stop(41%, rgba(117,189,209,1)), color-stop(100%, rgba(73,165,191,1)));
background: -webkit-linear-gradient(-45deg, rgba(147,206,222,1) 0%, rgba(117,189,209,1) 41%, rgba(73,165,191,1) 100%);
background: -o-linear-gradient(-45deg, rgba(147,206,222,1) 0%, rgba(117,189,209,1) 41%, rgba(73,165,191,1) 100%);
background: -ms-linear-gradient(-45deg, rgba(147,206,222,1) 0%, rgba(117,189,209,1) 41%, rgba(73,165,191,1) 100%);
background: linear-gradient(135deg, rgba(147,206,222,1) 0%, rgba(117,189,209,1) 41%, rgba(73,165,191,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#93cede', endColorstr='#49a5bf', GradientType=1 );
}
.bg-success{
background: rgba(238,250,173,1);
background: -moz-linear-gradient(-45deg, rgba(238,250,173,1) 0%, rgba(187,199,122,1) 100%);
background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(238,250,173,1)), color-stop(100%, rgba(187,199,122,1)));
background: -webkit-linear-gradient(-45deg, rgba(238,250,173,1) 0%, rgba(187,199,122,1) 100%);
background: -o-linear-gradient(-45deg, rgba(238,250,173,1) 0%, rgba(187,199,122,1) 100%);
background: -ms-linear-gradient(-45deg, rgba(238,250,173,1) 0%, rgba(187,199,122,1) 100%);
background: linear-gradient(135deg, rgba(238,250,173,1) 0%, rgba(187,199,122,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eefaad', endColorstr='#bbc77a', GradientType=1 );
}
.quote-ref-title{
color: #2980b9; font-weight: bold; font-size: 32px; margin: 0;
}
.quote-ref-title+small{
font-size: 16px;
}
@-moz-keyframes spin { 100% { -moz-transform: rotate(-360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(-360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(-360deg); transform:rotate(-360deg); } }
</style>
<?php
}
add_action( 'admin_head', 'gquote_get_bootstrap' );
function gquote_register_meta_boxes_callback($post){
$countries = array
(
'AF' => 'Afghanistan',
'AX' => 'Aland Islands',
'AL' => 'Albania',
'DZ' => 'Algeria',
'AS' => 'American Samoa',
'AD' => 'Andorra',
'AO' => 'Angola',
'AI' => 'Anguilla',
'AQ' => 'Antarctica',
'AG' => 'Antigua And Barbuda',
'AR' => 'Argentina',
'AM' => 'Armenia',
'AW' => 'Aruba',
'AU' => 'Australia',
'AT' => 'Austria',
'AZ' => 'Azerbaijan',
'BS' => 'Bahamas',
'BH' => 'Bahrain',
'BD' => 'Bangladesh',
'BB' => 'Barbados',
'BY' => 'Belarus',
'BE' => 'Belgium',
'BZ' => 'Belize',
'BJ' => 'Benin',
'BM' => 'Bermuda',
'BT' => 'Bhutan',
'BO' => 'Bolivia',
'BA' => 'Bosnia And Herzegovina',
'BW' => 'Botswana',
'BV' => 'Bouvet Island',
'BR' => 'Brazil',
'IO' => 'British Indian Ocean Territory',
'BN' => 'Brunei Darussalam',
'BG' => 'Bulgaria',
'BF' => 'Burkina Faso',
'BI' => 'Burundi',
'KH' => 'Cambodia',
'CM' => 'Cameroon',
'CA' => 'Canada',
'CV' => 'Cape Verde',
'KY' => 'Cayman Islands',
'CF' => 'Central African Republic',
'TD' => 'Chad',
'CL' => 'Chile',
'CN' => 'China',
'CX' => 'Christmas Island',
'CC' => 'Cocos (Keeling) Islands',
'CO' => 'Colombia',
'KM' => 'Comoros',
'CG' => 'Congo',
'CD' => 'Congo, Democratic Republic',
'CK' => 'Cook Islands',
'CR' => 'Costa Rica',
'CI' => 'Cote D\'Ivoire',
'HR' => 'Croatia',
'CU' => 'Cuba',
'CY' => 'Cyprus',
'CZ' => 'Czech Republic',
'DK' => 'Denmark',
'DJ' => 'Djibouti',
'DM' => 'Dominica',
'DO' => 'Dominican Republic',
'EC' => 'Ecuador',
'EG' => 'Egypt',
'SV' => 'El Salvador',
'GQ' => 'Equatorial Guinea',
'ER' => 'Eritrea',
'EE' => 'Estonia',
'ET' => 'Ethiopia',
'FK' => 'Falkland Islands (Malvinas)',
'FO' => 'Faroe Islands',
'FJ' => 'Fiji',
'FI' => 'Finland',
'FR' => 'France',
'GF' => 'French Guiana',
'PF' => 'French Polynesia',
'TF' => 'French Southern Territories',
'GA' => 'Gabon',
'GM' => 'Gambia',
'GE' => 'Georgia',
'DE' => 'Germany',
'GH' => 'Ghana',
'GI' => 'Gibraltar',
'GR' => 'Greece',
'GL' => 'Greenland',
'GD' => 'Grenada',
'GP' => 'Guadeloupe',
'GU' => 'Guam',
'GT' => 'Guatemala',
'GG' => 'Guernsey',
'GN' => 'Guinea',
'GW' => 'Guinea-Bissau',
'GY' => 'Guyana',
'HT' => 'Haiti',
'HM' => 'Heard Island & Mcdonald Islands',
'VA' => 'Holy See (Vatican City State)',
'HN' => 'Honduras',
'HK' => 'Hong Kong',
'HU' => 'Hungary',
'IS' => 'Iceland',
'IN' => 'India',
'ID' => 'Indonesia',
'IR' => 'Iran, Islamic Republic Of',
'IQ' => 'Iraq',
'IE' => 'Ireland',
'IM' => 'Isle Of Man',
'IL' => 'Israel',
'IT' => 'Italy',
'JM' => 'Jamaica',
'JP' => 'Japan',
'JE' => 'Jersey',
'JO' => 'Jordan',
'KZ' => 'Kazakhstan',
'KE' => 'Kenya',
'KI' => 'Kiribati',
'KR' => 'Korea',
'KW' => 'Kuwait',
'KG' => 'Kyrgyzstan',
'LA' => 'Lao People\'s Democratic Republic',
'LV' => 'Latvia',
'LB' => 'Lebanon',
'LS' => 'Lesotho',
'LR' => 'Liberia',
'LY' => 'Libyan Arab Jamahiriya',
'LI' => 'Liechtenstein',
'LT' => 'Lithuania',
'LU' => 'Luxembourg',
'MO' => 'Macao',
'MK' => 'Macedonia',
'MG' => 'Madagascar',
'MW' => 'Malawi',
'MY' => 'Malaysia',
'MV' => 'Maldives',
'ML' => 'Mali',
'MT' => 'Malta',
'MH' => 'Marshall Islands',
'MQ' => 'Martinique',
'MR' => 'Mauritania',
'MU' => 'Mauritius',
'YT' => 'Mayotte',
'MX' => 'Mexico',
'FM' => 'Micronesia, Federated States Of',
'MD' => 'Moldova',
'MC' => 'Monaco',
'MN' => 'Mongolia',
'ME' => 'Montenegro',
'MS' => 'Montserrat',
'MA' => 'Morocco',
'MZ' => 'Mozambique',
'MM' => 'Myanmar',
'NA' => 'Namibia',
'NR' => 'Nauru',
'NP' => 'Nepal',
'NL' => 'Netherlands',
'AN' => 'Netherlands Antilles',
'NC' => 'New Caledonia',
'NZ' => 'New Zealand',
'NI' => 'Nicaragua',
'NE' => 'Niger',
'NG' => 'Nigeria',
'NU' => 'Niue',
'NF' => 'Norfolk Island',
'MP' => 'Northern Mariana Islands',
'NO' => 'Norway',
'OM' => 'Oman',
'PK' => 'Pakistan',
'PW' => 'Palau',
'PS' => 'Palestinian Territory, Occupied',
'PA' => 'Panama',
'PG' => 'Papua New Guinea',
'PY' => 'Paraguay',
'PE' => 'Peru',
'PH' => 'Philippines',
'PN' => 'Pitcairn',
'PL' => 'Poland',
'PT' => 'Portugal',
'PR' => 'Puerto Rico',
'QA' => 'Qatar',
'RE' => 'Reunion',
'RO' => 'Romania',
'RU' => 'Russian Federation',
'RW' => 'Rwanda',
'BL' => 'Saint Barthelemy',
'SH' => 'Saint Helena',
'KN' => 'Saint Kitts And Nevis',
'LC' => 'Saint Lucia',
'MF' => 'Saint Martin',
'PM' => 'Saint Pierre And Miquelon',
'VC' => 'Saint Vincent And Grenadines',
'WS' => 'Samoa',
'SM' => 'San Marino',
'ST' => 'Sao Tome And Principe',
'SA' => 'Saudi Arabia',
'SN' => 'Senegal',
'RS' => 'Serbia',
'SC' => 'Seychelles',
'SL' => 'Sierra Leone',
'SG' => 'Singapore',
'SK' => 'Slovakia',
'SI' => 'Slovenia',
'SB' => 'Solomon Islands',
'SO' => 'Somalia',
'ZA' => 'South Africa',
'GS' => 'South Georgia And Sandwich Isl.',
'ES' => 'Spain',
'LK' => 'Sri Lanka',
'SD' => 'Sudan',
'SR' => 'Suriname',
'SJ' => 'Svalbard And Jan Mayen',
'SZ' => 'Swaziland',
'SE' => 'Sweden',
'CH' => 'Switzerland',
'SY' => 'Syrian Arab Republic',
'TW' => 'Taiwan',
'TJ' => 'Tajikistan',
'TZ' => 'Tanzania',
'TH' => 'Thailand',
'TL' => 'Timor-Leste',
'TG' => 'Togo',
'TK' => 'Tokelau',
'TO' => 'Tonga',
'TT' => 'Trinidad And Tobago',
'TN' => 'Tunisia',
'TR' => 'Turkey',
'TM' => 'Turkmenistan',
'TC' => 'Turks And Caicos Islands',
'TV' => 'Tuvalu',
'UG' => 'Uganda',
'UA' => 'Ukraine',
'AE' => 'United Arab Emirates',
'GB' => 'United Kingdom',
'US' => 'United States',
'UM' => 'United States Outlying Islands',
'UY' => 'Uruguay',
'UZ' => 'Uzbekistan',
'VU' => 'Vanuatu',
'VE' => 'Venezuela',
'VN' => 'Viet Nam',
'VG' => 'Virgin Islands, British',
'VI' => 'Virgin Islands, U.S.',
'WF' => 'Wallis And Futuna',
'EH' => 'Western Sahara',
'YE' => 'Yemen',
'ZM' => 'Zambia',
'ZW' => 'Zimbabwe',
);
$prefix = 'gg_';
$cabinas = get_post_meta($post->ID, 'quote_cabins', false);
$cabinas = array_shift($cabinas);
$extrasArray = get_post_meta($post->ID, 'quote_extras', false);
$extrasArray = array_shift($extrasArray);
$travelers = get_post_meta($post->ID, 'quote_traveler', false);
$travelers = array_shift($travelers);
$status = get_post_meta($post->ID, 'quote_status', true);
$qcode = get_post_meta($post->ID, 'quote_qcode', true);
?>
<input id="quoteID" type="hidden" name="quoteID" value="<?= $_GET['post'] ?>">
<div id="print-area" style="padding: 20px;" class="bg-<?= ($status == 0) ? 'info' : 'success' ?>">
    <div class="row">
        <div class="col-sm-5">
            <h3 class="quote-ref-title"><?= _e('Quote Details', 'gogalapagos') ?> #<?= get_post_meta($post->ID, 'quote_ID', true) ?></h3>
            <small><strong>T-CODE:</strong> <span id="q-code"><?= (!$qcode) ? 'no Q Code yet!' : $qcode ?></span></small>
        </div>
        <div class="col-sm-7">
            <button title="Imprimir" class="btn btn-default pull-right" id="print-quote" type="button" onclick="printDiv()"><i class="glyphicon glyphicon-print"></i></button>
            <button title="Reenviar" class="btn btn-default pull-right" id="send-quote" type="button"><i class="glyphicon glyphicon-envelope"></i></button>
            <?php
            if ($status != 0 && !$qcode){
            echo '<button class="btn btn-default pull-right" id="sync-receptivo" type="button"><i class="dashicons dashicons-migrate"></i> Download to ELIGOS</button>';
            }
            ?>
        </div>
    </div>
    <div class="row" style="margin-top: 18px; margin-bottom: 36px;">
        <div class="col-xs-4">
            <h3 style="margin: 0; margin-bottom: 18px; font-weight: bold;"><?= _e('General', 'gogalapagos')?></h3>
            <h4 style="font-weight: bold;"><?= _e('Creation date', 'gogalapagos')?></h4>
            <p><?= $post->post_date ?></p>
            <h4 style="font-weight: bold;"><?= _e('Quote status', 'gogalapagos')?></h4>
            <?php if ($status != 1){ ?>
            <select id="quote_status" name="quote_status">
                <option value="0" <?= ($status == 0) ? 'selected' : '' ?>>Follow</option>
                <option value="1" <?= ($status == 1) ? 'selected' : '' ?>>Confirmed</option>
            </select>
            <?php }else{ ?>
            <p style="color: forestgreen; font-size: 24px;"><strong>Confirmed</strong></p>
            <?php } ?>
        </div>
        <div class="col-xs-4">
            <h3 style="margin: 0; margin-bottom: 18px; font-weight: bold;"><?= _e('Quote', 'gogalapagos')?></h3>
            <ul>
                <li><strong><?= _e('Vessel', 'gogalapagos') ?></strong><br /><?= obtenerDatoBarcoPorCodigoDispo(get_post_meta($post->ID, 'quote_ship', true), 'post_title') ?></li>
                <li><strong><?= _e('Departure', 'gogalapagos') ?></strong><br /><?= get_post_meta($post->ID, 'quote_departure', true) ?></li>
                <li><strong><?= _e('Starting Itinerary', 'gogalapagos') ?></strong><br /><?= get_post_meta($post->ID, 'quote_departure', true) ?></li>
                <li><strong><?= _e('Applied Promo', 'gogalapagos') ?></strong><br /><?= get_the_title(get_post_meta($post->ID, 'quote_promo', true)) ?></li>
                <li><strong><?= _e('Duration', 'gogalapagos') ?></strong><br /><?= get_post_meta($post->ID, 'quote_duration', true) . ' Days' ?> - <?= get_post_meta($post->ID, 'quote_duration', true) - 1 . ' Nights' ?></li>
                <li><strong><?= _e('Adults', 'gogalapagos') ?></strong> <?= get_post_meta($post->ID, 'quote_adults', true) ?> - <strong> <?= _e('Children', 'gogalapagos') ?></strong> <?= get_post_meta($post->ID, 'quote_children', true) ?></li>
            </ul>
        </div>
        <div class="col-xs-4">
            <h3 style="margin: 0; margin-bottom: 18px; font-weight: bold;"><?= _e('Contact & Billing', 'gogalapagos')?></h3>
            <?php $travelers = get_post_meta($post->ID, 'quote_traveler', false) ?>
            <ul>
                <li><strong><?= _e('Name', 'gogalapagos') ?></strong><br /><span style="font-size: 24px;"><?= $travelers[0][1]['title'] ?> <?= $travelers[0][1]['fname'] ?> <?= $travelers[0][1]['lname'] ?></span></li>
                <li><strong><?= _e('Email', 'gogalapagos') ?></strong><br /><a href="mailto:<?= $travelers[0][1]['email'] ?>"><?= $travelers[0][1]['email'] ?></a></li>
                <li><strong><?= _e('Phone', 'gogalapagos') ?></strong><br /><a href="tel:<?= $travelers[0][1]['phone'] ?>"><?= $travelers[0][1]['phone'] ?></a></li>
                <li><strong><?= _e('Address', 'gogalapagos') ?></strong></li>
                <li><?= get_post_meta($post->ID, 'quote_billing_address', true) ?>, <?= get_post_meta($post->ID, 'quote_billing_city', true) ?>, <?= get_post_meta($post->ID, 'quote_billing_state', true) ?>.</li>
                <li><?= $countries[get_post_meta($post->ID, 'quote_billing_country', true)] ?> - <?= get_post_meta($post->ID, 'quote_billing_zipcode', true) ?>.</li>
                <li><img src="<?= RUTA_PLUGIN_BOOKING .'images/flags/'. strtolower(get_post_meta($post->ID, 'quote_billing_country', true)) ?>.png" alt="<?= $countries[get_post_meta($post->ID, 'quote_billing_country', true)] ?>"></li>
            </ul>
        </div>
    </div>
    <div class="row" style="background: #fff; padding: 20px; border-radius: 10px;">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-sm-8">
                    <h3 style="margin: 0; margin-bottom: 18px; font-weight: bold;"><?= _e('Cabins list', 'gogalapagos')?></h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?= _e('Cabin Type', 'gogalapagos')?></th>
                                <th align="center" style="text-align: center;"><?= _e('Cabin Code', 'gogalapagos')?></th>
                                <th><?= _e('Accommodation', 'gogalapagos')?></th>
                                <th align="center" style="text-align: center;"><?= _e('Guest Accomm.', 'gogalapagos')?></th>
                                <th align="right" style="text-align: right;"><?= _e('Total Estimated', 'gogalapagos')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $precioTotal = 0;
                            foreach($cabinas as $cabina){
                            $precioTotal += $cabina['precioCabina'];
                            $idCabina = obtenerCabinasPorCodigoDispo($cabina['codigoCabina']);
                            $cabinMetas = get_post_meta($idCabina[0]->ID);
                            echo '<tr>';
                                echo '<td style="background: '.$cabinMetas[$prefix . 'cabin_category_color'][0].';"><strong>' . $cabina['nombreCabina'] . '</strong></td>';
                                echo '<td align="center">' . $cabina['codigoCabina'] . '</td>';
                                echo '<td>' . $cabina['acomodacionTexto'] . '</td>';
                                echo '<td align="center">' . $cabina['personasEnCabina'] . '</td>';
                                echo '<td align="right">$ ' . $cabina['precioCabina'] . '</td>';
                            echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4">
                    <h3 style="margin: 0; margin-bottom: 18px; font-weight: bold;"><?= _e('Traveler\'s Extras', 'gogalapagos')?></h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?= _e('Extra', 'gogalapagos')?></th>
                                    <th align="center" style="text-align: center;"><?= _e('Amount', 'gogalapagos')?></th>
                                    <th align="right" style="text-align: right;"><?= _e('Subtotal', 'gogalapagos')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $items = 0;
                                $extrassubtotal = 0;
                                foreach($extrasArray as $extras){
                                foreach($extras as $extra => $cantidad){
                                if($cantidad > 0){
                                $onboardservice = goGetPostBySlug($extra);
                                $onboard_price = get_post_meta($onboardservice[$items]->ID, $prefix . 'onboard_service_price', true);
                                echo '<tr>';
                                    echo '<td>' . esc_html($onboardservice[$items]->post_title) . '</td>';
                                    echo '<td align="center">' . $cantidad . '</td>';
                                    echo '<td align="right">$ ' . $onboard_price * $cantidad . '</td>';
                                echo '</tr>';
                                }
                                $extrassubtotal += $onboard_price * $cantidad;
                                }
                                $items++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <hr />
                    <h3 style="margin: 0; margin-bottom: 18px; font-weight: bold;"><?= _e('Travelers Information', 'gogalapagos')?></h3>
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php
                            $tab = 0;
                            foreach ($travelers[0] as $pax){
                            echo '<li role="presentation" class="';
                                echo ($tab == 0) ? 'active' : '';
                                $number = $tab + 1;
                                echo '"><a href="#pax-'. $tab .'" aria-controls="pax-'. $tab .'" role="tab" data-toggle="tab"><strong>Pax '. $number .'</strong></a></li>';
                                $tab++;
                                }
                                ?>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <?php
                                $tab = 0;
                                foreach ($travelers[0] as $pax){
                                echo '<div role="tabpanel" class="tab-pane ';
                                    echo ($tab == 0) ? 'active' : 'fade';
                                    $number = $tab + 1;
                                    echo '" id="pax-'. $tab .'">';
                                    // IMPRIMIR EL ARRAY DEL PAX
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h4 style="margin: 0; margin: 16px 0; font-weight: bold;"><?= _e('Personal Information', 'gogalapagos')?></h4>
                                            <ul>
                                                <li><strong>Title</strong> <?= $pax['title'] ?></li>
                                                <li><strong>Full Name</strong> <?= $pax['fname'] ?> <?= $pax['lname'] ?></li>
                                                <li><strong>Gender</strong> <?= ($pax['gender'] == 'm') ? 'Male' : ' Female' ?></li>
                                                <li><strong>Date of Birth</strong> <?= $pax['dirthdate'] ?>/<?= $pax['dirthmonth'] ?>/<?= $pax['dirthyear'] ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3">
                                            <h4 style="margin: 0; margin: 16px 0; font-weight: bold;"><?= _e('Contact Information', 'gogalapagos')?></h4>
                                            <ul>
                                                <li><strong>Address</strong> <?= $pax['streetaddress'] ?></li>
                                                <li><strong>City</strong> <?= $pax['city'] ?></li>
                                                <li><strong>State</strong> <?= $pax['citystate'] ?></li>
                                                <li><strong>Country</strong> <?= $countries[$pax['country']] ?></li>
                                                <li><strong>Phone</strong> <?= $pax['phone'] ?></li>
                                                <li><strong>Email</strong> <?= $pax['email'] ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <h4 style="margin: 0; margin: 16px 0; font-weight: bold;"><?= _e('Additional Information', 'gogalapagos')?></h4>
                                            <ul>
                                                <li>
                                                    <strong>Diet Preferences</strong><br />
                                                    <p><?= $pax['diet'] ?></p>
                                                </li>
                                                <li>
                                                    <strong>Medical Conditions</strong><br />
                                                    <p><?= $pax['medical'] ?></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                    //-------------------------//
                                echo '</div>';
                                $tab++;
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                                                    //    echo '<pre>';
                                                            //    var_dump($travelers[0]);
                        //    echo '</pre>';
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <p class="estimated-total">Estimated Total: $ <?= $precioTotal + $extrassubtotal ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="syncmsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Downloading to ELIGOS</h4>
                </div>
                <div id="sync-text-msg" class="modal-body bg-info text-center">
                    <i class="dashicons dashicons-image-rotate"></i>
                    <p>Connecting to ELIGOS...</p>
                </div>
                <div class="modal-footer hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="send-quote-to" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Send Quote of... <span id="contact-full-name"><?= $travelers[0][1]['title'] ?> <?= $travelers[0][1]['fname'] ?> <?= $travelers[0][1]['lname'] ?></span></h4>
                </div>
                <div id="sync-text-msg" class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label">From</label>
                                <input class="form-control" type="email" name="emailfrom" placeholder="websales@gogalapagos.com.ec">
                                <p class="help-block">if leave blank it will use websales@gogalapagos.com.ec</p>
                            </div>
                            <div class="form-group email-to">
                                <label class="control-label">To *</label>
                                <input class="form-control" type="email" name="emailto" placeholder="example@examplemail.com">
                                <p class="help-block">This field is REQUIRED.</p>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Top Message</label>
                                <textarea class="form-control" name="topmsg" placeholder="Hi!, Mr. Jhon Doe, thanks for choosing us..."></textarea>
                                <p class="help-block">This text will appear on top of the Quote.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="send-quote-now" type="button" class="btn btn-primary" onclick="enviarQuote()">Send</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    function gquote_register_meta_boxes() {
    add_meta_box( 'goquitong-quote-data', '<span class="dashicons dashicons-cart"></span> ' . __( 'Quote Info', 'textdomain' ), 'gquote_register_meta_boxes_callback', 'gquote' );
    }
    add_action( 'add_meta_boxes', 'gquote_register_meta_boxes' );
    function goGetPostBySlug($slug){
    $args = array(
    'name'        => $slug,
    'post_type'   => 'ggonboardservices',
    'post_status' => 'publish',
    'numberposts' => 1
    );
    $my_onboardservice = get_posts($args);
    return $my_onboardservice;
    }
    function goDownloadReceptivo(){
    global $wp_version;
    $url = 'http://test.gogalapagos.com/getjson/?token=rogbV19gAJo33sS9RVdb_xyn_6bCkUWR';
    $args = array(
    'timeout'     => 5,
    'redirection' => 5,
    'httpversion' => '1.0',
    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
    'blocking'    => true,
    'headers'     => array(),
    'cookies'     => array(),
    'body'        => null,
    'compress'    => false,
    'decompress'  => true,
    'sslverify'   => true,
    'stream'      => false,
    'filename'    => null
    );
    $response = wp_remote_get( $url, $args );
    //echo $response;
    $tquote = 'T-'.rand(1000, 9999).'-'.date('Y');
    update_post_meta($_POST['quoteId'], 'quote_qcode', $tquote);
    $tcode = get_post_meta($_POST['quoteId'], 'quote_qcode', true);
    
    echo $tcode;
    
    die();
    }
    add_action('wp_ajax_goDownloadReceptivo','goDownloadReceptivo');
    function enviar_quote_via_ajax(){
    $mailfrom = filter_input(INPUT_POST, 'mailfrom');
    $mailto = filter_input(INPUT_POST, 'mailto');
    $messagge = filter_input(INPUT_POST, 'messagge');
    $body = filter_input(INPUT_POST, 'body');
    ob_start();
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns:v="urn:schemas-microsoft-com:vml"
        xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:w="urn:schemas-microsoft-com:office:word"
        xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
        xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv=Content-Type content="text/html; charset=unicode">
            <meta name=ProgId content=Word.Document>
            <meta name=Generator content="Microsoft Word 15">
            <meta name=Originator content="Microsoft Word 15">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <!--[if !mso]>
            <style>
            v\:* {behavior:url(#default#VML);}
            o\:* {behavior:url(#default#VML);}
            w\:* {behavior:url(#default#VML);}
            .shape {behavior:url(#default#VML);}
            </style>
            <![endif]-->
            <title>U+1F6F3 Contact from Go Galapagos</title>
            <!--[if gte mso 9]><xml>
            <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            </o:OfficeDocumentSettings>
            </xml><![endif]-->
            <style>
            .mail-title{
            margin: 36px auto;
            text-align: center;
            }
            </style>
            <meta name=viewport
            content="width=device-width, user-scalable=no,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
            <meta name=format-detection content="telephone=no">
            <!--[if gte mso 9]><xml>
            <o:shapedefaults v:ext="edit" spidmax="1027"/>
            </xml><![endif]--><!--[if gte mso 9]><xml>
            <o:shapelayout v:ext="edit">
            <o:idmap v:ext="edit" data="1"/>
            </o:shapelayout></xml><![endif]-->
        </head>
        <body>
            <table border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#cccccc" style="width: 100%;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff" style="margin-top:35px;margin-bottom:35px;font-family:Verdana, Helvetica;">
                    <tr style="background-color: #232323;">
                        <td align="center">
                            <h1 class="mail-title" style="color: white;">Quote Information</h1>
                        </td>
                    </tr>
                    <?php if ($messagge){ ?>
                    <tr>
                        <td>
                            <p><?= $messagge ?></p>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td style="color:#222222!important; padding: 30px;">
                            <?= $body ?>
                        </td>
                    </tr>
                    <tr style="background-color: #003956; color: #ffffff; margin-top: 35px;">
                        <td align="center">
                            <p style="margin-top:35px;margin-bottom:35px; max-width: 450px;">This email was sent from Go Galapagos Website, on <?php echo date("d/m/Y") ?> <?php echo date("h:i") ?></p>
                        </td>
                    </tr>
                </table>
            </table>
        </body>
    </html>
    <?php
    $body = ob_get_clean();
    $subject = 'U+1F6F3 Contact from Go Galapagos';
    require_once ABSPATH . WPINC . '/class-phpmailer.php';
    $mail = new PHPMailer( true );
    
    $mail->AddAddress($mailto, 'Go Galapagos User');
    $mail->FromName = 'Go Galapagos';
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->IsHTML();
    $mail->CharSet = 'utf-8';
    $mail->Send();
    
    echo 'TRUE';
    die();
    }
    add_action('wp_ajax_enviar_quote_via_ajax', 'enviar_quote_via_ajax');
    function devolverPais($index){
    $countries = array
    (
    'AF' => 'Afghanistan',
    'AX' => 'Aland Islands',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua And Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia And Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'IO' => 'British Indian Ocean Territory',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos (Keeling) Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CD' => 'Congo, Democratic Republic',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'CI' => 'Cote D\'Ivoire',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands (Malvinas)',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island & Mcdonald Islands',
    'VA' => 'Holy See (Vatican City State)',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran, Islamic Republic Of',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IM' => 'Isle Of Man',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KR' => 'Korea',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Lao People\'s Democratic Republic',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libyan Arab Jamahiriya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'MX' => 'Mexico',
    'FM' => 'Micronesia, Federated States Of',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
    'MP' => 'Northern Mariana Islands',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestinian Territory, Occupied',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RU' => 'Russian Federation',
    'RW' => 'Rwanda',
    'BL' => 'Saint Barthelemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts And Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin',
    'PM' => 'Saint Pierre And Miquelon',
    'VC' => 'Saint Vincent And Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome And Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia And Sandwich Isl.',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard And Jan Mayen',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad And Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks And Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'GB' => 'United Kingdom',
    'US' => 'United States',
    'UM' => 'United States Outlying Islands',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VE' => 'Venezuela',
    'VN' => 'Viet Nam',
    'VG' => 'Virgin Islands, British',
    'VI' => 'Virgin Islands, U.S.',
    'WF' => 'Wallis And Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe',
    );
    return $countries[$index];
    }
    ?>