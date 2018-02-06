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
            <tr>
                <td><input type="radio"></td>
                <td>221B Baker Street, Quezon City</td>
                <td>Metro Manila</td>
            </tr>
            <tr>
                <td colspan="3">
                    <button type="button" class="btn btn-default new-address-btn" data-toggle="modal" data-target="#newAddressModal">+ Add new address</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- New Address Form Modal -->
<div class="modal fade modal-md" id="newAddressModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Address</h4>
            </div>
            <div class="modal-body">
                <form method="post" class="new-address-form">

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
                <button class="btn btn-success">Submit</button>
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>