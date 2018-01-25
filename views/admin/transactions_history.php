<div class="transactions-history-table admin-form" style="display: none;">
<?php 

    $archive = $db->get_transaction_archive();

?>

    <?php if( empty( $archive ) ) { ?>
    
    <div class="no-ongoing">
        <h2>Archive is empty.</h2>
    </div>
    
    <?php } else { ?>
        
    <div class="history-list transactions-table">
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
                </tr>
            </thead>
            <tbody>
            <?php foreach( $archive as $tr ) { ?>
                
                <tr class="<?= get_row_color($tr['status']); ?>">
                    <td><?= $tr['user_id'] ?></td>
                    <td><?= $tr['tracking_no']; ?></td>
                    <td><?= $tr['last_name'] . ', ' . $tr['first_name'] ?></td>
                    <td><?= $tr['street_address'] ?></td>
                    <td><?= $tr['city'] ?></td>
                    <td><?= $tr['r_last_name'] . ', ' . $tr['r_first_name'] ?></td>
                    <td><?= $tr['r_address'] ?></td>
                    <td><?= $tr['r_city'] ?></td>
                    <td><?= $tr['r_mobile_no'] ?></td>
                    <td><?= $tr['schedule_date'] ?></td>
                    <td><strong><?= $tr['status'] ?></strong</td>
                </tr>
                
            <?php } ?>
            </tbody>
        </table>
    </div>
            
    <?php } ?>
</div>