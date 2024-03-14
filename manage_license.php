<?php 
include('db_connect.php');
session_start();
if(isset($_GET['license_key']) && isset($_SESSION['login_type'])){
	$user_id = $_SESSION['login_type'];
$license = $conn->query("SELECT * FROM license where license_key =".$_GET['license_key']);
foreach($license->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	
	<form action="" id="manage-license">
		<input type="hidden" name="license_key" value="<?php echo isset($meta['license_key']) ? $meta['license_key']: '' ?>">
		<input type="hidden" name="user_id" value="<?php echo isset($_SESSION['login_type']) ? $_SESSION['login_type']: '' ?>">
		<div class="form-group">
			<label for="license_type">License Type</label>
			<input type="text" name="license_type" id="license_type"
			 class="form-control" value="<?php echo isset($meta['license_type']) ? $meta['license_type']: '' ?>"
			  required>
			  <span class="error-message" id="license_type_error"></span>
		</div>
		<div class="form-group">
			<label for="license_info">License Name</label>
			<input type="text" name="license_info" id="license_info" class="form-control" value="<?php echo isset($meta['license_info']) ? $meta['license_info']: '' ?>" required>
			<span class="error-message" id="license_info_error"></span>
		</div>
		<div class="form-group">
			<label for="client_info">Client Information</label>
			<textarea name="client_info" class="form-control" id="client_info" cols="30" rows="5" value="<?php echo isset($meta['client_info']) ? $meta['client_info']: '' ?>" required><?php echo isset($meta['client_info']) ? $meta['client_info']: '' ?></textarea>
			<span class="error-message" id="client_info_error"></span>
		</div>
		<div class="form-group">
			<label for="license_description">License Description</label>
			<textarea name="license_description" class="form-control" id="license_description" cols="30" rows="5" value="<?php echo isset($meta['license_description']) ? $meta['license_description']: '' ?>" required><?php echo isset($meta['client_info']) ? $meta['client_info']: '' ?></textarea>
			<span class="error-message" id="license_description_error"></span>
		</div>
		<div class="form-group">
			<label for="license_cost">License Cost</label>
			<input type="number" name="license_cost" id="license_cost" class="form-control" value="<?php echo isset($meta['license_cost']) ? $meta['license_cost']: '' ?>" required>
			<span class="error-message" id="license_cost_error"></span>
			
		</div>
		<div class="form-group">
			<label for="purchased_date">Purchased Date</label>
			<input type="date" name="purchased_date" id="purchased_date" class="form-control" value="<?php echo isset($meta['purchased_date']) ? $meta['purchased_date']: '' ?>" required>
			<span class="error-message" id="purchased_date_error"></span>
		
		</div>
		<div class="form-group">
		<label for="license_lifetime">License Lifetime</label>
		<select id="licenseType" class="form-control">
        <option>Choose Lifetime</option>
        <option value="annual" >Annual</option>
        <option value="lifetime">Lifetime</option>
    </select>
		</div>
		<div class="form-group">
		<div id="expirationField" style="display: none;">
        <label for="expirationDate">License Expiration Date</label>
        <input type="date" id="expirationDate" name="expiration_date" class="form-control" value="<?php echo isset($meta['expiration_date']) ? $meta['expiration_date']: NULL ?>" required>
	</div>
		</div>
		<div class="form-group">
			<label for="contact_person">Contact Person</label>
			<input type="text" name="contact_person" id="contact_person" class="form-control" value="<?php echo isset($meta['contact_person']) ? $meta['contact_person']: '' ?>" required>
			<span class="error-message" id="contact_person_error"></span>
		</div>
		<div class="form-group">
			<label for="contact_phone">Contact Phone</label>
			<input type="tel" name="contact_phone" id="contact_phone" class="form-control" value="<?php echo isset($meta['contact_phone']) ? $meta['contact_phone']: '' ?>" required>
			<span class="error-message" id="contact_phone_error"></span>
		</div>
		<div class="form-group">
			<label for="contact_email">Contact Email</label>
			<input type="email" name="contact_email" id="contact_email" class="form-control" value="<?php echo isset($meta['contact_email']) ? $meta['contact_email']: '' ?>" required>
			<span class="error-message" id="contact_email_error"></span>
		</div>
		<div class="form-group">
			<label for="contact_person2">Contact Person 2</label>
			<input type="text" name="contact_person2" id="contact_person2" class="form-control" value="<?php echo isset($meta['contact_person2']) ? $meta['contact_person2']: '' ?>" required>
			<span class="error-message" id="contact_person2_error"></span>
		</div>
		<div class="form-group">
			<label for="contact_phone2">Contact Phone 2</label>
			<input type="tel" name="contact_phone2" id="contact_phone2" class="form-control" value="<?php echo isset($meta['contact_phone2']) ? $meta['contact_phone2']: '' ?>" required>
			<span class="error-message" id="contact_phone2_error"></span>
		</div>
		<div class="form-group">
			<label for="contact_email2">Contact Email 2</label>
			<input type="email" name="contact_email2" id="contact_email2" class="form-control" value="<?php echo isset($meta['contact_email2']) ? $meta['contact_email2']: '' ?>" required>
			<span class="error-message" id="contact_email2_error"></span>
		</div>
	</form>
</div>

<script>
	// Fetching all necessary datas from the input fields for validation
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

		  // Reset error messages
		  $('.error-message').text('');
        
        // Validate input fields
        var isValid = true;
        $(this).find(':input[required]').each(function() {
            var value = $(this).val().trim();
            var fieldName = $(this).attr('name');
            if (!value && fieldName !== 'expiration_date') {
                $('#' + fieldName + '_error').text('* This is a required field.').css('color', 'red');
                isValid = false;
            } else if (fieldName !== 'contact_email'
			 && !/^[a-zA-Z0-9\s]+$/.test(value)
			  && fieldName !== 'contact_email2'
			   && fieldName !== 'license_cost'
			   && fieldName !== 'contact_phone'
			   && fieldName !== 'contact_phone2'
			   && fieldName !== 'expiration_date'
			   && fieldName !== 'purchased_date') {
                $('#' + fieldName + '_error').text('Special characters are not allowed.').css('color', 'red');
                isValid = false;
            } else if (fieldName === 'license_description' && value.length > 500) {
                $('#' + fieldName + '_error').text('Maximum 500 characters allowed.').css('color', 'red');
                isValid = false;
            } else if (fieldName === 'client_info' && value.length > 500) {
                $('#' + fieldName + '_error').text('Maximum 500 characters allowed.').css('color', 'red');
                isValid = false;
            } else if (fieldName === 'license_cost' && parseFloat(value) < 1) {
                $('#' + fieldName + '_error').text('License cost cannot be less than $1.').css('color', 'red');
                isValid = false;
            } else if (fieldName === 'contact_email' && !isValidEmail(value)) {
                $('#' + fieldName + '_error').text('Invalid email format.').css('color', 'red');
                isValid = false;
            }else if (fieldName === 'contact_person' &&  !/^[a-zA-Z\s]+$/.test(value)) {
                $('#' + fieldName + '_error').text('Numbers and special characters are not allowed.').css('color', 'red');
                isValid = false;
            }else if (fieldName === 'contact_person2' &&  !/^[a-zA-Z\s]+$/.test(value)) {
                $('#' + fieldName + '_error').text('Numbers and special characters are not allowed.').css('color', 'red');
                isValid = false;
            } else if (fieldName === 'contact_phone' && !isValidPhoneNumber(value)) {
                $('#' + fieldName + '_error').text('Invalid phone number format.').css('color', 'red');
                isValid = false;
            }
        });
        
        // If any required field is empty, contains special characters, or exceeds the length limit, stop form submission
        if (!isValid) {
            return;
        }
		

		start_load();
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
				}else{
					alert_toast("Error saving data to the database. Please try again later.", 'error');
				}
			}
		})
				
	});

	// Function to validate email format and domain
	function isValidEmail(email) {
		var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		var validDomains = ['zemenbank.com', 'gmail.com', 'outlook.com', 'yahoo.com'];
		var domain = email.split('@')[1];
		return emailRegex.test(email) && validDomains.includes(domain);
	}

		// Function to validate phone number format
	function isValidPhoneNumber(phone) {
		var phoneRegex = /^(?:\+2519|\+2517|09|07|011)[0-9]{8}$/;
		return phoneRegex.test(phone);
	}

	$(document).ready(function() {
    $('#filterForm').submit(function(e) {
        e.preventDefault();
        var enteredYear = $('#expirationYear').val();

        $.ajax({
            url: 'filter_by_year.php',
            method: 'POST',
            data: { year: enteredYear},
            success: function(response) {
                $('#tableBody').empty(); // Clear existing table content
                $('#tableBody').append(response); // Append filtered data to the table body// Display filtered data in 'filteredData' div
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>