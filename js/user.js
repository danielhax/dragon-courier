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

    jQuery( 'select' ).change( function() {

        var option = jQuery( this ).find( "option:selected" );

        if( option.val() == 'Metro Manila (Same Day Delivery)' ) {

            jQuery( '.pkg_cost' ).val(148);

        } else if( option.val() == 'Metro Manila (2 Days)' ) {

            jQuery( '.pkg_cost' ).val(88);

        } else if( option.val() == 'Luzon' ) {

            jQuery( '.pkg_cost' ).val(218);

        } else if( option.val() == 'Visayas/Mindanao' ) {

            jQuery( '.pkg_cost' ).val(218);

        }
    });

});