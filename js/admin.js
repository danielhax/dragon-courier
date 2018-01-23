jQuery(function(){

    jQuery('.main-menu-buttons input').click(function(){

        //if button is already active, don't do anything
        if(!jQuery(this).hasClass('active')){
            
            jQuery('.main-menu-buttons input').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('.admin-form').hide();

            if(jQuery(this).hasClass('log')){

                jQuery('.log-transaction-form').show();
                
            } else if(jQuery(this).hasClass('modify')){

                jQuery('.modify-transaction-form').show();

            } else if(jQuery(this).hasClass('history')){

                jQuery('.transactions-history-table').show();
                
            }

        }

    });

});
