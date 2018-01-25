jQuery(function(){

    jQuery('.alert').hide();

    jQuery( '.main-menu-buttons input' ).click( function() {

        //if button is already active, don't do anything
        if(!jQuery(this).hasClass('active')){
            
            jQuery('.main-menu-buttons input').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('.admin-form').hide();

            if(jQuery(this).hasClass('ongoing')){

                jQuery('.ongoing-transactions-table').show();
                
            } else if(jQuery(this).hasClass('email')){

                jQuery('.email-settings-form').show();

            } else if(jQuery(this).hasClass('history')){

                jQuery('.transactions-history-table').show();
                
            }

        }

    });

    jQuery( '.assign-tracking-no' ).submit( function(e) {

        e.preventDefault();

        var data = {
            'action': 'update_tracking_no',
            'transaction_id': jQuery( this ).find( 'input[name=transaction_id]' ).val(),
            'tracking-no': jQuery( this ).find( 'input[name=tracking-no]' ).val()
        }

        jQuery.post( ajax_object.ajax_url, data, function( response ) {

            console.log(response);
            response = JSON.parse( response );
            var alert = jQuery('.assign-tracking-no .alert');

            switch( response.status ) {
                case 'error': 
                    alert.addClass( 'alert-danger' );
                    break;
                case 'fail': 
                    alert.addClass( 'alert-warning' );
                    break;
                case 'success': 
                    alert.addClass( 'alert-success' );
                    break;
            }

            alert.text( response.msg );
            alert.fadeIn();

        });

    });

    jQuery( '.complete-transaction' ).submit( function(e) {
        e.preventDefault();

        var data = {
            'action': 'complete_transaction',
            'transaction_id': jQuery( this ).find( 'input[name=transaction-id]' ).val()
        }

        jQuery.post( ajax_object.ajax_url, data, function( response ) {
            
        });

    });

    jQuery('#transactionsModal').on('hidden.bs.modal', function () {
        resetAlert();
    })

});

function initiateForms( transaction_id ) {

    //set form ID
    jQuery( '.action-form .transaction_id' ).val( transaction_id );
    //show ID on modal header
    jQuery( '#transactionsModal .title-id' ).text( transaction_id );

    var data = {
        'action': 'tracking_no_assigned',
        'transaction_id': transaction_id
    }

    jQuery.post( ajax_object.ajax_url, data, function( response ) {

        if( response == 'false' ) {

            jQuery('.assign-tracking-no input#tracking-no').prop('disabled', false);
            jQuery('.assign-tracking-no .assign-btn').prop('disabled', false);

        } else {

            jQuery('.assign-tracking-no input#tracking-no').prop('disabled', true);
            jQuery('.assign-tracking-no .assign-btn').prop('disabled', true);
            jQuery('.assign-tracking-no .alert').addClass('alert-info');
            jQuery('.assign-tracking-no .alert').show();

        }

    });

}

function resetAlert() {
    var alert = jQuery('.assign-tracking-no .alert');
    alert.hide();
    alert.text("");
    alert.removeClass("alert-success alert-warning alert-danger alert-info");

}
