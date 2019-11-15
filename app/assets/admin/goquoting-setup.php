<?php
/**
 * Este archivo contiene los codigo para generar los metaboxes para cada CPT
 */




/**
 * dataStructureConfiguration: acción que se ejecuta cuando los 
 * plugins se cargan. Se crean las tablas para almacenar tamporalmente
 * los datos del trayecto del pedido del usuario.
 */ 
function dataStructureConfiguration(){
    
    global $wpdb;
    
    $collate = '';
    if ( $wpdb->has_cap( 'collation' ) ) {
        $collate = $wpdb->get_charset_collate();
    }
    
    $tablepedido = $wpdb->prefix . 'goquoting_pedido';
    $tablepedido_detalle_cabina = $wpdb->prefix . 'goquoting_detalle_cabina';
    $tablepedido_detalle_pasajero = $wpdb->prefix . 'goquoting_detalle_pasajero';
    $tablepedido_detalle_extras = $wpdb->prefix . 'goquoting_detalle_extras';
    
    if ( $wpdb->get_var("SHOW TABLES LIKE '" . $tablepedido . "'") != $tablepedido ){
        // TABLA CABECERA PEDIDO
        $sql_ppal = 'CREATE TABLE `' . $tablepedido . '` (
        `id` BIGINT(20) UNSIGNED AUTO_INCREMENT,
        `cookie_sesion` VARCHAR(255),
        `fecha` DATETIME,
        `barco` VARCHAR(7),
        `f_salida` VARCHAR(10),
        `adultos` INT(4),
        `ninios` INT(4),        
        `duracion` INTEGER(1),
        `promo` INT(4),
        PRIMARY KEY (id)
        ) '.$collate.'; ';
        // TABLA DETALLE CABINA
        $sql_ppal .= 'CREATE TABLE `' . $tablepedido_detalle_cabina . '` (
        `id` BIGINT(20) UNSIGNED AUTO_INCREMENT,
        `id_pedido` BIGINT(20),
        `cabina` VARCHAR(10),
        `acomodacion` VARCHAR(80),
        `tarifa` BIGINT(20),
        PRIMARY KEY (id)
        ) '.$collate.'; ';
        // TABLA DETALLE PASAJEROS
        $sql_ppal .= 'CREATE TABLE `' . $tablepedido_detalle_pasajero . '` (
        `id` BIGINT(20) UNSIGNED AUTO_INCREMENT,
        `id_pedido` BIGINT(20),
        `principal` BOOLEAN,
        `facturar` BOOLEAN,
        `titulo` VARCHAR(10),
        `nombre` VARCHAR(250),
        `apellido` VARCHAR(250),
        `genero` VARCHAR(20),
        `telefono` VARCHAR(25),
        `email` VARCHAR(250),
        `dia` INT(4),
        `mes` INT(4),
        `anio` INT(4),
        `pais` VARCHAR(2),
        `provincia` INT(255),
        `calle` text,
        `ciudad` text,
        `codigo_postal` text,
        `dieta` INT(4),
        `cond_medica` INT(4),
        PRIMARY KEY (id)
        ) '.$collate.'; ';
        // TABLA DETALLE EXTRAS
        $sql_ppal .= 'CREATE TABLE `' . $tablepedido_detalle_extras . '` (
        `id` BIGINT(20) UNSIGNED AUTO_INCREMENT,
        `id_pedido` BIGINT(20),        
        `id_pasajero` BIGINT(20),        
        `id_servicio` BIGINT(20),
        `cantidad` INT(4),
        PRIMARY KEY (id)
        ) '.$collate.'; ';
        
        //var_dump($sql_ppal);
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        
        dbDelta($sql_ppal);
            
    }
}
add_action('plugins_loaded', 'dataStructureConfiguration');


/*** USUARIO Y ROLES DE USUARIO ***/
function go_web_sales_management_role() {
    add_role('go_web_sales',
        'Go Web Sales',
        array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'publish_posts' => false,
            'upload_files' => false,
        )
    );
}
add_action('admin_init','go_web_sales_management_role',50);

function go_add_role_caps() {
 
     // Add the roles you'd like to administer the custom post types
     $roles = array('go_web_sales', 'administrator');

     // Loop through each role and assign capabilities
     foreach($roles as $the_role) { 

          $role = get_role($the_role);

          $role->add_cap( 'read' );
          $role->add_cap( 'read_gquote');
          $role->add_cap( 'read_private_gquotes' );
          $role->add_cap( 'edit_gquote' );
          $role->add_cap( 'edit_gquotes' );
          $role->add_cap( 'edit_others_gquotes' );
          $role->add_cap( 'edit_published_gquotes' );
          $role->remove_cap( 'publish_gquotes' );
          $role->remove_cap( 'delete_others_gquotes' );
          $role->remove_cap( 'delete_private_gquotes' );
          $role->remove_cap( 'delete_published_gquotes' );

    }
}
add_action('admin_init','go_add_role_caps',999);

 
/**
 * goquoting_pedidos: se registra el custom post type para pedidos.
 * en este se agregan parametros para que NO se puedan agregar o eliminar
 * pedidos desde el administrador de WordPress.
 */ 
function goquoting_pedidos() {

	$labels = array(
		'name'                  => _x( 'Quotes', 'Post Type General Name', 'gogalapagos' ),
		'singular_name'         => _x( 'Quote', 'Post Type Singular Name', 'gogalapagos' ),
		'menu_name'             => __( 'Quotes', 'gogalapagos' ),
		'name_admin_bar'        => __( 'Quote', 'gogalapagos' ),
		'archives'              => __( 'Item Archives', 'gogalapagos' ),
		'attributes'            => __( 'Item Attributes', 'gogalapagos' ),
		'parent_item_colon'     => __( 'Parent Item:', 'gogalapagos' ),
		'all_items'             => __( 'All Items', 'gogalapagos' ),
		'add_new_item'          => __( 'Add New Item', 'gogalapagos' ),
		'add_new'               => __( 'Add New', 'gogalapagos' ),
		'new_item'              => __( 'New Item', 'gogalapagos' ),
		'edit_item'             => __( 'Edit Item', 'gogalapagos' ),
		'update_item'           => __( 'Update Item', 'gogalapagos' ),
		'view_item'             => __( 'View Item', 'gogalapagos' ),
		'view_items'            => __( 'View Items', 'gogalapagos' ),
		'search_items'          => __( 'Search Item', 'gogalapagos' ),
		'not_found'             => __( 'Not found', 'gogalapagos' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gogalapagos' ),
		'featured_image'        => __( 'Featured Image', 'gogalapagos' ),
		'set_featured_image'    => __( 'Set featured image', 'gogalapagos' ),
		'remove_featured_image' => __( 'Remove featured image', 'gogalapagos' ),
		'use_featured_image'    => __( 'Use as featured image', 'gogalapagos' ),
		'insert_into_item'      => __( 'Insert into item', 'gogalapagos' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'gogalapagos' ),
		'items_list'            => __( 'Items list', 'gogalapagos' ),
		'items_list_navigation' => __( 'Items list navigation', 'gogalapagos' ),
		'filter_items_list'     => __( 'Filter items list', 'gogalapagos' ),
	);
	$args = array(
		'label'                 => __( 'Quote', 'gogalapagos' ),
		'description'           => __( 'Web Quotes', 'gogalapagos' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true, // cambiar a false
		'show_ui'               => true, // cambiar a false
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-cart',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'query_var'             => 'web-sales',
		'rewrite'               => false,
		'capability_type'       => array('gquote', 'gquotes'),
//        'capabilities' => array(
//            'create_posts' => 'do_not_allow',
//            //'delete_published_posts' => 'do_not_allow',
//        ),
        'map_meta_cap' => true,
		'show_in_rest'          => true,
		'rest_base'             => 'web-sales',
		'rest_controller_class' => 'WP_REST_web_sales_Controller',
	);
	register_post_type( 'gquote', $args );

}
add_action( 'init', 'goquoting_pedidos', 0 );
?>