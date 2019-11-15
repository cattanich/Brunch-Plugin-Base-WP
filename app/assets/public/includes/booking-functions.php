<?php
global $wpdb;

// USO DE COOKIES
// CREAR COOKIE
function crearCookie(){
    if(!isset($_COOKIE['goquoting_cookie'])){
        $cookie = md5(strtotime(date('Ymd h:i:s')));
        setcookie('goquoting_cookie', $cookie, time() + (365 * 24 * 60 * 60), COOKIEPATH, COOKIE_DOMAIN) ; // 1 año
    }else{
        $cookie = $_COOKIE['goquoting_cookie'];
    }
    return $cookie;
}
// ELIMINAR COOKIE
function eliminarCookie(){
    unset($_COOKIE['goquoting_cookie']);
    setcookie('goquoting_cookie', '', time() - ( 15 * 60 ) , COOKIEPATH, COOKIE_DOMAIN);
};
// VERIFICAR VALIDEZ DE LA COOKIE
function empezarProceso(){
    wp_redirect( home_url('check-availability'), 302 );
    exit;
}
/******************/

// USO DE PEDIDOS
// CREAR PEDIDO
function realizarPedido($pedidoTemporal){    
    
    $pedido = crearPedidoEnWordpress($pedidoTemporal);  
    
    if($pedido){
        enviarMailPedido($pedidoTemporal);
        enviarAgradecimiento($pedidoTemporal);
    }
    
}
function crearPedidoEnWordpress($pedidoTemporal){
    
    // validar si existe el post en wordpress
    $args = array(
        'post_type' => 'gquote',
        'meta_key' => 'quote_ID',
        'meta_value' => $pedidoTemporal['quote']
    );
    $pedido = new WP_Query( $args );
    
    
    if($pedido->post_count > 0){
        return true;
    }else{
    
        $pedido = array(
            'post_title' => '@'. date('Y-m-d h:i:s') . ' QN' . $pedidoTemporal['quote'] . ' ' . $pedidoTemporal['ship'] . ' ADT' . $pedidoTemporal['adults'] .' CHD' . $pedidoTemporal['children'],
            'post_status' => 'publish',
            'post_type' => 'gquote',
            'meta_input' => array(
                $prefix . 'quote_ID' => $pedidoTemporal['quote'],
                $prefix . 'quote_status' => $pedidoTemporal['go-request'],
                $prefix . 'quote_ship' => $pedidoTemporal['ship'],
                $prefix . 'quote_departure' => $pedidoTemporal['departure'],
                $prefix . 'quote_promo' => $pedidoTemporal['promo'],
                $prefix . 'quote_duration' => $pedidoTemporal['duration'],
                $prefix . 'quote_adults' => $pedidoTemporal['adults'],
                $prefix . 'quote_children' => $pedidoTemporal['children'],
                $prefix . 'quote_cabins' => $pedidoTemporal['cabins-selected'],
                $prefix . 'quote_traveler' => $pedidoTemporal['traveler'],
                $prefix . 'quote_extras' => $pedidoTemporal['services'],
                $prefix . 'quote_billing_country' => $pedidoTemporal['billing-country'],
                $prefix . 'quote_billing_address' => $pedidoTemporal['billing-address'],
                $prefix . 'quote_billing_city' => $pedidoTemporal['billing-city'],
                $prefix . 'quote_billing_state' => $pedidoTemporal['billing-state'],
                $prefix . 'quote_billing_zipcode' => $pedidoTemporal['billing-zipcode'],
            )
        );
        wp_insert_post( $pedido );
        
        return $pedido;
    }
}


// RECUPERAR BARCO 
function obtenerObjetoBarcoPorCodigoDispo($dispocode){
$prefix = 'gg_'; 
    $args = array(
        'post_type'         => 'ggships',
        'meta_query'        => array(
            'relation' => 'AND',
            array(
                'key' => META_PREFIX . 'dispo_ID',
                'value' => $dispocode,
                'compare' => '='
            )
        )
    );
    $barco = get_posts($args);
    return $barco;
}
function obtenerDatoBarcoPorCodigoDispo($dispocode, $index){
    $barco = obtenerObjetoBarcoPorCodigoDispo($dispocode);
    $info = $barco[0]->$index;
    return $info;
}
// RECUPERAR LAS CABINAS DEL BARCO 
function obtenerCabinasPorBarco($id_barco){
    $args = array(
    'post_type' => 'ggcabins',
    'posts_per_page' => -1,
    'order'          => 'ASC',
    'orderby'        => 'menu_order',
    'meta_query' => array(
        array(
            'key'     => META_PREFIX . 'cabin_ship_id',
            'value'   => $id_barco,
            'compare' => 'LIKE',
        ),
    ));
    $cabinas = get_posts($args);
    
    return $cabinas;
}
function obtenerCabinasPorCodigoDispo($codigoCabina){
    $args = array(
    'post_type' => 'ggcabins',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key'     => META_PREFIX . 'cabin_eligos_code',
            'value'   => $codigoCabina,
            'compare' => 'LIKE',
        ),
    ));
    $cabinas = get_posts($args);
    
    return $cabinas;
}
// RECUPERAR PROMO DE WORDPRESS
function obtenerPromo($id_promo){    
    $promo = get_post($id_promo);
    return $promo;
}

// UTILIDADES
function mostrarArreglo($arreglo){
    echo '<pre>';
    print_r($arreglo);
    echo '</pre>';
}
// DEVOLVER MES EN LETRAS
function devolverMes($mesIndex){
    $meses = array(
        0 => _x('January'),
        1 => _x('February'),
        2 => _x('March'),
        3 => _x('April'),
        4 => _x('May'),
        5 => _x('June'),
        6 => _x('July'),
        7 => _x('August'),
        8 => _x('September'),
        9 => _x('October'),
        10 => _x('November'),
        11 => _x('December')
    );
    return $meses[$mesIndex];
}

function enviarAgradecimiento($pedidoTemporal){
        ob_start();
?>
<table align="center" cellpadding="0" cellspacing="0" border="0" width="600">
    <tr>
        <td align="center"><h1>THANK YOU</h1></td>
    </tr>
    <tr>
        <td>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos debitis voluptatibus repudiandae commodi perspiciatis laborum, minima, incidunt. A laudantium mollitia deserunt at natus, beatae nulla asperiores sint, numquam modi molestias.</p>
        </td>
    </tr>
    <tr>
        <td>
            <h2><?= _e('Quoting Information', 'gogalapagos')?></h2>
            <ul>
                <li><strong><?= _e('Ship', 'gogalapagos') ?> </strong> <?= $pedidoTemporal['ship'] ?></li>
                <li><strong><?= _e('Departure', 'gogalapagos') ?> </strong> <?= $pedidoTemporal['departure'] ?></li>
                <li><strong><?= _e('Cruise Length', 'gogalapagos') ?> </strong> <?= $pedidoTemporal['duration'] ?></li>
                <li><strong><?= _e('Adults', 'gogalapagos') ?> </strong> <?= $pedidoTemporal['adults'] ?></li>
                <li><strong><?= _e('Children', 'gogalapagos') ?> </strong> <?= $pedidoTemporal['children'] ?></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <h2>DATOS DE CONTACTO</h2>
            <ul>
                <li><strong>NOMBRE: </strong> <?= $pedidoTemporal['traveler'][1]['title']?> <?= $pedidoTemporal['traveler'][1]['fname']?> <?= $pedidoTemporal['traveler'][1]['lname']?></li>
                <li><strong>TEL&Eacute;FONO: </strong> <a href="phone:<?= $pedidoTemporal['traveler'][1]['phone']?>"><?= $pedidoTemporal['traveler'][1]['phone']?></a></li>
                <li><strong>EMAIL: </strong> <a href="mailto:<?= $pedidoTemporal['traveler'][1]['email']?>"><?= $pedidoTemporal['traveler'][1]['email']?></a></li>
            </ul>
        </td>
    </tr>
</table>
<?php
    
    $body = ob_get_clean();

    $subject = 'Your next adventure';

    require_once ABSPATH . WPINC . '/class-phpmailer.php';

    $mail = new PHPMailer( true );

    $mail->AddAddress($pedidoTemporal['traveler'][1]['email'], $pedidoTemporal['traveler'][1]['fname'] . ' ' . $pedidoTemporal['traveler'][1]['lname']);    
    $mail->FromName = 'Go Galapagos';
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->IsHTML();
    $mail->CharSet = 'utf-8';
    $mail->Send();
    
}

function enviarMailPedido($pedidoTemporal){
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
    
    ob_start();
?>
<table align="center" cellpadding="0" cellspacing="0" border="0" width="600">
    <tr>
        <td align="center"><h1>PEDIDO WEB</h1></td>
    </tr>
    <tr>
        <td>
            <h2>DATOS DEL PEDIDO</h2>
            <ul>
                <li><strong>REFEFENCIA:</strong> <?= '@'. date('Y-m-d h:i:s') . ' QN' . $pedidoTemporal['quote'] . ' ' . $pedidoTemporal['ship'] . ' ADT' . $pedidoTemporal['adults'] .' CHD' . $pedidoTemporal['children'] ?></li>
                <li><strong>BARCO: </strong> <?= $pedidoTemporal['ship'] ?></li>
                <li><strong>SALIDA: </strong> <?= $pedidoTemporal['departure'] ?></li>
                <li><strong>DURACION: </strong> <?= $pedidoTemporal['duration'] ?></li>
                <li><strong>ADULTOS: </strong> <?= $pedidoTemporal['adults'] ?></li>
                <li><strong>NIÑOS: </strong> <?= $pedidoTemporal['children'] ?></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <table align="center" cellpadding="0" cellspacing="0" border="0" width="600">
                <tr>
                    <td>
                        <h2>CABINAS SELECCIONADAS</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" border="1" width="500">
                            <thead>
                                <tr>
                                    <th align="center">COD</th>
                                    <th align="center">CABINA</th>
                                    <th align="center">ACOMODACION</th>
                                    <th align="center">TARIFA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($pedidoTemporal['cabins-selected'] as $cabin){
                                        echo '<tr>';
                                        echo '<td>' . $cabin['codigoCabina'] . '</td>';
                                        echo '<td>' . $cabin['nombreCabina'] . '</td>';
                                        echo '<td>' . $cabin['acomodacionTexto'] . '</td>';
                                        echo '<td align="right">$ ' . $cabin['precioCabina'] . '</td>';
                                        echo '</tr>';
                                        $subtotal += $cabin['precioCabina'];
                                    }
                                ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align="right">Subtotal</td>
                                    <td align="right">$ <?= $subtotal ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h2>DATOS DE CONTACTO</h2>
            <ul>
                <li><strong>NOMBRE: </strong> <?= $pedidoTemporal['traveler'][1]['title']?> <?= $pedidoTemporal['traveler'][1]['fname']?> <?= $pedidoTemporal['traveler'][1]['lname']?></li>
                <li><strong>TEL&Eacute;FONO: </strong> <a href="phone:<?= $pedidoTemporal['traveler'][1]['phone']?>"><?= $pedidoTemporal['traveler'][1]['phone']?></a></li>
                <li><strong>EMAIL: </strong> <a href="mailto:<?= $pedidoTemporal['traveler'][1]['email']?>"><?= $pedidoTemporal['traveler'][1]['email']?></a></li>
                <li><strong>PAIS: </strong> <?= $countries[$pedidoTemporal['traveler'][1]['country']] ?></li>
            </ul>
        </td>
    </tr>
</table>
<?php
    
    $body = ob_get_clean();

    $subject = 'Pedido Web';

    require_once ABSPATH . WPINC . '/class-phpmailer.php';

    $mail = new PHPMailer( true );

    $mail->AddAddress('web@kleintours.com.ec', 'Webmaster');
    $mail->AddAddress('websales@kleintours.com.ec', 'Web Sales');
    $mail->AddAddress('websales1@kleintours.com.ec', 'Web Sales 1');
    $mail->AddAddress('websales6@kleintours.com.ec', 'Web Sales 4');
    $mail->AddAddress('emilkb@yahoo.com', 'Emil Klein');
    $mail->FromName = 'Web Go Galapagos';
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->IsHTML();
    $mail->CharSet = 'utf-8';
    $mail->Send();

}
?>