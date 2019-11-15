$('#sync-receptivo').click( function(){
    var quoteId = $('#quoteID').val();
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data : {
            action: 'goDownloadReceptivo',
            quoteId: quoteId,
        },
        beforeSend  : function(){
            $('#syncmsg').modal('show');
            $('#sync-text-msg')
                .removeClass('bg-danger')
                .removeClass('bg-warning')
                .removeClass('bg-success')
                .addClass('bg-info');
            $('#sync-text-msg .dashicons')
                .removeClass('dashicons-yes')
                .removeClass('dashicons-no')
                .removeClass('dashicons-warning')
                .addClass('dashicons-image-rotate');
            $('#sync-text-msg p')
                .text('Connecting to ELIGOS...');
        },
        success: function( response ){
            setTimeout( function(){
                if (response.length > 0){
                    $('#sync-text-msg')
                        .removeClass('bg-info')
                        .addClass('bg-success');
                    $('#sync-text-msg .dashicons')
                        .removeClass('dashicons-image-rotate')
                        .addClass('dashicons-yes');
                    $('#sync-text-msg p')
                        .text('The information has been downloaded to ELIGOS...');
                    $('.modal-footer')
                        .removeClass('hidden');
                    if(response == 'ERROR'){
                        $('#q-code').html('<span class="text-danger"><strong>ERROR</strong></span>');
                    }else{
                        //$('#q-code').text(response);
                        console.log(response);
                    }
                }else{
                    $('#sync-text-msg')
                        .removeClass('bg-info')
                        .addClass('bg-warning');                
                    $('#sync-text-msg .dashicons')
                        .removeClass('dashicons-image-rotate')
                        .addClass('dashicons-warning');
                    $('#sync-text-msg p')
                        .text('An error has occurred with ELIGOS, please try again!...');
                    $('.modal-footer')
                        .removeClass('hidden');
                }
            }, 1000);    
            console.log(response);
        },
        error: function( response ){
            setTimeout( function(){
                $('#sync-text-msg')
                    .removeClass('bg-info')
                    .addClass('bg-danger');                
                $('#sync-text-msg .dashicons')
                    .removeClass('dashicons-image-rotate')
                    .addClass('dashicons-no');
                $('#sync-text-msg p')
                    .html('Error Connecting to ELIGOS... <strong class="text-danger">please report!</strong>');
                $('.modal-footer').show();
                $('.modal-footer')
                    .removeClass('hidden');
            }, 1000);
        }
    })
})
$('#send-quote').click(function(){
    $('#send-quote-to').modal('show');
})
function printDiv() 
{
    var divToPrint=document.getElementById('print-area');
    var newWin=window.open('','Print-Window');
    var vistaPrint = '';
    vistaPrint += '<html>';
    vistaPrint += '<head>';
    vistaPrint += '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
    vistaPrint += '</head>';
    vistaPrint += '<body onload="window.print()">';
    vistaPrint += divToPrint.innerHTML;
    vistaPrint += '</body>';
    vistaPrint += '</html>';

    console.log(vistaPrint);

    newWin.document.open();
    newWin.document.write(vistaPrint);
    //newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    newWin.document.close();
    setTimeout(function(){newWin.close();},10);
}
function enviarQuote(){
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var campoemail = $('[name=emailto]');
    var mailfrom = $('[name=emailfrom]');
    var messagge = $('[name=topmsg]');
    var body = $('#print-area');
    
    console.log(mailfrom.val());
    console.log(campoemail.val());
    
    if(!regex.test(campoemail.val())){
        $('.email-to').addClass('has-error');
        campoemail.focus();
    }else{
        
        $('.email-to')
            .removeClass('has-error')
            .addClass('has-success');
        
        //enviar_quote_via_ajax
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data : {
                action: 'enviar_quote_via_ajax',
                mailfrom: mailfrom.val(),
                mailto: campoemail.val(),
                messagge: messagge.val(),
                body: body.html(),
            },
            beforeSend: function(){
                $('#send-quote-now')
                    .removeClass('btn-primary')
                    .addClass('btn-warning');
            },
            success: function( response ){
                $('#send-quote-now')
                    .removeClass('btn-warning')
                    .addClass('btn-success');
                $('#send-quote-to').modal('hide');
                
            },
            error: function(){
                $('#send-quote-now')
                    .removeClass('btn-warning')
                    .addClass('btn-danger');
            }
        });
    }
}