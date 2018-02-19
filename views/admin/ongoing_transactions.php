<div class="ongoing-transactions-table admin-form">

    <?php 

        $pending_transactions = $db->get_incomplete_transactions();

    ?>

    <?php if( empty( $pending_transactions ) ) { ?>

        <div class="no-ongoing">
            <h2>No pending shipments at this time.</h2>
        </div>

    <?php } else { ?>

        <div class="ongoing-list transactions-table">
            <h2>Ongoing Shipments</h2>
            <table class="table sortable">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Tracking No.</th>
                    <th>Customer's Name</th>
                    <th>Pickup Address</th>
                    <th>City</th>
                    <th>Recipient's Name</th>
                    <th>Recipient's Address</th>
                    <th>Recipient's City</th>
                    <th>Recipient's Region</th>
                    <th>Recipient's Mobile No.</th>
                    <th>Date Scheduled</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach( $pending_transactions as $tr ) { 
                        
                        $address = $db->get_transaction_pickup_address( $tr['pickup_address'] );

                        ?>

                        <tr class="<?= get_row_color($tr['status']); ?>">
                            <td><?= $tr['user_id'] ?></td>
                            <td><?php 
                            
                            if( $tr['tracking_no'] == null || $tr['user_id'] == '' || ctype_space( $tr['user_id'] ) ) {
                                echo "<em>Not set.</em>";
                            } else {

                                echo $tr['tracking_no'];

                            }
                            
                            ?>
                            
                            </td>
                            <td><?= $tr['last_name'] . ', ' . $tr['first_name'] ?></td>
                            <td><?= $address['address'] ?></td>
                            <td><?= $address['city'] ?></td>
                            <td><?= $tr['r_last_name'] . ', ' . $tr['r_first_name'] ?></td>
                            <td><?= $tr['r_address'] ?></td>
                            <td><?= $tr['r_city'] ?></td>
                            <td><?= $tr['r_region'] ?></td>
                            <td><?= $tr['r_mobile_no'] ?></td>
                            <td><?= $tr['schedule_date'] ?></td>
                            <td><strong><?= $tr['status'] ?></strong</td>
                            <td><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#transactionsModal" id="<?= $tr['id'] ?>" onClick="initiateForms(<?= $tr['user_id'] ?>,<?= $tr['id'] ?>)">View Actions</button></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

</div>

<!-- Actions Modal -->
<div class="modal fade modal-md" id="transactionsModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">[ID:<span class="title-id"></span>] Actions</h4>
            </div>
            <div class="modal-body">

                <div class="alert" role="alert"></div>

                <h4>Set as Completed</h4>
                <button class="btn btn-success complete-btn" data-toggle="modal" data-target="#confirm-complete">Complete Transaction</button>
                <h4>Set as Cancelled</h4>
                <button class="btn btn-danger cancel-btn" data-toggle="modal" data-target="#confirm-cancel">Cancel Transaction</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Complete -->

<div class="modal fade" id="confirm-complete" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Complete</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to complete this transaction?
                <form method="post" class="complete-transaction action-form">
                    <input type="hidden" name="user_id" class="user_id">
                    <input type="hidden" name="transaction_id" class="transaction_id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Complete Transaction</button>
                </form>
                <button type="button" class="btn btn-default btn-ok" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete -->
<div class="modal fade" id="confirm-cancel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Cancel</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this transaction?
                <form method="post" class="cancel-transaction action-form">
                    <input type="hidden" name="transaction_id" class="transaction_id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger">Cancel Transaction</button>
                </form>
                <button type="button" class="btn btn-default btn-ok" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>