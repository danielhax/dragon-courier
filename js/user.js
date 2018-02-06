jQuery( function() {

    jQuery( 'input[type=radio][name="delivery-option"]' ).change( function() {

        if( this.value == 'own' ) {

            jQuery( '.unlimited-selected ').addClass( 'hide' );
            jQuery( '.own-selected ').removeClass( 'hide' );

        } else if ( this.value == 'unlimited' ) {

            jQuery( '.own-selected ').addClass( 'hide' );
            jQuery( '.unlimited-selected ').removeClass( 'hide' );

        }

    });

    jQuery( 'select#r_region' ).change( function() {

        var option = jQuery( this ).find( "option:selected" );

        if( option.val() == 'Metro Manila' ) {

            //jQuery( '#pkg_cost' ).val(188);

            jQuery( '.pl-selected' ).addClass( 'hide' );
            jQuery( '.vm-selected' ).addClass( 'hide' );
            jQuery( '.mm-selected' ).removeClass( 'hide' );

        }  else if( option.val() == 'Provincial Luzon' ) {

            jQuery( '.vm-selected' ).addClass( 'hide' );
            jQuery( '.mm-selected' ).addClass( 'hide' );
            jQuery( '.pl-selected' ).removeClass( 'hide' );

        } else if( option.val() == 'Visayas/Mindanao' ) {

            jQuery( '.pl-selected' ).addClass( 'hide' );
            jQuery( '.mm-selected' ).addClass( 'hide' );
            jQuery( '.vm-selected' ).removeClass( 'hide' );

        }
    });

    /**
     * 
     * Mobile no. fields constant prefix (+63) are undeleteable
     * 
     */
    jQuery( '#mobile_number-88, #r_mobile_no' ).keydown(function(e) {
        var oldvalue=jQuery(this).val();
        var field=this;
        setTimeout(function () {
            if(field.value.indexOf('+63') !== 0) {
                jQuery(field).val(oldvalue);
            } 
        }, 1);
    });

});