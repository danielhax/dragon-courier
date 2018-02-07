<!--
    Occurences: "Schedule a Pickup" page and "Manage Address" tab in "User Account" page
 -->

 <?php 
    $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );

    $addresses = $db->get_addresses();
 ?>

<div class="pickup-address-table">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Address</th>
                <th>Region</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach( $addresses as $address) { ?>

                <tr>
                    <td><input type="radio" value="<?= $address['id'] ?>" name="pickup_address"></td>
                    <td><?= $address['address'] . ', ' . $address['barangay'] . ', ' . $address['city'] ?></td>
                    <td><?= $address['region'] ?></td>
                </tr>

            <?php } ?>
            <tr>
                <td colspan="3">
                    <button type="button" class="btn btn-default new-address-btn" data-toggle="modal" data-target="#newAddressModal">+ Add new address</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    jQuery( '.pickup-address-table input[type=radio]:first' ).prop('checked', 'checked');
</script>