<?php
include 'db_connect.php';

if (isset($_POST['year'])) {
    $enteredYear = $_POST['year'];
    $filteredResult = '';

    $licenses = $conn->query("SELECT * FROM license WHERE YEAR(expiration_date) = '$enteredYear'");

    if ($licenses->num_rows > 0) {
    // Start HTML table
        // Add table header and loop through filtered data to create table rows
        while ($row = $licenses->fetch_assoc()) {

            // Calculate days left
            $currentDate = date("Y-m-d");
            $expirationDate = $row['expiration_date'];
            $daysLeft = strtotime($expirationDate) - strtotime($currentDate);
            $daysLeft = floor($daysLeft / (60 * 60 * 24));

            // Determine background color based on days left
            $bgColor = '';
            if ($daysLeft <= 7) {
                $bgColor = '#ff758f'; // Expired
            } elseif ($daysLeft <= 30) {
                $bgColor = '#ffe97f'; // Within a month
            } else {
                $bgColor = '#80ed99'; // More than a month left
            }
            // Construct table rows based on retrieved data
            $filteredResult .= '<tr style="background-color: ' . $bgColor . ';">';
            $filteredResult .= '<td>' . $row['license_info'] . '</td>';
            $filteredResult .= '<td>' . $row['client_info'] . '</td>';
            $filteredResult .= '<td>' . $daysLeft . '</td>';
            $filteredResult .= '<td>' . $row['contact_person']  . '</td>';
           
            $filteredResult .= '</tr>';
        }
    
    } else {
        $filteredResult = 'No data found for the entered year.';
    }

    echo $filteredResult;
} else {
    echo 'Year not provided.';
}
?>
