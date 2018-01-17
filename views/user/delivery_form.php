<form class="delivery-form">
    <h3>Recipient's Information</h3>
    <div class="form-group">
        <label for="first-name">First Name<span class="required">*</span></label>
        <input type="text" class="form-control" name="first-name" id="first-name" placeholder="First Name" required>
    </div>
    <div class="form-group">
        <label for="last-name">Last Name<span class="required">*</span></label>
        <input type="text" class="form-control" name="last-name" id="last-name" placeholder="Last Name" required>
    </div>
    <div class="form-group">
        <label for="delivery-address">Street Address<span class="required">*</span></label>
        <input type="text" class="form-control" name="delivery-address" id="delivery-address" placeholder="Street Address" required>
    </div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="delivery-city">City<span class="required">*</span></label>
            <input type="text" class="form-control" name="delivery-city" id="delivery-city" placeholder="City" required>
        </div>
        <div class="col-md-6">
            <label for="delivery-postal">Postal Code<span class="required">*</span></label>
            <input type="text" class="form-control" name="delivery-postal" id="delivery-postal" placeholder="Postal Code" required>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="mobile-no">Mobile Number<span class="required">*</span></label>
            <input type="text" class="form-control" name="mobile-no" id="mobile-no" placeholder="Mobile No." required>
        </div>
        <div class="col-md-6">
            <label for="email">Email Address: </label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
        </div>
    </div>
    <h3>Package Information</h3>
    <div class="form-group">
        <label for="weight">Weight (kg)<span class="required">*</span></label>
        <input type="number" class="form-control" value="0" name="weight" id="weight" required>
    </div>
    <div class="form-group">

        <label for="dimensions">Dimensions (cm)<span class="required">*</span></label>
        <div class="row dimensions" id="dimensions">

            <div class="form-group col-md-4">
                <label for="length">Length</label>
                <input type="number" class="form-control" value="0" name="length" id="length" required>
            </div>

            <div class="form-group col-md-4">
                <label for="width">Width</label>
                <input type="number" class="form-control" value="0" name="width" id="width" required>
            </div>

            <div class="form-group col-md-4">
                <label for="height">Height</label>
                <input type="number" class="form-control" value="0" name="height" id="height" required>
            </div>

        </div>
    </div>
    <div class="form-group">
        <label for="Cost">Cost</label>
        <input type="text" class="form-control" value="0" name="cost" id="cost" disabled>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>