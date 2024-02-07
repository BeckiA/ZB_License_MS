<?php 
include 'db_connect.php';

$folder_parent =  0;
if(isset($_GET['license_key'])){
	$license = $conn->query("SELECT * FROM license where license_key =".$_GET['license_key']);
	foreach($license->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}
	}
?>
<style>
	.folder-item{
		cursor: pointer;
	}
	.folder-item:hover{
		background: #eaeaea;
	    color: black;
	    box-shadow: 3px 3px #0000000f;
	}
	.custom-menu {
        z-index: 1000;
	    position: absolute;
	    background-color: #ffffff;
	    border: 1px solid #0000001c;
	    border-radius: 5px;
	    padding: 8px;
	    min-width: 13vw;
}
a.custom-menu-list {
    width: 100%;
    display: flex;
    color: #4c4b4b;
    font-weight: 600;
    font-size: 1em;
    padding: 1px 11px;
}
.file-item{
	cursor: pointer;
}
a.custom-menu-list:hover,.file-item:hover,.file-item.active {
    background: #80808024;
}
table th,td{
	/*border-left:1px solid gray;*/
}
a.custom-menu-list span.icon{
		width:1em;
		margin-right: 5px
}

</style>
<nav aria-label="breadcrumb ">
  <ol class="breadcrumb">
  
	<li class="breadcrumb-item"><a class="text-primary" href="index.php?page=licenses">Licenses</a></li>
  </ol>
</nav>
<div class="container-fluid">
	<div class="col-lg-12">

		<div class="row">
			<button class="btn btn-primary btn-sm" id="new_license"><i class="fa fa-plus"></i> New License</button>
			<!-- <button class="btn btn-primary btn-sm ml-4" id="new_file"><i class="fa fa-upload"></i> Upload File</button> -->
		</div>
		<hr>
		<div class="row">
			<div class="col-lg-12">
			<div class="col-md-4 input-group offset-4">
				
  				<input type="text" class="form-control" id="search" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="Enter year">
  				<div class="input-group-append">
   					 <span class="input-group-text" id="expirationYear" type="submit"><i class="fa fa-search"></i></span>
  				</div>
			</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><h4><b>License Manager</b></h4></div>
		</div>
		<hr>
		<div class="row">
			<div class="card col-md-12">
				<div class="card-body">
				<ul class="pagination" id="pagination"></ul>
					<table width="100%" class="table">
						<tr>
							<th width = '5%'>#</th>
							<th width = '15%'>License Type</th>
							<th width="20%" class="">License Name</th>
							<th width="15%" class="">Vendor</th>
							<th width="30%" class="">Expiration Date</th>
							<th width="15%" class="">Actions</th>
						</tr>
						<tbody id="tableBody">
						<?php
 				include 'db_connect.php';

				// Select and fetch the deleted records
				$deletedLicensesResult = $conn->query("SELECT * FROM license WHERE expiration_date < NOW() AND expiration_date IS NOT NULL AND expiration_date != '0000-00-00'");
				 
				if ($deletedLicensesResult) {
					// Loop through the deleted records and insert into history table
					while ($row = $deletedLicensesResult->fetch_assoc()) {
						// Assuming your history table is named 'license_history'
						$insertQuery = "INSERT INTO `license_history` (`license_type`, `license_description`, 
							`license_info`, `client_info`, `license_cost`, 
							`purchased_date`, `expiration_date`, `contact_person`, 
							`contact_person2`, `contact_phone`, `contact_phone2`, 
							`contact_email`, `contact_email2`)
							VALUES ('{$row['license_type']}','{$row['license_description']}',
							'{$row['license_info']}','{$row['client_info']}','{$row['license_cost']}',
							'{$row['purchased_date']}','{$row['expiration_date']}','{$row['contact_person']}',
							'{$row['contact_person2']}','{$row['contact_phone']}','{$row['contact_phone2']}',
							'{$row['contact_email']}','{$row['contact_email2']}')";
				
						$insertResult = $conn->query($insertQuery);
				
						if (!$insertResult) {
							echo "Error inserting data into history table: " . $conn->error;
							break;  // Exit the loop if insertion fails
						}
					}
				
					// echo "Deleted records have been inserted into the history table.";
				} else {
					echo "Error fetching deleted records: " . $conn->error;
				}
				   
				 // Delete expired licenses from the database
				 $deleteExpiredLicenses = $conn->query("DELETE FROM license WHERE (expiration_date < NOW() AND expiration_date IS NOT NULL AND expiration_date != '0000-00-00')");
				 
				 // Check if deletion was successful
				 if ($deleteExpiredLicenses) {
					//  echo "Expired licenses have been removed from the database.";
				 } else {
					 echo "Error removing expired licenses: " . $conn->error;
				 }
				 
				 					 

 					$licenses = $conn->query("SELECT * FROM license WHERE (expiration_date >= NOW() OR expiration_date IS NULL OR expiration_date = '0000-00-00') ORDER BY expiration_date ASC");
 					$i = 1;
 					while($row= $licenses->fetch_assoc()):
						$displayLifetime = $row['expiration_date'] == '0000-00-00' ? 'Lifetime License' : $row['expiration_date'];
				 ?>
				 <tr>
				 	<td>
				 		<?php echo $i++ ?>
				 	</td>
				 	<td>
				 		<?php echo $row['license_type'] ?>
				 	</td>
				 	<td>
				 		<?php echo $row['license_info'] ?>
				 	</td>
				 	<td>
				 		<?php echo $row['client_info'] ?>
				 	</td>
				 	<td>
				 		<?php echo $displayLifetime ?>
				 	</td>
				 	<td>
				 		<center>
								<div class="btn-group">
								  <button type="button" class="btn btn-primary">Action</button>
								  <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    <span class="sr-only">Toggle Dropdown</span>
								  </button>
								  <div class="dropdown-menu">
								    <a class="dropdown-item edit_license" href="javascript:void(0)" data-id = '<?php echo $row['license_key'] ?>'>Edit</a>
								    <div class="dropdown-divider"></div>
								    <a class="dropdown-item delete_license" href="javascript:void(0)" data-id = '<?php echo $row['license_key'] ?>'>Delete</a>
								  </div>
								</div>
								</center>
				 	</td>
				 </tr>
				<?php endwhile; ?>
			</tbody>
					</table>
					
				</div>
			</div>
			
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
    var tableRows = $('#tableBody tr'); // Select all table rows
    var rowsPerPage = 5; // Number of rows per page
    var totalRows = tableRows.length; // Total number of rows
    var totalPages = Math.ceil(totalRows / rowsPerPage); // Calculate total pages

    // Initialize pagination links
    var pagination = $('#pagination');
    for (var i = 1; i <= totalPages; i++) {
        pagination.append('<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>');
    }

    // Show first page initially
    showPage(1);

    // Pagination click event
    pagination.on('click', 'a.page-link', function(e) {
        e.preventDefault();
        var pageNum = parseInt($(this).text());
        showPage(pageNum);
    });

    // Function to show specific page
    function showPage(pageNum) {
        var start = (pageNum - 1) * rowsPerPage;
        var end = start + rowsPerPage;

        // Hide all rows
        tableRows.hide();

        // Show rows for the selected page
        tableRows.slice(start, end).show();
    }
});

	$('#new_license').click(function(){
		uni_modal('New License','manage_license.php')
	})
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


	$(document).ready(function() {
    $('#search').submit(function(e) {
        e.preventDefault();
        var enteredYear = $('#expirationYear').val();

        $.ajax({
            url: 'filter_by_year.php',
            method: 'POST',
            data: { year: enteredYear},
            success: function(response) {
                $('#tableBody').empty(); // Clear existing table content
                $('#tableBody').append(response); // Append filtered data to the table body// Display filtered data in 'filteredData' div
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>