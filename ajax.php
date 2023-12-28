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
// if($action == 'save_user'){
// 	$save = $crud->save_user();
// 	if($save)
// 		echo $save;
// }
if($action == 'save_license'){
	$save = $crud->save_license();
	if($save)
		echo $save;
}
if($action == 'delete_license1'){
    $license_key = $_GET['license_key12'];
    // Use prepared statements to prevent SQL injection
    $delete = $crud->delete_license	($license_key);
    // $stmt->bind_param("s", $license_key);
    // if ($stmt->execute()) {
    //     echo 1;
    // } else {
    //     echo 0;
    // }
	if ($delete) {
		echo $delete;
	}
}


?>

