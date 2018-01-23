<!-- <script>
    function get_pending_transactions() {

        var data = {
            "action": "get_pending_transactions",
            "pending_transactions": ""
        }

        jQuery.post( ajax_object.ajax, data, function(result) {
            



        } );

    }
</script> -->

<div class="pending-transactions-table admin-form">

    <?php 

    $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );

    $pending_transactions = $db->get_incomplete_transactions();
    ?>

    <?php if( empty( $pending_transactions ) ) { ?>

        <div class="no-ongoing">
            <h2>No pending shipments at this time.</h2>
        </div>

    <?php } else { ?>

        <div class="ongoing-list">
            <h2>Ongoing Shipments</h2>
            <table class="table sortable">
                <thead>
                <tr>
                    <th>User ID</th>
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
                            <td><?= $tr['last_name'] . ', ' . $tr['first_name'] ?></td>
                            <td><?= $tr['street_address'] ?></td>
                            <td><?= $tr['city'] ?></td>
                            <td><?= $tr['r_last_name'] . ', ' . $tr['r_first_name'] ?></td>
                            <td><?= $tr['r_address'] ?></td>
                            <td><?= $tr['r_city'] ?></td>
                            <td><?= $tr['r_mobile_no'] ?></td>
                            <td><?= $tr['schedule_date'] ?></td>
                            <td><?= $tr['status'] ?></td>
                            <td><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#transactionsModal" id="<?= $tr['id'] ?>">View Actions</button></td>
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
          <h4 class="modal-title">Actions</h4>
        </div>
        <div class="modal-body">
          <form method="post" class="assign-tracking-no">
                <h3>Assign Tracking No.</h3>
                <div class="form-group">
                    <label for="tracking-no"></label>
                    <input type="text" class="form-control" name="tracking-no" id="tracking-no" placeholder="Tracking No." value="012345678901">
                </div>
                <input type="submit" class="btn btn-success" value="Assign">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
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