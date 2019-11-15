<?php  
if ( !defined( 'ABSPATH' ) ) {
  die( 'Direct access is forbidden.' );
}
?>

<div class="navbar fixed-top navbar-expand-md parabg p-5">
  </div>
  <div class="collapse" id="search">
    <div class="container fixed-top pt-3 mt-0 animated bounceInLeft d-none d-lg-block">
      <div class="row">
        <div class="col">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_theme_file_uri( 'images/dg_logo.png' ); ?>" alt="DigiCatt Logo" /></a>
        </div>
        <div class="col-6 pt-3">
          <input class="form-control form-control-lg " type="search" placeholder="Search...">
        </div>
        <div class="col pt-3 ">
          <button class="btn btn-lg btn-primary" type="submit">Search</button>
        </div>
        <div class="col pt-4">
          <a id="diss" class="hvr-underline-reveal" type="button" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="search"><i class="fa fa-undo fa-lg text-light"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="container fixed-top pt-1 mt-0 animated bounceInLeft d-lg-none">
      <div class="row">
        <div class="col">
          <img src="<?php echo get_theme_file_uri( 'images/dg_logo.png' ); ?>" alt="DigiCatt Logo" />

        </div>
      </div>
      <div class="row bg-dark pb-2">
        <div class="col-6 pt-2">
          <input class="form-control " type="search" placeholder="Search...">
        </div>
        <div class="col pt-2 ">
          <button class="btn btn-primary" type="submit">Search</button>
          <a id="diss2" class="px-3" type="" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="search"><i class="fa fa-undo fa-lg text-light"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
<nav class="navbar fixed-top navbar-expand-md navbar-dark esconder animated fadeInDown fast" role="navigation">
    <a class="navbar-brand d-md-none" href="#"><img src="<?php echo get_theme_file_uri( 'images/dg_logo.png' ); ?>"></a>
    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="d-md-flex d-block w-100 bg-nav">
      <div class="collapse navbar-collapse mx-auto w-auto justify-content-center rounded" id="navbarNavAltMarkup">
        <div>
    <?php
    wp_nav_menu( array(
      'theme_location'    => 'primary',
      'container'         => 'div',
      'container_class'   => 'navbar-collapse',
      // 'container_id'      => 'bs-example-navbar-collapse-1',
      'menu_class'        => 'navbar-nav',
      // 'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
      'walker'            => new WP_Bootstrap_Navwalker(),
    ) );
    ?>
  </div>
  </div>
  </div>
</nav>