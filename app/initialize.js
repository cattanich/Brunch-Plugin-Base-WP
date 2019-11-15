const $ = require('jquery');
window.jQuery = $;
window.$ = $;
var preloader = require('preloader-js');
var bootstrap = require('bootstrap');
var AOS = require('aos');
var scrollify = require('jquery-scrollify');
var jssocials = require('jssocials');


document.addEventListener('DOMContentLoaded', function() {

    console.log('made by @cattanich @digicattEC');
    // do your setup here

    // Hides the preloader with a fade out animation.
    preloader.hide();
    // Shows the preloader.
    preloader.show();

// RETURN TO TOP
$(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('.ret-top').fadeIn();
    } else {
        $('.ret-top').fadeOut();
    }
});

$(".ret-top").click(function () {
   //1 second of animation time
   //html works for FFX but not Chrome
   //body works for Chrome but not FFX
   //This strange selector seems to work universally
   $("html, body").animate({scrollTop: 0}, 1000);
});


// ANCHOR SCROLLING
// Select all links with hashes
$('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .not('[href="#pills-web"]')
  .not('[href="#pills-grafico"]') 
  .not('[href="#pills-publicitario"]') 

  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
    }
  });

    // (function() {
    //   'use strict';
    //   window.addEventListener('load', function() {
    //     // Fetch all the forms we want to apply custom Bootstrap validation styles to
    //     var forms = document.getElementsByClassName('needs-validation');
    //     // Loop over them and prevent submission
    //     var validation = Array.prototype.filter.call(forms, function(form) {
    //       form.addEventListener('submit', function(event) {
    //         if (form.checkValidity() === false) {
    //           event.preventDefault();
    //           event.stopPropagation();
    //         }
    //         form.classList.add('was-validated');
    //       }, false);
    //     });
    //   }, false);
    // })();







    $('[data-toggle="tooltip"]').tooltip();

    AOS.init();

    $.scrollify({
        section: ".section",
        sectionName: "section-name",
        interstitialSection: '.off-height',
        easing: "easeOutExpo",
        scrollSpeed: 1100,
        offset: 0,
        scrollbars: true,
        standardScrollElements: ".off-scroll",
        setHeights: true,
        overflowScroll: false,
        updateHash: true,
        touchScroll: true
    });

    $(".off-scrollify", function() {
        $.scrollify.disable()
    });

    $(".social").jsSocials({
        url: "http://www.digicatt.com.ec",
        text: "DigiCatt Media | Diseñamos ideas...",
        showCount: true,
        showLabel: false,
        shares: [
            { share: "twitter", via: "digicattEC", hashtags: "diseñografico,diseñoweb,wordpress" },
            "facebook",
            "googleplus",
            "pinterest",
            "whatsapp"
        ]
    });

        $(".blog-share").jsSocials({
        showCount: true,
        showLabel: false,
        shares: [
            { share: "twitter", via: "digicattEC", hashtags: "diseñografico,diseñoweb,wordpress" },
            "facebook",
            "googleplus",
            "pinterest",
            "whatsapp"
        ]
    });






    // ///// SCRIPTS
    // console.log('hello world');
    // $('a').tooltip({ placement: 'left' });
    // $(function() {
    //     $('[data-toggle="popover"]').popover()
    // });

    // AOS.init();

    // $("#fakeLoader").fakeLoader({

    //     timeToHide: 2000, //Time in milliseconds for fakeLoader disappear
    //     zIndex: 1001, // Default zIndex
    //     spinner: "", //Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7' 
    //     bgColor: "white", //Hex, RGB or RGBA colors
    //     imagePath: "" + templateUrl + "/images/dg_logo.png" //If you want can you insert your custom image
    //     // imagePath:""+templateUrl+"/assets/SVG-Loaders/svg-loaders/puff.svg" //If you want can you insert your custom image


    // });

    // $("#intro-fixed").responsiveSlides({
    //     auto: true,
    //     pager: false,
    //     speed: 4500,
    //     timeout: 5000
    // });



    // $('#intro-fixed').slick({
    //   dots: false,
    //   infinite: true,
    //   speed: 500,
    //   fade: true,
    //   // autoplay: true,
    //   cssEase: 'linear',
    //   arrows: false



    // });


    // $('#intro-fixed').slick({
    // centerMode:true,
    //     // slidesToShow: 1,
    //     // slidesToScroll: 3,
    //     dots: false,
    //     arrows: false,
    //     infinite: true,
    //     fade: true,
    //     autoplay: true,
    //     cssEase: 'linear',
    //     variableWidth: true,
    //     variableHeight: true
    // });

    // $('#intro-fixed').slick({
    //     autoplay: true,
    //     autoplaySpeed: 2000,
    //     // fade: true,
    //     arrows: false
    // });



    $('#intro-fixed').carousel({
        interval: 6000,
        pause: false,
        ride: 'carousel', 
    });

    $('#search').on('show.bs.collapse', function() { $('.esconder').addClass('d-none'); });
    $('#diss').click(function() { $('.esconder').removeClass('d-none'); });
    $('#diss2').click(function() { $('.esconder').removeClass('d-none'); });

    $(window).scroll(function() {
        // 100 = The point you would like to fade the nav in.

        if ($(window).scrollTop() > 100) {

            $('.parabg').addClass('bg-dark2');

        } else {


            $('.parabg').removeClass('bg-dark2');

        };
    });

    // $.scrollify({
    //     section: ".section",
    //     sectionName: "section-name",
    //     interstitialSection: '.off-height',
    //     easing: "easeOutExpo",
    //     scrollSpeed: 1100,
    //     offset: 0,
    //     scrollbars: true,
    //     standardScrollElements: ".off-scroll",
    //     setHeights: true,
    //     overflowScroll: false,
    //     updateHash: true,
    //     touchScroll: true
    // });

    $(".somos").on('click', function(event) {
        $.scrollify.move("#somos");

    });

    $(document).ready(function() {
        var myElement = $('#logo').find('img');
        $(window).on('scroll', function() {
            var st = $(this).scrollTop();
            myElement.css({
                'opacity': 1 - st / 200
            });
        });
    });

    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
        }
        var $subMenu = $(this).next('.dropdown-menu');
        $subMenu.toggleClass('show');


        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass('show');
        });


        return false;
    })














});