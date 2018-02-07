<form action="" method="post" class="delivery-form">
    <!-- <input type="hidden" name="action" value="schedule_delivery"> -->
    <?php wp_nonce_field( 'pickup_schedule' ); ?>
    <div class="row">
        <div class="col col-md-4 col-sm-12">
            <div class="row">
                <h3>Select Date of Pickup</h3>

                <div class="input-group date" id="pickup-datetimepicker">
                    <input type="text" name="pickup_date" class="form-control">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                </div>
                <p class="note">NOTE: Cutoff time for Same Day delivery is 12:00am</p>
            </div>
            <div class="row">
                <h3>Select Pickup Address</h3>

                <?php include( ABSPATH . 'wp-content/plugins/dragon-courier/views/address_table.php'); ?>

            </div>
        </div>
        <div class="col col-md-4 col-sm-12">
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
                <input type="text" class="form-control" name="r_address" id="r_address" placeholder="House No., Street, Condo/Village/Subdivision" required value="Example">
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="r_barangay">Barangay<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_barangay" id="r_barangay" placeholder="Barangay" required value="Example">
                </div>
                <div class="col-md-6">
                    <label for="r_city">City<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_city" id="r_city" placeholder="City" required value="Example">
                </div>
            </div>
            <div class="form-group">
                <label for="r_region">Region</label>
                <select name="r_region" class="form-control" id="r_region">
                    <option value="Metro Manila">Metro Manila</option>
                    <option value="Provincial Luzon">Provincial Luzon</option>
                    <option value="Visayas/Mindanao">Visayas/Mindanao</option>
                </select>
                <div class="pl-selected hide">
                    <p class="note">3 to 4 Days Estimated Time of Delivery</p>
                </div>

                <div class="vm-selected hide">
                    <p class="note">5 to 6 Days Estimated Time of Delivery</p>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="r_mobile_no">Mobile Number<span class="required">*</span></label>
                    <input type="text" class="form-control" name="r_mobile_no" id="r_mobile_no" placeholder="e.g. +639271234567" required value="+639274817290">
                </div>
                <div class="col-md-6">
                    <label for="r_email">Email Address: </label>
                    <input type="email" class="form-control" name="r_email" id="r_email" placeholder="Email Address">
                </div>
            </div>
        </div>

        <div class="col col-md-4 col-sm-12">
            <h3>Delivery Options</h3>

            <div class="form-group mm-selected">
                <label class="radio-inline delivery-option-label">
                    <input type="radio" name="mm-same-day" value="Same Day" checked>Same Day Delivery
                </label>
                <label class="radio-inline delivery-option-label">
                    <input type="radio" name="mm-same-day" value="Regular">Regular Delivery (2 Days)
                </label>
            </div>

            <div class="form-group">
                <label class="radio-inline delivery-option-label">
                    <input type="radio" name="delivery_option" value="Unlimited" checked>Unlimited Weight
                </label>
                <label class="radio-inline delivery-option-label">
                    <input type="radio" name="delivery_option" value="Own">Own Packaging
                </label>
            </div>

            <div class="unlimited-selected">
                <p class="note">Please make sure your item is no more than 12" x 18" in dimensions</p>
            </div>

            <div class="own-selected form-group hide">
                <label for="pkg_weight">Weight (kg)</label>
                <input type="number" class="form-control" name="pkg_weight" id="pkg_weight" value="3" disabled>
                <h6 class="additional_kg_cost">Additional cost: ₱<span>0</span></h6>
            </div>

            <div class="form-group">
                <label for="pkg_cost">Total Cost</label>
                <input type="number" class="form-control" name="pkg_cost" id="pkg_cost" class="pkg_cost" value="188" readonly>
            </div>

            <h3>Remarks</h3>
            <textarea name="remarks" class="form-control remarks" cols="30" rows="5" placeholder="Optional"></textarea>

            <button type="submit" name="pickup_schedule_submit" class="btn btn-default pick-up-schedule-btn">Submit</button>
        </div>
    </div>
</form>

<!-- New Address Form Modal -->
<?php include( ABSPATH . 'wp-content/plugins/dragon-courier/views/new_address_modal.php'); ?>

<script>
    var initialHour = {};

    jQuery( function(){

        initialHour = moment().hour();

        jQuery('#pickup-datetimepicker').datetimepicker({
            format: 'YYYY-DD-MM',
            minDate: moment().add( getMinDay(), 'days')
        });

        var checkIfCutoff = setInterval( function() {
            console.log( "Initial: " + initialHour + " Current: " + moment().hour() + " -- " + isNextHour() );
            if( isNextHour() ) {

                jQuery('#pickup-datetimepicker').data("DateTimePicker").minDate( moment().add( getMinDay(), 'days') );
                initialHour = moment().hour();

            }
        }, 1000);

    });

    //returns true of current time is past 11:59 am
    function isCutOff(){

        return moment().hour() >= 12;

    }

    //check if the hour changed
    function isNextHour() {

        return initialHour != moment().hour();

    }

    //if cutoff, set schedule for next day
    function getMinDay(){

        if( isCutOff() ) return 2;

        return 1;

    }

</script>

<?php 

    if( isset ( $_POST['pickup_schedule_submit'] ) ) {

        $db = DragonDB::getInstance() or die( "ERROR ACCESSING DATABASE!" );;

        $db->insert_schedule_request();

    }

?>