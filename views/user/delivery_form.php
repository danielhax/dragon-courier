<form action="" method="post" class="delivery-form">
    <!-- <input type="hidden" name="action" value="schedule_delivery"> -->
    <?php wp_nonce_field( 'pickup_schedule' ); ?>
    <div class="row">
        <div class="col col-md-6">
            <h3>Recipient's Information</h3>
            <div class="form-group">
                <label for="r_first_name">First Name<span class="required">*</span></label>
                <input type="text" class="form-control" name="r_first_name" id="r_first_name" placeholder="First Name" required value="Example">
            </div>
            <div class="form-group">
                <label for="r_last_name">Last Name<span class="required">*</span></label>
                <input type="text" class="form-control" name="r_last_name" id="r_last_name" placeholder="Last Name" required value="Example">
            </div>
            <div class="form-group">
                <label for="r_address">Street Address<span class="required">*</span></label>
                <input type="text" class="form-control" name="r_address" id="r_address" placeholder="Street Address" required value="Example">
            </div>
            <div class="row form-group">
                <div class="col-md-4">
                    <label for="r_city">City<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_city" id="r_city" placeholder="City" required value="Example">
                </div>
                <div class="col-md-4">
                    <label for="r_brgy">Barangay<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_brgy" id="r_brgy" placeholder="Barangay" required value="Example">
                </div>
                <div class="col-md-4">
                    <label for="r_postal">Postal Code<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_postal" id="r_postal" placeholder="Postal Code" required value="12345">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="r_mobile_no">Mobile Number<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_mobile_no" id="r_mobile_no" placeholder="Mobile No." required value="+639274817290">
                </div>
                <div class="col-md-6">
                    <label for="r_email">Email Address: </label>
                    <input type="email" class="form-control" name="r_email" id="r_email" placeholder="Email Address">
                </div>
            </div>
        </div>

        <div class="col col-md-6">
            <h3>Delivery Information</h3>
            <div class="form-group">
                <label for="delivery_types">Delivery Type</label>
                <select class="form-control" id="delivery_types" class="delivery_types">
                    <option>Metro Manila (Same Day Delivery)</option>
                    <option>Metro Manila (2 Days)</option>
                    <option>Luzon</option>
                    <option>Visayas/Mindanao</option>
                </select>
            </div>
            <div class="form-group">
                <label class="radio-inline delivery-option-label">
                    <input type="radio" name="delivery-option" value="unlimited" checked>Unlimited Weight
                </label>
                <label class="radio-inline delivery-option-label">
                    <input type="radio" name="delivery-option" value="own">Own Packaging
                </label>
            </div>

            <div class="unlimited-selected">
                <p>Please make sure your item is no more than 12" x 18" in dimensions</p>
            </div>

            <div class="own-selected form-group hide">
                <label for="additional_kg">Weight (kg)</label>
                <input type="number" class="form-control" name="addition_kg" id="additional_kg" value="3">
                <h6 class="additional_kg_cost">Additional cost: ₱<span>0</span></h6>
            </div>

            <div class="form-group">
                <label for="pkg_cost">Total Cost</label>
                <input type="number" class="form-control" name="pkg_cost" id="pkg_cost" class="pkg_cost" value="148" readonly>
            </div>

            <button type="submit" name="pickup_schedule_submit" class="btn btn-default pick-up-schedule-btn">Submit</button>
        </div>
    </div>
</form>

<?php 

    if( isset ( $_POST['pickup_schedule_submit'] ) ) {

        $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );;

        $db->insert_schedule_request();

    }

?>