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

    jQuery( '.schedule-nav button.next' ).click( function(){

        jQuery( '.schedule-nav .next' ).css( 'visibility', 'hidden' );       

        jQuery('.pickup-details').hide( 'slide', { direction: 'left' }, 1000, function(){
            jQuery('.payment-details').removeClass( 'hide' ).show( 'slide', { direction: 'left' }, 1000 );
            jQuery( '.schedule-nav .back' ).css( 'visibility', 'visible' ).removeClass( 'hide' );
            jQuery( '.schedule-nav .pickup-schedule-btn' ).css( 'visibility', 'visible' ).removeClass( 'hide' ); 
        });

    });

    jQuery( '.schedule-nav button.back' ).click( function(){

        jQuery( '.schedule-nav .pickup-schedule-btn' ).css( 'visibility', 'hidden' );
        jQuery( '.schedule-nav .back' ).css( 'visibility', 'hidden' );
        
        jQuery('.payment-details').hide( 'slide', {direction: 'right'}, 1000, function(){
            jQuery('.pickup-details').show( 'slide', {direction: 'right'}, 1000);
            jQuery( '.schedule-nav .next' ).css( 'visibility', 'visible' ).removeClass( 'hide' );
        });

    });

    jQuery('form.delivery-form').submit( function(){
        /**
         * 
         * Start validation upon pickup schedule form submit
         * 
         */
        var pickup_radio = jQuery( 'input[name="pickup_address"]' );

        if( pickup_radio.length ) {

            return true;
            
        }

        jQuery( '.payment-details' ).hide('slide', {direction: 'right'}, 1000, function(){
            jQuery( '.pickup-details .alert' ).removeClass( 'hide' );
            jQuery( '.pickup-details' ).show('slide', {direction: 'right'}, 1000);
        }); 
        

        return false;
    });


});