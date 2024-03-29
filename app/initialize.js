// const $ = require('jquery');
// window.jQuery = $;
// window.$ = $;
// var preloader = require('preloader-js');



document.addEventListener('DOMContentLoaded', function() {

    console.log('Goquoting plugin made by @cattanich @digicattEC');
    




/* GO Quoting main js */
// DECLARACIONES
var datesShown = false;
var adults = 2;
var children = 0;
var total_pax = 0;
var finicio = $('input[name=date-start]').val();
var ffin = $('input[name=date-end]').val();
var salidas = '';
var promociones = '';
var personas_por_acomodar = 0;
var fecha_barco, duracion_barco;
var cabinas_seleccionadas = [];
var panel = '';
// FUNCIONES
function fechasMostrarOcultar(padre){
    if (datesShown){
        $('#'+padre).find('.ship-departure-dates-list').addClass('shown');
        $('#'+padre).find('.hide-all-dates').removeClass('hidden');
    }else{
        $('#'+padre).find('.ship-departure-dates-list').removeClass('shown');
        $('#'+padre).find('.see-all-dates').removeClass('hidden');
    }
}
// function mostrarMascara(){
//     var mascara = '';

//     mascara += '<div class="loading-mask">';
//     mascara += '<i class="fas fa-3x fa-spinner fa-spin"></i>';
//     mascara += '</div>';

//     $('.booking-page-main-container').prepend( mascara );

//     $('body').css('overflow', 'hidden');
//     $('.loading-mask').css('top', $(window).scrollTop() );

// }
// function quitarMascara(){
//     setTimeout( function(){
//         $('.loading-mask').remove();
//     }, 1000);
//     $('body').css('overflow', 'auto');
// }
function calcularInputPax(tipo, valor){
    switch (tipo){
        case 0:
            valor++;
            break;
        case 1:
            valor--;
            break;
    }
    return valor;
}
function validarTotalPax(){
    adults = $('#adults-counter');
    children = $('#children-counter');
    total_pax = parseInt( adults.val() ) + parseInt( children.val() );
    $('#more-filters').removeClass('hidden');
    $('#search-dates').removeClass('hidden');
    $('#more-than-nine').addClass('hidden');
    if (total_pax > 9){
        $('#more-filters').addClass('hidden');
        $('#search-dates').addClass('hidden');
        $('#more-than-nine').removeClass('hidden');
    }
    return total_pax;
}
function recuperarFechasSalidas(fini, ffin){
    // salidas = '';
    
    // $.ajax({
    //     url         : 'http://test.gogalapagos.com/getjson/?token=rogbV19gAJo33sS9RVdb_xyn_6bCkUWR',
    //     data        : {
            
    //     },
    //     dataType    : 'json',
    //     beforeSend  : function(){
    //         mostrarMascara();
    //     },
    //     error       : function(response){
    //         console.error('Error de AJAX', response);
    //     },
    //     success     : function(response, textStatus, jQxhr){
    //         salidas = response;
    //     }
    // }).done( function(){
    //     quitarMascara();
    // });
    
    // return salidas;
}
function mostrarErrorAcomodacion(padre){
    $('#' + padre).find('.info-accommodation').slideToggle(200);
}
function mostrarSumario(){
    llenarListaCabinasSumario();
    $('#cabinSumary').modal('toggle');
}
function llenarListaCabinasSumario(){
    
    $.each(cabinas_seleccionadas, function(index, value){

        panel = '<div class="panel panel-default" id="panel-'+index+'">';
        panel += '<div class="panel-heading" role="tab" id="heading-' + index + '">';
        panel += '<a role="button" data-toggle="collapse" data-parent="#accordion-' + index + '" href="#collapse-' + index + '" aria-expanded="true" aria-controls="collapse-' + index + '">';
        panel += '<span class="fas fa-chevron-down"></span> ';
        panel += value.nombreCabina;
        panel += '</a>';
        panel += '<button id="cabin-item-list-' + index + '" type="button" class="pull-right cabin-item-list-remove-btn" data-removethiselement="' + index + '">';
        panel += '<span class="fas fa-times"></span>';
        panel += '</button>';
        panel += '<span class="pull-right">$ ' + value.precioCabina + '</span>';
        panel += '</div>';
        panel += '<div id="collapse-' + index + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-' + index + '">';
        panel += '<div class="panel-body">';
        panel += '<strong>' + value.acomodacionTexto + '</strong>';
        panel += '</div>';
        panel += '</div>';
        panel += '</div>';        

        //console.warn(index, value);
    })
    
    $('#sumary-content .panel-group').append(panel);
}
function redibujarListaCabinasSumario(arreglo_cabinas){
    
    $('#sumary-content .panel-group').html('');
    panel = '';
    //if(arreglo_cabinas.length > 0){
        $.each(arreglo_cabinas, function(index2, value2){
            
            panel += '<div class="panel panel-default" id="panel-'+index2+'">';
            panel += '<div class="panel-heading" role="tab" id="heading-' + index2 + '">';
            panel += '<a role="button" data-toggle="collapse" data-parent="#accordion-' + index2 + '" href="#collapse-' + index2 + '" aria-expanded="true" aria-controls="collapse-' + index2 + '">';
            panel += '<span class="fas fa-chevron-down"></span> ';
            panel += value2.nombreCabina;
            panel += '</a>';
            panel += '<button id="cabin-item-list-' + index2 + '" type="button" class="pull-right cabin-item-list-remove-btn" data-removethiselement="' + index2 + '">';
            panel += '<span class="fas fa-times"></span>';
            panel += '</button>';
            panel += '<span class="pull-right">$ ' + value2.precioCabina + '</span>';
            panel += '</div>';
            panel += '<div id="collapse-' + index2 + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-' + index2 + '">';
            panel += '<div class="panel-body">';
            panel += '<strong>' + value2.acomodacionTexto + '</strong>';
            panel += '</div>';
            panel += '</div>';
            panel += '</div>';

            //console.warn(index2, value2, panel);
        });
    //}
    //console.log(panel);
    $('#sumary-content .panel-group').append(panel);
}
function cabinasLlenas(){
    $('.shoping-status').removeClass('accommodate');
    $('.shoping-status').addClass('done');
}
function cabinasPendiente(){
    $('.shoping-status').addClass('accommodate');
    $('.shoping-status').removeClass('done');
}
function calcularPaxPorAcomodar(){
    adults = parseInt( $('input[name=adults]').val() );
    children = parseInt( $('input[name=children]').val() );
    var pending_pax = $('#pending-pax');
    var pax_por_acomodar = adults + children;
    var personas_en_cabina = 0;
    
    
    $.each(cabinas_seleccionadas, function(index, value){
        //console.warn(value.personasEnCabina);
        personas_en_cabina += value.personasEnCabina;
    })
    console.log('holaxxx');
    if (pax_por_acomodar <= personas_en_cabina || pax_por_acomodar == personas_en_cabina){
        cabinasLlenas();
        $('.pending-text').hide();
        $('#add-another-cabin-btn').hide();
        $('.checkout-btn-placeholder').show();
        $('#submit-accommodation').show();
        pending_pax.text('0');
        console.log('aqui');
    }else{
        if (personas_en_cabina > pax_por_acomodar){
            cabinasLlenas();
            $('.pending-text').hide();
            $('#add-another-cabin-btn').hide();
            $('#submit-accommodation').hide();
            $('.checkout-btn-placeholder').show();
            pending_pax.text('0');
            console.log('aqui mayor');
        }else{
            pending_pax.text(pax_por_acomodar - personas_en_cabina);
            cabinasPendiente();
            $('#add-another-cabin-btn').show();
            $('.checkout-btn-placeholder').hide();
            $('#submit-accommodation').hide();
            $('.pending-text').show();
            console.log('aqui menor');
        }
    }
    pending_pax.text();
    console.log( pax_por_acomodar - personas_en_cabina );
}
// INICIALIZACIONES
// $('.input-daterange').datepicker({
//     startView: 2,
//     maxViewMode: 0,
//     autoclose: true,
//     format: "yyyy-mm-dd"
// });

/* READY */
$(document).ready( function(){
  
    // console.log('App started!');
    recuperarFechasSalidas(finicio, ffin);
    promociones = $('.ggspecialoffer');
    
})

/* EVENTOS  PARA DISPONIBILIDAD */
// BUSCAR FECHAS EN CLICK
$('#search-dates').click( function(){
    var inicio = $('input[name=date-start]').val();
    var fin = $('input[name=date-end]').val();
    recuperarFechasSalidas(inicio, fin);
    $.each(salidas, function(index, value){
       console.log(index); 
    });
})
// MOSTRAR MAS FILTROS
$('#more-filters').click( function(){
    var more_filter_button = $(this);
    more_filter_button.toggleClass('open');    
    $('#filter-controls-placeholder').slideToggle(200, function(){
        if (more_filter_button.hasClass('open')){
            more_filter_button.find('i').removeClass('fa-sliders-h');
            more_filter_button.find('i').addClass('fa-chevron-up');
        }else{
            more_filter_button.find('i').removeClass('fa-chevron-up');
            more_filter_button.find('i').addClass('fa-sliders-h');
        }
    });
})
// CALCULAR LOS PAX ADULTOS
$('.counter-adults').click( function(){
    adults = $('#adults-counter');
    var valor = adults.val();
    if( $(this).hasClass('add') ){
        adults.val( calcularInputPax(0, valor) );
    }else{
        if (valor > 1){
            adults.val( calcularInputPax(1, valor) );
        }
    }
    validarTotalPax();
})
// CALCULAR LOS PAX NIÑOS
$('.counter-children').click( function(){
    children = $('#children-counter');
    var valor = children.val();
    if( $(this).hasClass('add') ){
        children.val( calcularInputPax(0, valor) );
    }else{
        if (valor > 1){
            children.val( calcularInputPax(1, valor) );
        }
    }
    validarTotalPax();
})
// MOSTRAR Y OCULTAR LAS FECHAS
$('.hide-all-dates').click( function(){
    datesShown = false;
    $(this).addClass('hidden');
    fechasMostrarOcultar($(this).parents('.ship-container').attr('id'));
});    
$('.see-all-dates').click( function(){
    datesShown = true;
    $(this).addClass('hidden');
    fechasMostrarOcultar($(this).parents('.ship-container').attr('id'));
});
// SELECCIONAR LA FECHA
$('.departure-placeholder').click( function(){
    $('#error-message').hide();

    // mostrarMascara();

    var departure_promo = $(this).data('promo');
    var titulo_modal = $('#' + departure_promo).find('.modal-title').text();
    var promo_link = '';
    
    $('.duration-box').addClass('hidden');
    $('.cabins-box').addClass('hidden');
    $('.promo-name').html('');
    $(this).parents('.ship-container').find('.duration-box').removeClass('hidden');

    $('.departure-placeholder').removeClass('selected');
    $(this).addClass('selected');
    
    fecha_barco = $(this).parents('.ship-container').data('shipcode');

    $('#input-ship').val(fecha_barco);
    $('#input-departure').val($(this).data('departure'));
    $('#input-promo').val($(this).data('promo'));
    
    promo_link = '<strong>Offer available on this date</strong><br />';
    promo_link += '<a href="#" data-toggle="modal" data-target="#' + departure_promo + '">';
    promo_link += titulo_modal;
    promo_link += '</a>';

    $(this).parents('.ship-container').find('.promo-name').html(promo_link);
    if (departure_promo === undefined){
        $('#input-promo').val('false');
        $('#promo-name').html('')
    }

    // quitarMascara();

})
// SELECCIONAR DURACION
$('.duration-placeholder').click( function(){
    
    $('#error-message').hide();

    $('.cabins-box').addClass('hidden');
    $(this).parents('.ship-container').find('.cabins-box').removeClass('hidden');

    $('.duration-placeholder').removeClass('open');
    $(this).addClass('open');
    
    duracion_barco = $(this).parents('.ship-container').data('shipcode');
    
    $('#input-ship').val(duracion_barco);
    $('#input-duration').val($(this).data('duration'));
})
$('#set-date').click( function(e){
    e.preventDefault();
    if(fecha_barco === duracion_barco){
        //console.log('enviar');
        $('#error-message').hide();
        $('#set-date-form').submit();
    }else{
        $('#error-message').slideDown();
    }
    $('#extrax-form').submit();
})

/*  EVENTOS PARA ACOMODACION */

// MOSTRAR SUMARIO
$('#shoping-status').click( function(){
    $('#cabinSumary').modal('show');
})
// MOSTRAR CARACTERISTICAS CABINAS
$('.cabin-name').click( function(){
    var featured_id = $(this).data('cabinid');
    $('#featured-' + featured_id).slideToggle(200);
})
// MOSTRAR LISTADO FILTRO CABINAS
$('#show-cabins-list-filter').click( function(){
    var elemento = $(this).find('.fas');
    if(elemento.hasClass('fa-plus')){
        elemento.removeClass('fa-plus');
        elemento.addClass('fa-minus');
    }else{
        elemento.removeClass('fa-minus');
        elemento.addClass('fa-plus');
    }
    $('#cabins-list-placeholder').slideToggle(400);
})
// DESACTIVAR CABINA EN FILTRO
$('.cabin-list-status').click( function(){
    var cabinbox = $(this).data("cabinbox");
    $(this).toggleClass('hiddencabinbox');
    if($(this).hasClass('hiddencabinbox')){
        $('#' + cabinbox).hide();
    }else{
        $('#' + cabinbox).show();        
    }
})
// VALIDAR ACOMODACION
$('.accommodation-items').change( function(){
    var elemento = $(this);
    elemento.parents('.cabin-box').find('.info-accommodation').hide();
})
// AGREGAR CABINA
$('.add-cabin-btn').click( function(){
    var elemento = $(this);
    var acomodacion = $('select[name=accommodation-for-'+elemento.data('addcabin')+']');
    var cabina = '';
    if (acomodacion.val() == 0){
        mostrarErrorAcomodacion( elemento.data('addcabin') );
    }else{
        
        //agregar cabinas al arreglo
        cabina = {
            acomodacionTexto    :   $('select[name=accommodation-for-'+elemento.data('addcabin')+'] option:selected').text(),
            codigoCabina        :   elemento.data('dispocode'),
            idCabina            :   elemento.data('addcabin'),
            nombreCabina        :   elemento.parents('#'+elemento.data('addcabin')).find('.cabin-name').text(),
            precioCabina        :   elemento.parents('#'+elemento.data('addcabin')).find('.price').text(),
            personasEnCabina    :   $('select[name=accommodation-for-'+elemento.data('addcabin')+'] option:selected').data('peopleincabin')
        };
        $('select[name=accommodation-for-'+elemento.data('addcabin')+']').val(0);
        
        
        cabinas_seleccionadas.push(cabina);
        
//        $.each(cabinas_seleccionadas, function(index, values){
//            console.log(values);
//            $('input[name="cabins-selected['+index+'][acomodacionTexto]"]').val( values.acomodacionTexto );
//            $('input[name="cabins-selected['+index+'][codigoCabina]"]').val( values.codigoCabina );
//            $('input[name="cabins-selected['+index+'][idCabina]"]').val( values.idCabina );
//            $('input[name="cabins-selected['+index+'][nombreCabina]"]').val( values.nombreCabina );
//            $('input[name="cabins-selected['+index+'][personasEnCabina]"]').val( values.personasEnCabina );
//            $('input[name="cabins-selected['+index+'][precioCabina]"]').val( values.precioCabina );
//        });
//        
//        console.log($('input[name="cabins-selected[0][acomodacionTexto]"]'));
        
        calcularPaxPorAcomodar();
        
        mostrarSumario();        
    }
})

// ELIMINAR CABINA
$(document).on('click', '.cabin-item-list-remove-btn', function(){
    
    if (cabinas_seleccionadas.length > 1){
        console.log('tiene varios', cabinas_seleccionadas.length);
        cabinas_seleccionadas.splice($(this).data('removethiselement'), 1);
    }else{
        console.log('tiene ', cabinas_seleccionadas.length);
        cabinas_seleccionadas.shift(0);
    }
    $('#panel-' + $(this).data('removethiselement')).remove();
    
    //console.log($(this).data('removethiselement'));
    
    redibujarListaCabinasSumario(cabinas_seleccionadas);
    
    calcularPaxPorAcomodar();
})

// ENVIAR ACOMODACION DESDE FORM
$('#go-checkout').click( function(){
    
    $.each( cabinas_seleccionadas, function(index, value){        
        $('<input type="hidden" name="cabins-selected['+index+'][idCabina]" value="'+value.idCabina+'">').appendTo($('#accommodation-form'));
        $('<input type="hidden" name="cabins-selected['+index+'][codigoCabina]" value="'+value.codigoCabina+'">').appendTo($('#accommodation-form'));
        $('<input type="hidden" name="cabins-selected['+index+'][nombreCabina]" value="'+value.nombreCabina+'">').appendTo($('#accommodation-form'));
        $('<input type="hidden" name="cabins-selected['+index+'][acomodacionTexto]" value="'+value.acomodacionTexto+'">').appendTo($('#accommodation-form'));
        $('<input type="hidden" name="cabins-selected['+index+'][personasEnCabina]" value="'+value.personasEnCabina+'">').appendTo($('#accommodation-form'));
        $('<input type="hidden" name="cabins-selected['+index+'][precioCabina]" value="'+parseInt(value.precioCabina)+'">').appendTo($('#accommodation-form'));
    })
    $('#accommodation-form').submit();
})

// ENVIAR ACOMODACION DESDE MODAL
$('#submit-accommodation').click( function(){    
    $('#go-checkout').trigger('click');
})

/* EVENTOS PARA EXTRAS */
// SELECCIONAR PACK
$('.pack-placeholder').click( function(){

    $(this).toggleClass('open');
    $(this).toggleClass('selected');
    
})
// VER INFORMACION DEL PACK
$('.pack-placeholder-info-box').click(function(){
    console.log($(this));
    $('#info-extra-'+ $(this).data('pax') +'-' + $(this).data('packcode')).modal('show');
})
// ACTIVAR SERCIVIO ADICIONAL
$('.offer-search-filter-placeholder').click(function(){
    $(this).toggleClass('selected');
    if ($(this).hasClass('selected') && $(this).hasClass('set-billing-info')){
        $('select[name=billing-country] option[value=' + $('input[name=traveler\\[1\\]\\[country\\]]').val() + ']').prop('selected', true);
        $('input[name=billing-address]').val($('input[name=traveler\\[1\\]\\[streetaddress\\]]').val());
        $('input[name=billing-city]').val($('input[name=traveler\\[1\\]\\[city\\]]').val());
        $('input[name=billing-state]').val($('input[name=traveler\\[1\\]\\[citystate\\]]').val());
        $('input[name=billing-zipcode]').val($('input[name=traveler\\[1\\]\\[zipcode\\]]').val());
    }else{
        $('select[name=billing-country]').val(0);
        $('input[name=billing-address]').val('');
        $('input[name=billing-city]').val('');
        $('input[name=billing-state]').val('');
        $('input[name=billing-zipcode]').val('');
    }
})
$('.counter-service').click(function(){
    var operador = $(this);
    var pax = operador.data('pax');
    var servicio = operador.data('serviceid');
    var campo = $('#counter-'+pax+'-'+servicio);
    var valor = campo.val();
    
    if( $(this).hasClass('add') ){
        campo.val( calcularInputPax(0, valor) );
    }else{
        if (valor > 1){
            campo.val( calcularInputPax(1, valor) );
        }
    }
    console.log(campo.val());
})
$('#send-as-quote').click(function(e){
    e.preventDefault();
    $('[name="go-request"]').val(0);
    $('#checkout-form').submit();
})







});