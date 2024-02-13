<?php
session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	
	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user($id){
		extract($_POST);
		include 'db_connect.php';
		$delete = $conn->query("DELETE FROM users WHERE id = $id ");
		if ($delete) {
			return 1;
		}
	}
	function delete_license($li_key) {
		include 'db_connect.php';
	
		// Fetch the license details before deletion
		$selectQuery = "SELECT * FROM license WHERE license_key = $li_key";
		$selectResult = $conn->query($selectQuery);
	
		if ($selectResult && $selectResult->num_rows > 0) {
			$row = $selectResult->fetch_assoc();
	
			// Insert the license details into the license_history table
			$insertQuery = "INSERT INTO `license_history` (`license_type`, `license_description`, 
				`license_info`, `client_info`, `license_cost`, 
				`purchased_date`, `expiration_date`, `contact_person`, 
				`contact_person2`, `contact_phone`, `contact_phone2`, 
				`contact_email`, `contact_email2`, `user_id`)
				VALUES ('{$row['license_type']}', '{$row['license_description']}',
				'{$row['license_info']}', '{$row['client_info']}', '{$row['license_cost']}',
				'{$row['purchased_date']}', '{$row['expiration_date']}', '{$row['contact_person']}',
				'{$row['contact_person2']}', '{$row['contact_phone']}', '{$row['contact_phone2']}',
				'{$row['contact_email']}', '{$row['contact_email2']}', '{$row['user_id']}')";
	
			$insertResult = $conn->query($insertQuery);
	
			if ($insertResult) {
				// After inserting into history table, delete from the original table
				$deleteQuery = "DELETE FROM license WHERE license_key = $li_key";
				$deleteResult = $conn->query($deleteQuery);
	
				if ($deleteResult) {
					return 1; // Success
				} else {
					return "Error deleting from license table: " . $conn->error;
				}
			} else {
				return "Error inserting into license_history table: " . $conn->error;
			}
		} else {
			return "License not found for key $li_key";
		}
	}
	
	function save_license(){
		extract($_POST);
		$data = " license_type = '$license_type' ";
		$data .= ", license_info = '$license_info' ";
		$data .= ", user_id = '$user_id'";
		$data .= ", license_description = '$license_description' ";
		$data .= ", client_info = '$client_info' ";
		$data .= ", license_cost = '$license_cost' ";
		$data .= ", purchased_date = '$purchased_date' ";
		$data .= ", expiration_date = '$expiration_date' ";
		$data .= ", contact_person = '$contact_person' ";
		$data .= ", contact_email = '$contact_email' ";
		$data .= ", contact_phone = '$contact_phone' ";
		$data .= ", contact_person2 = '$contact_person2' ";
		$data .= ", contact_email2 = '$contact_email2' ";
		$data .= ", contact_phone2 = '$contact_phone2' ";
		if(empty($license_key)){
			$save = $this->db->query("INSERT INTO license set ".$data);
		}else{
			$save = $this->db->query("UPDATE license set ".$data." where license_key = ".$license_key);
			// $save = $this->db->query("INSERT INTO license_history set ".$data);
		}
		if($save){
			return 1;
		}
	}
}