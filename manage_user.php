<?php 
include('db_connect.php');
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	
	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
			<span class="error-message" id="name_error"></span>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required>
			<span class="error-message" id="username_error"></span>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="<?php echo isset($meta['password']) ? $meta['id']: '' ?>" required>
			<span class="error-message" id="password_error"></span>
		</div>
		<div class="form-group">
			<label for="type">User Role</label>
			<select name="type" id="type" class="custom-select">
				<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>System Admin</option>
				<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Database</option>
				<option value="3" <?php echo isset($meta['type']) && $meta['type'] == 3 ? 'selected': '' ?>>Software & CBS</option> 
				<option value="4" <?php echo isset($meta['type']) && $meta['type'] == 4 ? 'selected': '' ?>>Digital</option> 
				<option value="5" <?php echo isset($meta['type']) && $meta['type'] == 5 ? 'selected': '' ?>>Cyber</option> 
				<option value="6" <?php echo isset($meta['type']) && $meta['type'] == 6 ? 'selected': '' ?>>Infrastructure</option> 
				<option value="7" <?php echo isset($meta['type']) && $meta['type'] == 7 ? 'selected': '' ?>>IT Support</option> 
			</select>
		</div>
	</form>
</div> 
<script>
	$('#manage-user').submit(function(e){
		e.preventDefault();
		// Reset error messages
		$('.error-message').text('');

		var isValid = true;
        $(this).find(':input[required]').each(function() {
            var value = $(this).val().trim();
            var fieldName = $(this).attr('name');
            if (!value) {
                $('#' + fieldName + '_error').text('* This is a required field.').css('color', 'red');
                isValid = false;
            }else if (fieldName === 'name' &&  !/^[a-zA-Z\s]+$/.test(value)) {
                $('#' + fieldName + '_error').text('Name can not contain numbers and special characters.').css('color', 'red');
                isValid = false;
            }else if (fieldName === 'username' &&  !/^[a-zA-Z0-9\s]+$/.test(value)) {
                $('#' + fieldName + '_error').text('Username can not contain special characters.').css('color', 'red');
                isValid = false;
            }else if (fieldName === 'password' && !isValidPassword(value)) {
                $('#' + fieldName + '_error').text('Password must contain at least one lowercase letter, one uppercase letter and one special character').css('color', 'red');
                isValid = false;
            }

		});

		// If any required field is empty, contains special characters, or exceeds the length limit, stop form submission
        if (!isValid) {
            return;
        }

		function isValidPassword(password) {
		// Password must contain at least one lowercase letter, one uppercase letter, one special character, one number, and be at least 8 characters long
		var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+}{"':;?/>.<,]).+$/;
		return passwordRegex.test(password);
	}


		start_load()
		$.ajax({
			url:'ajax.php?action=save_user',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>