<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin | Blog Site</title>
 	

<?php include('./header.php'); ?>
<?php 
session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");
?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		background:#d12149;
		display: flex;
		align-items: center;
	}
	#login-right .card{
		margin: auto
	}
	.logo h1 {
   text-align: center;
   color: #fff;
   margin-left: 55px;
   font-size: 75px;
   font-family:'Times New Roman', Times, serif;
}
.logo h3{
	color: #fff;
    text-align: center;
    font-size: 30px;
    margin-top: 15px;
	margin-left: 75px;
}
.logo p{
	text-align: center;
    margin-top: 15px;
	margin-left: 35px;
	
}

.text-primary{
	color: #d12149 !important;
}
.btn-primary {
	background-color : #d12149 !important;
	border-color : #d12149 !important;
}
</style>

<body>


  <main id="main" class=" alert-info">
  		<div id="login-left" class = "d-flex flex-column align-items-center justify-content-center">
  			<div class="logo d-flex flex-column align-items-center justify-content-center">
				<img src="assets/img/zb_logo.png" alt="Zemen Logo" class = "">
			  <h1 class = 'text-center'>Zemen Bank</h1>
              <h3> License Management System</h3>
    
  			</div>
  		</div>
  		<div id="login-right">
  			<div class="w-100">
  				<h4 class="text-primary text-center"><b>License Management System</b></h4>
  				<br>
  			
  			<div class="card col-md-8">
  				<div class="card-body">
  					<form id="login-form" >
  						<div class="form-group">
  							<label for="username" class="control-label text-primary">Username</label>
  							<input type="text" id="username" name="username" class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="text-primary">Password</label>
  							<input type="password" id="password" name="password" class="form-control">
  						</div>	
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
  					</form>
  				</div>
  			</div>
  		</div>
   		</div>

  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.reload('index.php?page=home');
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>