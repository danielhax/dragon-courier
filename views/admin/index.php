<div class="main-menu-buttons">
    <input type="button" class="btn btn-primary log active" value="Pending Transactions">
    <input type="button" class="btn btn-primary modify" value="Modify Transaction">
    <input type="button" class="btn btn-primary history" value="Transactions History">
</div>
<div class="row forms-area">
    <div class="col-md-4">
        <?php include(plugin_dir_path( __FILE__ ) . '/pending_transactions.php'); ?>
        <?php include(plugin_dir_path( __FILE__ ) . '/modify_transaction.php'); ?>
        <?php include(plugin_dir_path( __FILE__ ) . '/transactions_history.php'); ?>
    </div>
</div>