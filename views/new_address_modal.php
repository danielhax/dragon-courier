<!-- New Address Form Modal -->
<div class="modal fade modal-md" id="newAddressModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Address</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post" class="new-address-form">
                    <?php wp_nonce_field( 'new_address' ); ?>
                    <div class="form-group">
                        <label for="address">Street Address<span class="required">*</span></label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="House No., Street, Condo/Village/Subdivision" required value="Example">
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="barangay">Barangay<span class="required">*</span></label>
                            <input type="text" class="form-control" name="barangay" id="barangay" placeholder="Barangay" required value="Example">
                        </div>
                        <div class="col-md-6">
                            <label for="city">City<span class="required">*</span></label>
                            <input type="text" class="form-control" name="city" id="city" placeholder="City" required value="Example">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="region">Region</label>
                        <select name="region" class="form-control" id="region">
                            <option value="Metro Manila">Metro Manila</option>
                            <option value="Provincial Luzon">Provincial Luzon</option>
                            <option value="Visayas/Mindanao">Visayas/Mindanao</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="submit" name="new-address-submit" class="btn btn-success" value="Submit">
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php

    if( isset( $_POST['new-address-submit'] ) ) {

        $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );;

        $db->insert_new_address();

    }

?>