jQuery( function() {

    jQuery( 'input[type=radio][name="delivery_option"]' ).change( function() {

        if( this.value == 'Own' ) {

            jQuery( '.unlimited-selected ').addClass( 'hide' );
            jQuery( '.own-selected ').removeClass( 'hide' );
            jQuery( '#pkg_weight' ).prop('disabled', false);

        } else if ( this.value == 'Unlimited' ) {

            jQuery( '.own-selected ').addClass( 'hide' );
            jQuery( '.unlimited-selected ').removeClass( 'hide' );
            jQuery( '#pkg_weight' ).prop('disabled', true);

        }

    });

    jQuery( 'select#r_region' ).change( function() {

        var option = jQuery( this ).find( "option:selected" );

        if( option.val() == 'Metro Manila' ) {

            //jQuery( '#pkg_cost' ).val(188);

            jQuery( '.pl-selected' ).addClass( 'hide' );
            jQuery( '.vm-selected' ).addClass( 'hide' );
            jQuery( '.mm-selected' ).removeClass( 'hide' );

            jQuery( '.mm-selected input' ).prop('disabled', false);

        }  else if( option.val() == 'Provincial Luzon' ) {

            jQuery( '.vm-selected' ).addClass( 'hide' );
            jQuery( '.mm-selected' ).addClass( 'hide' );
            jQuery( '.pl-selected' ).removeClass( 'hide' );

            jQuery( '.mm-selected input' ).prop('disabled', true);

        } else if( option.val() == 'Visayas/Mindanao' ) {

            jQuery( '.pl-selected' ).addClass( 'hide' );
            jQuery( '.mm-selected' ).addClass( 'hide' );
            jQuery( '.vm-selected' ).removeClass( 'hide' );

            jQuery( '.mm-selected input' ).prop('disabled', true);

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