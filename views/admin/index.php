<?php 
    $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );
?>

<div class="main-menu-buttons">
    <input type="button" class="btn btn-primary ongoing active" value="Ongoing Transactions">
    <input type="button" class="btn btn-primary history" value="Transactions History">
    <input type="button" class="btn btn-primary email" value="E-mail">
</div>
<div class="forms-area">
    <?php include(plugin_dir_path( __FILE__ ) . '/ongoing_transactions.php'); ?>
    <?php //include(plugin_dir_path( __FILE__ ) . '/modify_transaction.php'); ?>
    <?php include(plugin_dir_path( __FILE__ ) . '/transactions_history.php'); ?>
</div>

<?php
    //extra function

    function get_row_color($status) {

        switch( $status ) {
            case 'Pending' :
                return 'warning';
            case 'Completed' :
                return 'success';
            case 'Dispatched' :
                return 'info';
            case 'Cancelled' :
                return 'danger';
            default:
                return 'active';
        }

    }
?>