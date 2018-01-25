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
                    <th>Recipient's Mobile No.</th>
                    <th>Date Scheduled</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach( $pending_transactions as $tr ) { ?>

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
                            <td><?= $tr['street_address'] ?></td>
                            <td><?= $tr['city'] ?></td>
                            <td><?= $tr['r_last_name'] . ', ' . $tr['r_first_name'] ?></td>
                            <td><?= $tr['r_address'] ?></td>
                            <td><?= $tr['r_city'] ?></td>
                            <td><?= $tr['r_mobile_no'] ?></td>
                            <td><?= $tr['schedule_date'] ?></td>
                            <td><strong><?= $tr['status'] ?></strong</td>
                            <td><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#transactionsModal" id="<?= $tr['id'] ?>" onClick="initiateForms(<?= $tr['id'] ?>)">View Actions</button></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

</div>

<!-- Modal -->
<div class="modal fade" id="transactionsModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">[ID:<span class="title-id"></span>] Actions</h4>
            </div>
            <div class="modal-body">
                <form method="post" class="assign-tracking-no action-form">
                    <input type="hidden" name="transaction_id" class="transaction_id">
                    <h4>Assign Tracking No.</h4>
                    <div class="alert" role="alert"></div>
                    <div class="input-group">
                        <label for="tracking-no"></label>
                        <input type="text" class="form-control" name="tracking-no" id="tracking-no" placeholder="Tracking No." disabled>
                        <span class="input-group-btn">
                                <button class="btn btn-success assign-btn" disabled>Assign</button>
                        </span>
                    </div>
                </form>
                <form method="post" class="complete-transaction action-form">
                    <input type="hidden" name="transaction_id" class="transaction_id">
                    <h4>Set as Completed</h4>
                    <button class="btn btn-success complete-btn">Complete Transaction</button>
                    </div>
                </form>
                <form method="post" class="cancel-transaction action-form">
                    <input type="hidden" name="transaction_id" class="transaction_id">
                    <h4>Set as Cancelled</h4>
                    <button class="btn btn-success cancel-btn">Cancel Transaction</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>