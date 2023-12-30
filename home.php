<style>
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
	span.card-icon {
    position: absolute;
    font-size: 3em;
    bottom: .2em;
    color: #ffffff80;
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

.bg-custom{
	background : #d12149;
}
</style>
<nav aria-label="breadcrumb ">
  <ol class="breadcrumb">
  <li class="breadcrumb-item text-primary">Home</li>
  </ol>
</nav>
<div class="containe-fluid">
	<?php include('db_connect.php') ;
	// $files = $conn->query("SELECT f.*,u.name as uname FROM files f inner join users u on u.id = f.user_id where  f.is_public = 1 order by date(f.date_updated) desc");

	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="card col-md-4 offset-2 bg-custom float-left">
				<div class="card-body text-white">
					<h4><b>Users</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-users"></i></span>
					<h3 class="text-right"><b><?php echo $conn->query('SELECT * FROM users')->num_rows ?></b></h3>
				</div>
			</div>
			<div class="card col-md-4 offset-2 bg-custom ml-4 float-left">
				<div class="card-body text-white">
					<h4><b>Saved Licenses</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-file"></i></span>
					<h3 class="text-right"><b><?php echo $conn->query('SELECT * FROM license')->num_rows ?></b></h3>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3 ml-3 mr-3">
			<div class="card col-md-12">
				<div class="card-body">
					<table width="100%">
						<tr>
							<th width="30%" class="">License Name</th>
							<th width="30%" class="">Vendor</th>
							<th width="20%" class="">Expiration Date Left</th>
							<th width="20%" class="">Contact Person</th>
						</tr>
							<tbody>
							
     
	
							
						
						</tbody>	 
						<?php
        include 'db_connect.php';
        $licenses = $conn->query("SELECT * FROM license ORDER BY expiration_date ASC");
        $i = 1;
        while ($row = $licenses->fetch_assoc()) :
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
            ?>
            <tr style="background-color: <?php echo $bgColor; ?>; padding: 15">
                <td><?php echo $row['license_info'] ?></td>
                <td><?php echo $row['client_info'] ?></td>
                <td><?php echo $daysLeft ?></td>
                <td><?php echo $row['contact_person'] ?></td>
            </tr>
        <?php endwhile; ?>
					</table>
					
				</div>
			</div>
			
		</div>
	</div>

</div>