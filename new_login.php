<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('./header.php'); ?>
    <?php
	session_start();
  if(!isset($_SESSION['login_id']))
    header('location:login.php'); 
 ?>
    <title>ZB LICENSE | CHANGE PASSWORD</title>
    <style>
      body{
        background-color: #d12149;
      }
    </style>
</head>
<body>
<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white">
    </div>
  </div>
    <!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Welcome <?php
        $user_id = $_SESSION['login_type'];
        
        include 'db_connect.php';
        $user = $conn->query("SELECT * FROM users WHERE users.type = $user_id")->fetch_assoc();
        echo $user["username"]
         ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="p-2">
      Change your password for added security before managing licenses!
        </h6>

        <div class="container-fluid">
            <form action = "POST" id="pass-form">
            <div class="form-group">
			<label for="new_password">New Password</label>
			<input type="password" name="new_password" id="new_password" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="confirm_password">Confirm Password</label>
			<input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
		</div>
           
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="checkPasswords()">Update Password</button>
        
      </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    // Trigger the modal when the page loads
    $('#loginModal').modal('show');
  });


  function checkPasswords() {

            $('#pass-form button[type="button"]').attr('disabled', true);

            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if(newPassword == '' && confirmPassword == ''){
              // $('#pass-form').prepend('<div class="alert alert-danger">Passwords can not be empty. Please try again.</div>')
              alert_toast("Passwords can not be empty. Please try again.",'danger')

            }
            if (newPassword === confirmPassword) {
               
                if (!isValidPassword(newPassword)) {
                    // $('#pass-form').prepend('<div class="alert alert-danger">Password must contain at least one lowercase letter, one uppercase letter, one special character, one number, and be at least 8 characters long.</div>')
                    alert_toast("Password must contain at least one lowercase letter, one uppercase letter, one special character, one number, and be at least 8 characters long.", 'danger');
                    return; // Stop further execution if password is not valid
                }

                
                $.ajax({
                    url: 'ajax.php?action=update_password', 
                    method: 'POST',
                    data: {password: newPassword},
                    error:err=>{
                    console.log(err);
                  },
                  success: function (resp) {
                      if (resp == 1) {
                            console.log(resp)
                            alert_toast("Passwords Upated Successfully!",'success')
                            // $('#pass-form').prepend('<div class="alert alert-success">Passwords Upated Successfully!</div>')
                            setTimeout(function () {
                                location.href = 'index.php?home';
                            }, 500)
                             // Enable the login button after the password is successfully updated
                             $('#pass-form button[type="button"]').removeAttr('disabled');
                        }
                        else{
                          console.log(resp);
                          console.log("Error updating");
                         
                        }
                    },
                    error: function() {
                        alert('Error updating password.');
                    }
                });
            
            
        } else {
                // Passwords do not match, show an error message
                // $('#pass-form').prepend('<div class="alert alert-danger">Passwords do not match. Please try again.</div>')
                alert_toast("Passwords do not match. Please try again.",'danger')
            }
    }

      // Function to validate password
      function isValidPassword(password) {
          // Password must contain at least one lowercase letter, one uppercase letter, one special character, one number, and be at least 8 characters long
          var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;
          return passwordRegex.test(password);
      }

    window.alert_toast= function($msg = 'TEST',$bg = 'success'){
      $('#alert_toast').removeClass('bg-success')
      $('#alert_toast').removeClass('bg-danger')
      $('#alert_toast').removeClass('bg-info')
      $('#alert_toast').removeClass('bg-warning')

    if($bg == 'success')
      $('#alert_toast').addClass('bg-success')
    if($bg == 'danger')
      $('#alert_toast').addClass('bg-danger')
    if($bg == 'info')
      $('#alert_toast').addClass('bg-info')
    if($bg == 'warning')
      $('#alert_toast').addClass('bg-warning')
    $('#alert_toast .toast-body').html($msg)
    $('#alert_toast').toast({delay:3000}).toast('show');
  }
  $(document).ready(function(){
    $('#preloader').fadeOut('fast', function() {
        $(this).remove();
      })
  })
</script>
</body>
</html>