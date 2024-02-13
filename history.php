<nav aria-label="breadcrumb ">
  <ol class="breadcrumb">
  <li class="breadcrumb-item text-primary">License History</li>
  </ol>
</nav>


<div class="container-fluid">
	<div class="col-lg-12">

		<div class="row">
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
			<div class="col-md-12"><h4><b>Saved License Histories</b></h4></div>
		</div>
		<hr>
		<div class="row">
			<div class="card col-md-12">
				<div class="card-body">
				<ul class="pagination" id="pagination"></ul>
					<table width="50%" class="table">
						<tr>
							<th width ='5%'>#</th>
							<th width ='15%'>License Type</th>
							<th width="15%" class="">License Name</th>
							<th width="15%" class="">Vendor</th>
							<th width="15%" class="">Expiration Date</th> 
							<th width="15%" class="">License Cost</th> 
							<th width="20%" class="">Purchased Date</th> 
						</tr>
						<tbody id="tableBody">
                        <?php
 					include 'db_connect.php';
					
					$userID = $_SESSION['login_type'];


					$adminUserID = 1;

					// Check if the user is an admin
					$isUserAdmin = ($userID == $adminUserID);

					// Define the WHERE clause based on the user's role
					$whereClause = $isUserAdmin ? "" : "AND users.type = $userID";

					$licenses = $conn->query("SELECT license_history.*, users.username 
											FROM license_history
											LEFT JOIN users ON license_history.user_id = users.type
											WHERE (expiration_date >= NOW() OR expiration_date IS NULL OR expiration_date = '0000-00-00')
											$whereClause
											ORDER BY expiration_date ASC");
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
				 		<?php echo $row['license_cost'] ?>
				 	</td>
				 	<td>
				 		<?php echo $row['purchased_date'] ?>
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