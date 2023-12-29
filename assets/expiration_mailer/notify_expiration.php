<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
	
	// Get current date
$currentDate = date("Y-m-d");
	    
include '../../db_connect.php';
// Query to fetch necessary data for upcoming expirations (let's say 10 days before expiration)
$sql = "SELECT contact_email, contact_person, license_info, expiration_date, purchased_date FROM license WHERE DATEDIFF( '$currentDate',expiration_date) = 11";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through the results
    while($row = $result->fetch_assoc()) {
        // Extract data from the row
        $contactEmail = $row['contact_email'];
        $contactPerson = $row['contact_person'];
        $licenseInfo = $row['license_info'];
        $expirationDateFromDB = $row['expiration_date'];
        $purchasedDate = $row['purchased_date'];

		// Convert the expiration date from the database to a date-only format
		$expirationDate = date("Y-m-d", strtotime($expirationDateFromDB));

        // Calculate days left
        $daysLeft = abs(strtotime($currentDate) - strtotime($expirationDate))  ;
        $daysLeft = floor($daysLeft / (60 * 60 * 24));
		echo($daysLeft);
        // Send email if 10 days are left
        if ($daysLeft = 11) {

			
		$name = "Zemen Bank";
		$mail = new PHPMailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = 2;
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'zemenbanklicencems@gmail.com'; /* This is the sender of the bookings. */
		$mail->Password = 'mqjr ijxe jgiv tzqw';


		$mail->setFrom('bekanimabera@gmail.com');
		$mail->addAddress($contactEmail, $contactPerson);
		$mail->addReplyTo('zemenbanklicencems@gmail.com', $name); /* Reply to the user who submitted the form from the bookings email. */

			$mail->Subject = "License Expiration Reminder";
	        $mail->isHTML(true);
	        $mail->Body = "
			Hi  $contactPerson,<br><br>
			The license for $licenseInfo will expire in $daysLeft days (on $expirationDate). Please consider extending it.
			Purchased Date: $purchasedDate<br><br>
			Best regards,<br>
			Zemen Bank License Management System";
			
			if ($mail->send()){
				echo($daysLeft);
				echo('Please Check Your Email Inbox!');}
        }else{
			echo "ERROR";
		}
    }
}else {
    echo "No upcoming expirations.";
} 
        
?>