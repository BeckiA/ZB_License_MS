<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
	
	// Get current date
$currentDate = date("Y-m-d");
	    
include '../../db_connect.php';

// Query to fetch necessary data for upcoming expirations at specific intervals
$sql = "SELECT contact_email, contact_person, license_info, expiration_date, purchased_date 
        FROM license 
        WHERE expiration_date >= ? 
        AND expiration_date BETWEEN ? AND DATE_ADD(?, INTERVAL 6 MONTH)
        ORDER BY expiration_date";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("sss", $currentDate, $currentDate, $currentDate);

$stmt->execute();
$result = $stmt->get_result();

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
        $daysLeft = strtotime($expirationDate) - strtotime($currentDate);
        $daysLeft = floor($daysLeft / (60 * 60 * 24));

		// Define intervals in days
		$intervals = [
			180, // 6 months
			90,  // 3 months
			30,  // 1 month
			14,  // 2 weeks
			7    // 1 week
		];


		echo($daysLeft);
			
			
			
		// Send notifications at each interval

		if (in_array($daysLeft, $intervals)) {
            
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
			// $mail->addReplyTo('zemenbanklicencems@gmail.com', $name); /* Reply to the user who submitted the form from the bookings email. */
		
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
					echo('Please Check Your Email Inbox!');
				}
        } elseif ($daysLeft < 7 && $daysLeft > 0) {
            // Send daily notifications within the last week before expiration
            for ($i = $daysLeft; $i > 0; $i--) {
                
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
			// $mail->addReplyTo('zemenbanklicencems@gmail.com', $name); /* Reply to the user who submitted the form from the bookings email. */
		
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
					echo('Please Check Your Email Inbox!');
				}
            // Exit loop for intervals before 1 week
				break;  
            }
        } else {
            echo "The Date doesn't fall in a range";
        }
    }
} else {
    echo "No upcoming expirations.";
}
  
?>