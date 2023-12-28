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
	function delete_license($li_key){
		extract($_POST);
		include 'db_connect.php';
		$delete = $conn->query("DELETE FROM license WHERE license_key =$li_key");
			
        
        if ($delete) {
            return 1;
        }
	}
	function save_license(){
		extract($_POST);
		$data = " license_type = '$license_type' ";
		$data .= ", license_info = '$license_info' ";
		$data .= ", client_info = '$client_info' ";
		$data .= ", purchased_date = '$purchased_date' ";
		$data .= ", contact_person = '$contact_person' ";
		$data .= ", contact_email = '$contact_email' ";
		$data .= ", contact_phone = '$contact_phone' ";
		if(empty($license_key)){
			$save = $this->db->query("INSERT INTO license set ".$data);
		}else{
			$save = $this->db->query("UPDATE license set ".$data." where license_key = ".$license_key);
		}
		if($save){
			return 1;
		}
	}
}