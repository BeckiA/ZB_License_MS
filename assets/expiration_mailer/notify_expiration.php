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
$sql = "SELECT * 
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

	$expirationDates = array();
    // Loop through the results
    while($row = $result->fetch_assoc()) {
        // Extract data from the row
        $contactEmail = $row['contact_email'];
        $contactEmail2 = $row['contact_email2'];
        $contactPerson = $row['contact_person'];
        $contactPerson2 = $row['contact_person2'];
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
			
		// Check for duplicate expiration dates
        if (in_array($expirationDate, $expirationDates)) {
            // Duplicate found, send email to both persons

            // Send email to the first person
            sendExpirationEmail($contactEmail, $contactPerson, $licenseInfo, $expirationDate, $purchasedDate);

             
			// Send email to the second person
        	sendExpirationEmail($contactEmail2, $contactPerson2, $licenseInfo, $expirationDate, $purchasedDate);
			} else {
			// No duplicate, add the expiration date to the array
				$expirationDates[] = $expirationDate;
            // Send notifications at each interval
            if (in_array($daysLeft, $intervals)) {
            // Send email to the first person
        	 sendExpirationEmail($contactEmail, $contactPerson, $licenseInfo, $expirationDate, $purchasedDate);

			// Send email to the second person
        	sendExpirationEmail($contactEmail2, $contactPerson2, $licenseInfo, $expirationDate, $purchasedDate);
				
        
            } elseif ($daysLeft < 7 && $daysLeft > 0) {
                // Send daily notifications within the last week before expiration
                for ($i = $daysLeft; $i > 0; $i--) {
                    sendExpirationEmail($contactEmail, $contactPerson, $licenseInfo, $expirationDate, $purchasedDate);
					sendExpirationEmail($contactEmail2, $contactPerson2, $licenseInfo, $expirationDate, $purchasedDate);
                    $expirationDates[] = $expirationDate; // Add the date to the array for subsequent emails
                }
            }
        }
    }
} else {
    echo "No upcoming expirations.";
}

function sendExpirationEmail($to, $person, $info, $expDate, $purchaseDate) {
    $name = "Zemen Bank";
	$mail = new PHPMailer;
	$mail->isSMTP();
	//$mail->SMTPDebug = 2;
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->Username = 'zemenbanklicencems@gmail.com'; /* This is the sender of the bookings. */
	$mail->Password = 'mqjr ijxe jgiv tzqw';

    // $mail->setFrom('bekanimabera@gmail.com');
    $mail->addAddress($to, $person);
    // ... (existing code for setting up email)

    $mail->Subject = "License Expiration Reminder";
    $mail->isHTML(true);
    $mail->Body = "
        Hi  $person,<br><br>
        The license for $info will expire on $expDate. Please consider extending it.
        Purchased Date: $purchaseDate<br><br>
        Best regards,<br>
        Zemen Bank License Management System";
    
    if ($mail->send()) {
        echo "Email sent successfully to $to";
    } else {
        echo "Error sending email to $to: " . $mail->ErrorInfo;
    }
}
?>