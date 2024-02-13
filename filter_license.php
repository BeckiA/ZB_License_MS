<?php
include 'db_connect.php';

if (isset($_POST['query'])) {
    $enteredQuery = $_POST['query'];
    $filteredResult = '';

    $licenses = $conn->query("SELECT * FROM license WHERE YEAR(expiration_date) = '$enteredQuery' OR license_info LIKE '%$enteredQuery%' OR license_type LIKE '%$enteredQuery%'OR client_info LIKE '%$enteredQuery%'");

    if ($licenses->num_rows > 0) {
    // Start HTML table
        // Add table header and loop through filtered data to create table rows
        $i = 1;
        while ($row = $licenses->fetch_assoc()) {

            // Construct table rows based on retrieved data
            $filteredResult .= '<tr>';
            $filteredResult .= '<td>' .$i++. '</td>';
            $filteredResult .= '<td>' . $row['license_type'] . '</td>';
            $filteredResult .= '<td>' . $row['license_info'] . '</td>';
            $filteredResult .= '<td>' . $row['client_info'] . '</td>';
            $filteredResult .= '<td>' . $row['expiration_date'] . '</td>';
            $filteredResult .= '<td>';
$filteredResult .= '<center>';
$filteredResult .= '<div class="btn-group">';
$filteredResult .= '<button type="button" class="btn btn-primary">Action</button>';
$filteredResult .= '<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
$filteredResult .= '<span class="sr-only">Toggle Dropdown</span>';
$filteredResult .= '</button>';
$filteredResult .= '<div class="dropdown-menu">';
$filteredResult .= '<a class="dropdown-item edit_license" href="javascript:void(0)" data-id="' . $row['license_key'] . '">Edit</a>';
$filteredResult .= '<div class="dropdown-divider"></div>';
$filteredResult .= '<a class="dropdown-item delete_license" href="javascript:void(0)" data-id="' . $row['license_key'] . '">Delete</a>';
$filteredResult .= '</div>';
$filteredResult .= '</div>';
$filteredResult .= '</center>';
$filteredResult .= '</td>';
           
            $filteredResult .= '</tr>';
        }
    
    } else {
        $filteredResult .= '<td width = "100%">';
        $filteredResult .= 'No data found for the entered query.';
        $filteredResult .= '</td>';
        // $filteredResult .="No data found for the entered query.";
    }

    echo $filteredResult;
} else {
    echo 'Year not provided.';
}
?>

<script>
    $('.edit_license').click(function(){
	uni_modal('Edit License','manage_license.php?license_key='+$(this).attr('data-id'))
	})

    $('.delete_license').click(function(){
		
		$li_key=$(this).attr('data-id');
     start_load()

$.ajax({
	url:'ajax.php?action=delete_license&license_key='+$(this).attr('data-id'),
	method:'POST',
	data:$(this).serialize(),
	success:function(resp){
		if(resp ==1){
			alert_toast("Data successfully Deleted",'success')
			setTimeout(function(){
				location.reload()
			},500)
		}
	}
})
	})
</script>