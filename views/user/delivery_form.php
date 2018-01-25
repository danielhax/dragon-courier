<form action="" method="post" class="delivery-form">
    <!-- <input type="hidden" name="action" value="schedule_delivery"> -->
    <?php wp_nonce_field( 'pickup_schedule' ); ?>

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
    <h3>Package Information</h3>
    <div class="form-group">
        <label for="weight">Weight (kg)<span class="required">*</span></label>
        <input type="number" class="form-control" value="0" name="pkg_weight" id="weight" required>
    </div>
    <div class="form-group">

        <label for="dimensions">Dimensions (cm)<span class="required">*</span></label>
        <div class="row dimensions" id="dimensions">

            <div class="form-group col-md-4">
                <label for="length">Length</label>
                <input type="number" class="form-control" value="0" name="pkg_length" id="length" required>
            </div>

            <div class="form-group col-md-4">
                <label for="width">Width</label>
                <input type="number" class="form-control" value="0" name="pkg_width" id="width" required>
            </div>

            <div class="form-group col-md-4">
                <label for="height">Height</label>
                <input type="number" class="form-control" value="0" name="pkg_height" id="height" required>
            </div>

        </div>
    </div>
    <div class="form-group">
        <label for="cost">Cost</label>
        <input type="text" class="form-control" value="0" name="pkg_cost" id="cost" readonly>
    </div>
    <button type="submit" name="pickup_schedule_submit" class="btn btn-default">Submit</button>
</form>

<?php 

    if( isset ( $_POST['pickup_schedule_submit'] ) ) {

        $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );;

        $db->insert_schedule_request();

    }

?>