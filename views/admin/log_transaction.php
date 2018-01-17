<form class="log-transaction-form admin-form">
    <div class="form-group">
        <label for="transaction-no">Transaction no.:</label>
        <input type="text" class="form-control" name="transaction-no" id="transaction-no" placeholder="Transaction #">
    </div>
    <div class="form-group">
        <label for="first-name">First Name:</label>
        <input type="text" class="form-control" name="first-name" id="first-name" placeholder="First Name">
    </div>
    <div class="form-group">
        <label for="last-name">Last Name:</label>
        <input type="text" class="form-control" name="last-name" id="last-name" placeholder="Last Name">
    </div>
    <div class="form-group">
        <label for="pick-up-address">Pick-up Address:</label>
        <input type="text" class="form-control" name="pick-up-address" id="pick-up-address" placeholder="Address">
    </div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="pick-up-city">City:</label>
            <input type="text" class="form-control" name="pick-up-city" id="pick-up-city" placeholder="City">
        </div>
        <div class="col-md-6">
            <label for="pick-up-postal">Postal Code:</label>
            <input type="text" class="form-control" name="pick-up-postal" id="pick-up-postal" placeholder="Postal Code">
        </div>
    </div>
    <div class="form-group">
        <label for="delivery-address">Delivery Address:</label>
        <input type="text" class="form-control" name="delivery-address" id="delivery-address" placeholder="Address">
    </div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="delivery-city">City:</label>
            <input type="text" class="form-control" name="delivery-city" id="delivery-city" placeholder="City">
        </div>
        <div class="col-md-6">
            <label for="delivery-postal">Postal Code:</label>
            <input type="text" class="form-control" name="delivery-postal" id="delivery-postal" placeholder="Postal Code">
        </div>
    </div>
    
    <div class="form-group">
        <label for="weight">Weight (kg):</label>
        <input type="number" value="0" name="weight" id="weight">
    </div>
    <div class="form-group">
        <label for="weight">Dimensions (LxWxH cm):</label>
        <div class="dimensions">
            <input type="number" value="0" name="length" id="length">
            x
            <input type="number" value="0" name="width" id="length">
            x
            <input type="number" value="0" name="height" id="length">
        </div>
    </div>
    <div class="form-group">
        <label for="Cost">Cost:</label>
        <input type="text" value="0" name="cost" id="cost" disabled>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form> 