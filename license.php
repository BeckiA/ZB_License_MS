<?php 
include 'db_connect.php';
// include '';
$folder_parent =  0;
// $folders = $conn->query("SELECT * FROM folders where parent_id = $folder_parent and user_id = '".$_SESSION['login_id']."'  order by name asc");


// $files = $conn->query("SELECT * FROM files where folder_id = $folder_parent and user_id = '".$_SESSION['login_id']."'  order by name asc");

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
  
<?php 
	$id=$folder_parent;
	while($id > 0)
	{ 

	$path = $conn->query("SELECT * FROM folders where id = $id  order by name asc")->fetch_array(); 
?>
	<li class="breadcrumb-item text-primary"><?php echo $path['name']; ?></li>
<?php
	$id = $path['parent_id'];	
	} 
?> 
	<li class="breadcrumb-item"><a class="text-primary" href="index.php?page=files">Licenses</a></li>
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
				
  				<input type="text" class="form-control" id="search" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
  				<div class="input-group-append">
   					 <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa fa-search"></i></span>
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
					<table width="100%">
						<tr>
							<th width="40%" class="">Filename</th>
							<th width="20%" class="">Date</th>
							<th width="40%" class="">Description</th>
						</tr>
						
					</table>
					
				</div>
			</div>
			
		</div>
	</div>
</div>

<script>
	
	$('#new_license').click(function(){
		uni_modal('New License','manage_license.php')
	})
</script>