
jQuery( 'select#r_region' ).change( recalculate() );
jQuery( 'input[type=radio][name="mm-same-day"]' ).change( recalculate() );
jQuery( 'input[type=radio][name="delivery_option"]' ).change(recalculate());
jQuery( '#pkg_weight' ).change( recalculate() );

function recalculate(){

    var cost = get_region_cost() + additional_weight_cost();

    jQuery( '#pkg_cost' ).val( cost );

}

function additional_weight_cost(){

    if( jQuery( '#pkg_weight' ).prop( 'disabled' ) ){

        return 0;

    } else {
        
        return jQuery( '#pkg_weight' ).val() * 50;

    }

}

function get_region_cost(){
    var option = jQuery( 'select#r_region' ).find( "option:selected" );

    if( option.val() == "Metro Manila" ){
        
        if( is_same_day() )
            return 188;
        else
            return 88;

    } else {

        return 188;

    }
}

function is_same_day(){

    return jQuery('input[name="mm-same-day"]:checked').val() == 'Same Day';

}