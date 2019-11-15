<?php
if ( !defined( 'ABSPATH' ) ) {
die( 'Direct access is forbidden.' );
}
get_header();
$img_front = get_post_meta ( get_the_ID(), 'imgslides', false);
the_post();
// Llamar posts de section
$args = array(
'post_type' => 'servicio',
'posts_per_page' => -1,
'order'    => 'ASC',
'orderby'  => 'menu_order'
);
$servicios = get_posts($args);
// Llamar imagenes meta de posts section
$images0 = get_post_meta ( $servicios[0]->ID, 'imgslides', false);
?>
<div class="preloader">
  <div class="animation animation-rotating-square"></div>
</div>
<!-- INTRO FIXED POSITION -->
<div id="intro-fixed" class="carousel slide carousel-fade" style="z-index: -1" >
	<div class="carousel-inner position-fixed " role="listbox">
		<?php
		$count = 0;
		foreach ($img_front as $imgs0 ) {
		$count++;
		?>
		<div class="carousel-item <?php if ($count == 1) {echo "active"; }  ?>" style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.5) 70%, rgba(0, 0, 0, 0.9) 100%),url(<?= wp_get_attachment_url( $imgs0 ) ?>)">
		</div>
		<?php } ?>
		
	</div>
</div>
<!-- INTRO SECTION -->
<div id="intro" class="section off-scroll">
	<div class="p-4"></div>
	<div class="container h-100">
		<div class="row no-gutters align-items-center h-100 border-bottom-0 text-white">
			<div class="col-md-10 offset-md-1 mx-auto">
				<div class="jumbotron">
					<h1 class="text-center display-4 font-weight-bold secondfont"><?= get_post_meta(get_the_ID(), 'titulo', true) ?></h1>
					<h3 class="font-weight-normal text-center"><?= get_post_meta(get_the_ID(), 'subtitulo', true) ?></h3>
					<div class="col-12 text-center">
						<a href=""><button type="button" class=" mt-2 btn btn-primary btn-lg">EMPEZAR PROYECTO ></button></a>
						
					</div>
				</div>
				<p></p>
				<div class="h1 text-light text-center bounce animated infinite slow"><i class="fas fa-angle-down"></i></div>
			</div>
		</div>
		
	</div>
	
</div>
<!-- SECTION -->
<div class="section off-scroll off-height">
	<div class="p-5"></div>
	<div class="container bg-white mt-5">
		<h1 class="mx-3 text-center titulos py-3 bg-secondary border border-gray">Servicios</h1>
		<div class="row p-4 justify-content-center">
			<div class="col-md-6 p-2">
				<div class="card bg-secondary">
					<a href="<?= get_permalink( $servicios[0]->ID ); ?>"><img src="<?= wp_get_attachment_url( get_post_meta ( $servicios[0]->ID, 'descimg', true) ) ?>" class="img-fluid hvr-bob w-100" alt="<?= get_the_title( get_post_meta ( $servicios[0]->ID, 'descimg', true) ); ?>"></a>
					<div class="card-body text-center">
						<h4 class="card-title titulos"><?= get_the_title( $servicios[0]->ID );  ?></h4>
						<p class="card-text text-left"><?= wp_trim_words( $servicios[0]->post_content, 25, NULL ); ?></p>
						<a href="<?= get_permalink( $servicios[0]->ID ); ?>" class="btn btn-outline-primary">Leer más</a>
					</div>
				</div>
			</div>
			<div class="col-md-6 p-2">
				<div class="card bg-secondary">
					<a href="<?= get_permalink( $servicios[1]->ID ); ?>"><img src="<?= wp_get_attachment_url( get_post_meta ( $servicios[1]->ID, 'descimg', true) ) ?>" class="img-fluid hvr-bob w-100" alt="<?= get_the_title( get_post_meta ( $servicios[1]->ID, 'descimg', true) ); ?>"></a>
					<div class="card-body text-center">
						<h4 class="card-title titulos"><?= get_the_title( $servicios[1]->ID );  ?></h4>
						<p class="card-text text-left"><?= wp_trim_words( $servicios[1]->post_content, 18, NULL ); ?></p>
						<a href="<?= get_permalink( $servicios[1]->ID ); ?>" class="btn btn-outline-primary">Leer más</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row px-4 pb-4 justify-content-center">
			<div class="col-md-4 p-2">
				<div class="card bg-secondary">
					<a href="<?= get_permalink( $servicios[2]->ID ); ?>"><img src="<?= wp_get_attachment_url( get_post_meta ( $servicios[2]->ID, 'descimg', true) ) ?>" class="img-fluid hvr-bob w-100" alt="<?= get_the_title( get_post_meta ( $servicios[2]->ID, 'descimg', true) ); ?>"></a>
					<div class="card-body text-center">
						<h4 class="card-title titulos"><?= get_the_title( $servicios[2]->ID );  ?></h4>
						<p class="card-text text-left"><?= wp_trim_words( $servicios[2]->post_content, 15, NULL ); ?></p>
						<a href="<?= get_permalink( $servicios[2]->ID ); ?>" class="btn btn-outline-primary">Leer más</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 p-2">
				<div class="card bg-secondary">
					<a href="<?= get_permalink( $servicios[3]->ID ); ?>"><img src="<?= wp_get_attachment_url( get_post_meta ( $servicios[3]->ID, 'descimg', true) ) ?>" class="img-fluid hvr-bob w-100" alt="<?= get_the_title( get_post_meta ( $servicios[3]->ID, 'descimg', true) ); ?>"></a>
					<div class="card-body text-center">
						<h4 class="card-title titulos"><?= get_the_title( $servicios[3]->ID );  ?></h4>
						<p class="card-text text-left"><?= wp_trim_words( $servicios[3]->post_content, 15, NULL ); ?></p>
						<a href="<?= get_permalink( $servicios[3]->ID ); ?>" class="btn btn-outline-primary">Leer más</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 p-2">
				<div class="card bg-secondary">
					<a href="<?= get_permalink( $servicios[4]->ID ); ?>"><img src="<?= wp_get_attachment_url( get_post_meta ( $servicios[4]->ID, 'descimg', true) ) ?>" class="img-fluid hvr-bob w-100" alt="<?= get_the_title( get_post_meta ( $servicios[4]->ID, 'descimg', true) ); ?>"></a>
					<div class="card-body text-center">
						<h4 class="card-title titulos"><?= get_the_title( $servicios[4]->ID );  ?></h4>
						<p class="card-text text-left"><?= wp_trim_words( $servicios[4]->post_content, 15, NULL ); ?></p>
						<a href="<?= get_permalink( $servicios[4]->ID ); ?>" class="btn btn-outline-primary">Leer más</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- SECTION -->
<div class="section off-scroll off-height">
	<div class="p-5"></div>
	<div class="container bg-white mt-5">
		<h1 class="mx-3 text-center titulos py-3 bg-secondary border border-gray"><?= the_title( '', '', true );  ?></h1>
		<div class="row mx-3 py-3 justify-content-center">
			<?= get_the_content( null, false ); ?>
		</div>
	</div>
</div>
<!-- SECTION -->
<div class="section off-scroll">
	<div class="p-5"></div>
	<div class="container bg-primary mt-5">
		seccion 3
	</div>
</div>
<?php get_footer(); ?>