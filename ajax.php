<?php
ob_start();
$action = $_GET['action'];
// $license_key = $_GET['license_key'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}

if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$user_id = $_GET['user_id'];
	$delete = $crud->delete_user($user_id);
	if($delete)
		echo $delete;
}
if($action == 'save_license'){
	$save = $crud->save_license();
	if($save)
		echo $save;
}
if($action == 'delete_license'){

    $license_key = $_GET['license_key'];
    $delete = $crud->delete_license($license_key);
    
	if ($delete) {
		echo $delete;
	}
}


?>

