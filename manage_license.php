<?php 
include('db_connect.php');
if(isset($_GET['license_key'])){
$license = $conn->query("SELECT * FROM license where license_key =".$_GET['license_key']);
foreach($license->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	
	<form action="" id="manage-license">
		<input type="hidden" name="license_key" value="<?php echo isset($meta['license_key']) ? $meta['license_key']: '' ?>">
		<div class="form-group">
			<label for="license_type">License Type</label>
			<input type="text" name="license_type" id="license_type" class="form-control" value="<?php echo isset($meta['license_type']) ? $meta['license_type']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="license_info">License Name</label>
			<input type="text" name="license_info" id="license_info" class="form-control" value="<?php echo isset($meta['license_info']) ? $meta['license_info']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="client_info">Client Information</label>
			<input type="text" name="client_info" id="client_info" class="form-control" value="<?php echo isset($meta['client_info']) ? $meta['client_info']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="purchased_date">Purchased Date</label>
			<input type="date" name="purchased_date" id="purchased_date" class="form-control" value="<?php echo isset($meta['purchased_date']) ? $meta['purchased_date']: '' ?>" required>
		</div>
		<div class="form-group">
		<label for="license_lifetime">License Lifetime</label>
		<select id="licenseType" class="form-control">
        <option value="">Choose Lifetime</option>
        <option value="annual" >Annual</option>
        <option value="lifetime">Lifetime</option>
    </select>
		</div>
		<div class="form-group">
		<div id="expirationField" style="display: none;">
        <label for="expirationDate">License Expiration Date</label>
        <input type="date" id="expirationDate" name="expiration_date" class="form-control" value="<?php echo isset($meta['expiration_date']) ? $meta['expiration_date']: '' ?>" required>
    </div>
		</div>
		<div class="form-group">
			<label for="contact_person">Contact Person</label>
			<input type="text" name="contact_person" id="contact_person" class="form-control" value="<?php echo isset($meta['contact_person']) ? $meta['contact_person']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="contact_phone">Contact Phone</label>
			<input type="tel" name="contact_phone" id="contact_phone" class="form-control" value="<?php echo isset($meta['contact_phone']) ? $meta['contact_phone']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="contact_email">Contact Email</label>
			<input type="email" name="contact_email" id="contact_email" class="form-control" value="<?php echo isset($meta['contact_email']) ? $meta['contact_email']: '' ?>" required>
		</div>
	</form>
</div>
<script>
	 $(document).ready(function() {
        $('#licenseType').change(function() {
            var selectedType = $(this).val();
            var expirationField = $('#expirationField');

            if (selectedType === 'lifetime') {
                expirationField.hide();
            } else {
                expirationField.show();
            }
        });
    });
	$('#manage-license').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=save_license',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp ==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>