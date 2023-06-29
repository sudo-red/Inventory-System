<?php 
include('functions.php');
if(!isset($_SESSION['BranchID'])){
	$error[] = 'Select Branch - Click Sidebar Menu';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<!-- add icon link -->
    <link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
	<script src="jquery-3.2.1.min.js"></script>
	<script>
	function increment_quantity(ProdID) {
		var inputQuantityElement = $("#input-quantity-"+ProdID);
		var newQuantity = parseInt($(inputQuantityElement).val())+1;
		save_to_db(ProdID, newQuantity);
	}

	function decrement_quantity(ProdID, price) {
		var inputQuantityElement = $("#input-quantity-"+ProdID);
		if($(inputQuantityElement).val() > 1) 
		{
		var newQuantity = parseInt($(inputQuantityElement).val()) - 1;
		save_to_db(ProdID, newQuantity);
		}
	}

	function save_to_db(ProdID, new_quantity) {
		var inputQuantityElement = $("#input-quantity-"+ProdID);
		$.ajax({
			url : "update_cart_quantity.php",
			data : "ProdID="+ProdID+"&new_quantity="+new_quantity,
			type : 'post',
			success : function(response) {
				$(inputQuantityElement).val(new_quantity);
				var totalQuantity = 0;
				$("input[id*='input-quantity-']").each(function() {
					var cart_quantity = $(this).val();
					totalQuantity = parseInt(totalQuantity) + parseInt(cart_quantity);
				});
				$("#total-quantity").text(totalQuantity);
			}
		});
	}
	</script>
</head>
<body>
	<!---HEADER--->
	<div class="header">
		<!---SIDE BAR BUTTON--->
		<button class="SideBarBtn" onclick="openSideBar()">☰</button> 

		<img class="logo" src="logo.png">
		<span class="Fae-Fae"><b>FAE-FAE</b></span>
		<img class="user" src="usericon.png">
		<span class="Username"><?php
			echo " ";
			echo $_SESSION['name'];
			?></span><br>
	</div>

	<!---SIDE BAR--->
	<div class="SideBar" id="SideBar">
		<button class="CloseSideBtn" onclick="closeSideBar()">x</button> 
		<br><br>
		<?php
		if($_SESSION['role']=="crew" || $_SESSION['role']=="manager"):
		?>
		<a href="dashboard.php">Dashboard</a><br>
		<?php endif; ?>
		
		<?php
		if($_SESSION['role']=="owner"):
		?>
		<a href="summary.php">
			<div class="sidebar-container">
				View Summary
			</div>
		</a><br><br><br>
		<?php endif; ?>
		
		
  		<a href="updateprofile.php">Update Profile</a><br>
		<?php
			if($_SESSION['role']=="owner"):
			?>
  		<a href="createbranch.php">Create branch</a><br>
  		<a href="createaccount.php">Create account</a><br>
		<?php endif; ?>
		
		<?php if($_SESSION['role']=="owner" || $_SESSION['role']=="supervisor"): ?>
  		<!--SIDEBAR - SELECT BRANCH -->
		<form method="post">
			<div id="SelectBranch">  
				<select class="dropdown-btn" style="border-radius: 10px;margin-left: 20px;" name="branch" class="dropbtn">  
				<div class="dropdown-content">
					<option value="" disabled selected style="background-color: white;">Select Branch</option> 
					<?php
						$sql = mysqli_query($conn, "SELECT * FROM branches");
						while ($row = $sql->fetch_assoc())
						{
							echo '<option style="background-color: white; color: black;" value="'.$row['BranchID'].'">'.$row['BranchName'].'</option>';
						}
					?>
				</div>
				</select>
			</div>
			<span><input type="submit" name="submit" style="margin-left:20px; border-radius: 10px;"><span>
		</form>
		<br>
		<?php endif; ?>
		
  		<br><br><br><br>
  		<a href="logout.php" style="background-color: #FEFEBE; color: black;">Log-out</a>
	</div>


	<!---ROLE--->
	<p style="margin-left:30px;">Role: <span style="color: red;">
	<?php echo $_SESSION['role'];?></span></p></span></p>
	<h2 style="text-align: center;"><b>DASHBOARD</b></h2>



	<!-- CREATE PRODUCT MODAL -->
	<!-- FOR ONWER & MANAGER -->
	<?php if($_SESSION['role']=="owner" || $_SESSION['role']=="manager"): ?>
	<button id="CreateModalBtn" class="TopModalBtn" style= "width: 150px;">Create Product</button> 				<!-- CHANGE !!!!TopModalBtn CSS!!!!-->
	<div id="CreateModal" class="topmodal">
		<div class="topmodal-content">
			<div class="topmodal-header">
				<button class="create-close">x</button> 
				<h2>Create Product</h2>
			</div>
			<div class="topmodal-body">
				<!--FORM KEME-->
				<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
				<form action="" method="post">
				<input type="name" name="ProductName" required placeholder="Enter Product Name *" style="width:90%;"><br>
				<input type="name" name="Supplier" required placeholder="Enter Supplier *" style="width:90%;"><br><br>
				<label>Date Acquired</label><br>
				<input type="date" name="DateAcquired" style="margin-top: 5px;"><br>
				<span><label>Date of Expiration</label><br>
				<input type="date" name="Expiry" required placeholder="Enter Date of Expiration" style="margin-top: 5px;"><br></span><br>
				<input type="number" name="Starting" required placeholder="Enter starting quantity*" style="width:90%;"><br>
				<input type="number" name="Quantity" required placeholder="Enter current quantity *" style="width:90%;"><br>
				<input type="text" name="Unit" required placeholder="Enter product's unit (kg, pcs, etc.)*" style="width:90%;"><br>
				<input type="submit" name="create"><br><br>
				</form>
			</div>
		</div>
	</div>

	
	<!-- UPDATE MODAL -->
	<div class="dropdown">
		<button class="dropdown-btn" onclick="ProductSearchDrop()" style="width:150px;">Update Product</button><br>
		<div id="ProductSearchDropdown" class="dropdown-content">
			<form id="update-form" method="post"> <!--<form method="post"> UPDATE: id="update-form"-->
			<input type="text" style="width:99%; margin: 1px;" placeholder="Search.." id="ProductInput" onkeyup="Updatefilter()">
				<select name="ProdID">
					<?php
						$ID = $_SESSION['BranchID'];
						$sql = mysqli_query($conn, "SELECT branches.BranchID, product.*
							FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active'");
						while ($row = $sql->fetch_assoc()) {
							echo '<option value="'.$row['ProdID'].'" data-id="'.$row['ProdID'].'"data-name="'.$row['product_name'].'" data-supplier="'.$row['supplier'].'" data-acquired="'.$row['date_acq'].'" data-expiry="'.$row['date_exp'].'"data-start="'.$row['start_amt'].'" data-quantity="'.$row['quantity'].'" data-unit="'.$row['unit'].'">'.
								$row['product_name'] . ' (batch expires: ' . date("m/d/Y", strtotime($row["date_exp"])) . ')</option>';
						}
					?> 
				</select>
				<input type="submit" style="width: 100%; margin-left: 0px;" id="UpdateModalBtn" class="TopModalBtn">
			</form>
		</div>
	</div>
	
	<div id="UpdateModal" class="topmodal">
		<div class="topmodal-content">
			<div class="topmodal-header">
				<button class="update-close" id="update-x">x</button> 
				<h2>Update Product Information</h2>
			</div>
			<div class="topmodal-body"><br>
				<!---->
				<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
				<form method="post">
				<input type="hidden" name="ProductID">
				<span><label>Product Name</label><br>
				<input type="name" name="ProductName" style="width: 90%;margin-block: 5px;"><br></span>
				<label>Supplier</label><br>
				<input type="name" name="Supplier" style="width: 90%; margin-block: 5px;"><br>
				<label>Date Acquired</label><br>
				<input type="date" name="DateAcquired" style="margin-block: 5px;"><br>
				<span><label>Date of Expiration</label><br>
				<input type="date" name="Expiry" required placeholder="Enter Date of Expiration" style="margin-block: 5px;"><br></span>
				<label>Starting Quantity</label><br>
				<input type="number" name="Starting" required placeholder="Enter starting quantity *" style="width: 90%; margin-block: 5px;"><br>
				<label>Available</label><br>
				<input type="number" name="Quantity" required placeholder="Enter current quantity *" style="width: 90%; margin-block: 5px;"><br>
				<label>Unit</label><br>
				<input type="text" name="Unit" required placeholder="Enter product's unit (kg, pcs, etc.)*" style="width: 90%; margin-block: 5px;"><br>
				<input type="submit" name="update"><br><br>
				</form> 
			</div>
		</div>
	</div>
	
	
	<!-- ARCHIVE MODAL -->
	<div class="dropdown">
		<button id="archiveprod-btn" class="dropdown-btn" onclick="AProductSearchDrop()" style= "width: 150px;">Archive Product</button>
		<div id="AProductSearchDropdown" class="dropdown-content">
			<form id="archive-form" method="post"> <!--<form method="post"> UPDATE: id="update-form"-->
			<input type="text" style="width:99%; margin: 1px;" placeholder="Search.." id="AProductInput" onkeyup="Archivefilter()">
				<select name="ProdID">
					<?php
						$ID = $_SESSION['BranchID'];
						$sql = mysqli_query($conn, "SELECT branches.BranchID, product.*
							FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active'");
						while ($row = $sql->fetch_assoc()) {
							echo '<option value="'.$row['ProdID'].'" data-id="'.$row['ProdID'].'">'.
								$row['product_name'] . ' (batch expires: ' . date("m/d/Y", strtotime($row["date_exp"])) . ')</option>';
						}
					?> 
				</select>
				<input type="submit" id="ArchiveModalBtn" style="width: 100%; margin-left: 0px;" class="TopModalBtn">
			</form>
		</div>
	</div>
					
	<div id="ArchiveModal" class="topmodal">
		<div class="topmodal-content">
			<div class="topmodal-header">
				<button class="archive-close">x</button> 
				<h2>Archive Products</h2>
			</div>
			<div class="topmodal-body"><br>
				<div style="text-align: center;">
					<form method="post">
						<h3 >Are you sure you want to archive this product?</h3>
						<input type="hidden" name="ProductID">
						<h2 id="NameProd" style="color:#FD914C"></h2>
						<input type="password" name ="password" required placeholder="Confirm with Password"><br>
						<input type="submit" name="archive">
						<br><br>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--UNARCHIVE MODAL -->
	<div class="dropdown">
		<button id="unarchiveprod-btn" class="dropdown-btn" onclick="UProductSearchDrop()" style= "width: 150px;">Unarchive Product</button>
		<div id="UProductSearchDropdown" class="dropdown-content">
			<form id="unarchive-form" method="post">
			<input type="text" style="width:99%; margin: 1px;" placeholder="Search.." id="UProductInput" onkeyup="Unarchivefilter()">
				<select name="ProdID">
					<?php
						$ID = $_SESSION['BranchID'];
						$sql = mysqli_query($conn, "SELECT branches.BranchID, product.*
							FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'archived'");
						while ($row = $sql->fetch_assoc()) {
							echo '<option value="'.$row['ProdID'].'" data-id="'.$row['ProdID'].'">'.
								$row['product_name'] . ' (batch expires: ' . date("m/d/Y", strtotime($row["date_exp"])) . ')</option>';
						}
					?> 
				</select>
				<input type="submit" id="UnarchiveModalBtn" style="width: 100%; margin-left: 0px;" class="TopModalBtn">
			</form>
		</div>
	</div>
					
	<div id="UnarchiveModal" class="topmodal">
		<div class="topmodal-content">
			<div class="topmodal-header">
				<button class="unarchive-close">x</button> 
				<h2>Unarchive Products</h2>
			</div>
			<div class="topmodal-body"><br>
				<div style="text-align: center;">
					<form method="post">
						<h3 >Are you sure you want to unarchive this product?</h3>
						<input type="hidden" name="ProductID">
						<h2 id="UNameProd" style="color:#FD914C"></h2>
						<input type="password" name ="password" required placeholder="Confirm with Password"><br>
						<input type="submit" name="unarchive">
						<br><br>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>


	<!-- GENERATE REPORT MODAL -->
	<?php if($_SESSION['role']=="manager"): ?>
	<button id="ReportModalBtn" class="TopModalBtn" style="width: 150px;">Generate Report</button> 
	<div id="ReportModal" class="topmodal">
		<div class="topmodal-content">
			<div class="topmodal-header">
				<button class="report-close">x</button> 
				<h2>Inventory as of 
					<?php
					// Set the new timezone
					date_default_timezone_set('Asia/Manila');
					$date = date('M d, Y');
					echo $date;
					?>
				</h2>
			</div>
			<div class="topmodal-body">
				<!-- LABELS -->
				<p style="font-size:70%;color:grey;">
					<span><img class="labels" src="stackicon.png"></span>Branch: 
					<?php echo $_SESSION['Location'];?>
					<span><img class="labels" src="stackicon.png"></span>Total Number of Items:
					<?php 
					$ID = $_SESSION['BranchID'];
					$result = mysqli_query($conn,"SELECT branches.BranchID, product.*
						FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active'");
					$itemnum = mysqli_num_rows($result);
					echo $itemnum;
					?>
				</p>
				<!-- TABLE -->
				<form method="post" action="update_need.php">
				<table id="ReportTable">
				<?php
				$ID = $_SESSION['BranchID'];
				$sortBy = isset($_POST["sortBy"]) ? $_POST["sortBy"] : null;
				$query = "SELECT branches.BranchID, product.*
					FROM branches
					INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active'";
					if ($sortBy != null) {
						if ($sortBy=="date_acq") {
							$query .= " ORDER BY " . $sortBy . " DESC";
						} else {
							if ($sortBy=="date_exp") {
								$query .= " ORDER BY " . $sortBy . " ASC";
							} else {
								$query .= " ORDER BY " . $sortBy;
							}
						}
					}
					
					($result = mysqli_query($conn, $query)) or die(mysqli_error());
					
					$sql = mysqli_query($conn, $query);
					$dataArray = array();
					while ($row = mysqli_fetch_assoc($sql)) {
						$dataArray[] = $row;
					}
					
					echo "<tr>";
						echo "<th></th>";
						echo "<th>Product Name</th>";
						echo "<th>Starting<br>Quantity</th>";
						echo "<th>Unit</th>";
						echo "<th>Quantity<br>Consumed</th>";
						echo "<th>Quantity<br>Needed</th>";
					echo "</tr>";
					
					foreach ($dataArray as $data) {
					// output data of each row
						echo "<tr>";
						echo '<td><input type="hidden" name="id[]" value="'.$data['ProdID'].'"></td>';
						echo "<td>" . $data["product_name"]. "</td>";
						echo "<td>" . $data["start_amt"]. "</td>";
						echo "<td>" . $data["unit"]. "</td>";
						echo "<td>" . ($data["start_amt"]-$data["quantity"]). "</td>";
						echo '<td><input type="number" name="need[]" value="'.$data['amt_need'].'"></td>';
						echo "</tr>";
					}
				?>
				</table>
				<br><br>
				<div class="modal-footer" style="text-align: right; padding-right: 30px;">
					<a href="pdf.php">Save as PDF</a>
					<input type="submit" value="Update Values">
					<br><br>
				</div>
				</form>
				<br><br>
				
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<!--Notification Messages-->
	<div class="content">
		<?php if (isset($_SESSION['success'])) : ?>
		<div class="success">
			<?php 
			echo '<h3 style="margin-left: 50px;">'.$_SESSION['success']. "</h3>"; 
			?>
		</div>
		<?php endif;?>
		
		<?php 
		unset($_SESSION['success']);
		?>
		
		
		<?php if(isset($error)): ?>
		<div class="error">
			<?php 
				foreach($error as $error)
				{
				echo '<h3 style="margin-left: 50px;">'.$error. "</h3>"; 
			 }
			?>
		</div>
		<?php endif;?>
	</div>

	<!-- SORT BY -->
	<div id="sort" class="dropdown">
		<!--<button class="dropdown-btn" style="margin-left:21px; width: 150px;">Sort Records</button>-->
		<!--<div class="dropdown-btn" style="display: block; margin: 0;">-->
		<form method="post">
			<select name="sortBy" 
				style="background-color: #FD914C; 
					   color: white;
					   padding-block: 8px;
					   border: none;" 
				onchange="this.form.submit()">
					<option value="#" style="background-color: white; color: black;">Sort Record by:</option>
					<option value="product_name" style="background-color: white; color: black;">A-Z Product Name</option>
					<option value="quantity" style="background-color: white; color: black;">Lowest to Highest Quantity</option>
					<option value="date_acq" style="background-color: white; color: black;">Recent Date Acquired</option>
					<option value="date_exp" style="background-color: white; color: black;">Nearest Expiry Date</option>
					<option value="supplier" style="background-color: white; color: black;">A-Z Supplier</option>
			</select>
		</form>
		</div>
	</div>
	
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<!--MAIN TABLE-->
		<div class="container">
			<div class="container-title">
				<p>INVENTORY - STOCKS</p>
			</div>
			<!-- LABELS -->
			<p style="font-size:70%;color:grey;">
			<span><img class="labels" src="stackicon.png"></span>Branch: 
			<?php echo $_SESSION['Location'];?>
			<span><img class="labels" src="stackicon.png"></span>Total Number of Items:
			<?php 
			$ID = $_SESSION['BranchID'];
			$result = mysqli_query($conn,"SELECT branches.BranchID, product.*
				FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active'");
			$itemnum = mysqli_num_rows($result);
			echo $itemnum;
			?>
			<span><img class="labels" src="calendaricon.png"></span>Date &amp Time:
			<?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y h:i');
			echo $date;
			?>
			</p>
			
			<!-- TABLE -->
			<table id="dashboard">
			<?php
				$ID = $_SESSION['BranchID'];
				$sortBy = isset($_POST["sortBy"]) ? $_POST["sortBy"] : null;
				$query = "SELECT branches.BranchID, product.*
					FROM branches
					INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active'";
					if ($sortBy != null) {
						if ($sortBy=="date_acq") {
							$query .= " ORDER BY " . $sortBy . " DESC";
						} else {
							if ($sortBy=="date_exp") {
								$query .= " ORDER BY " . $sortBy . " ASC";
							} else {
								$query .= " ORDER BY " . $sortBy;
							}
						}
					}
					
					($result = mysqli_query($conn, $query)) or die(mysqli_error());
					//print columns from branches-
					while ($row = mysqli_fetch_assoc($result))
					{
						$array[] = $row;
					}
					if (mysqli_num_rows($result) > 0) 
					{
					// output data of each row
						echo "<tr>";
						echo "<th>Product Name</th>";
						echo "<th>Available</th>";
						echo "<th>Unit</th>";
						echo "<th>Date Acquired</th>";
						echo "<th>Expiry Date</th>";
						echo "<th>Supplier</th>";
						echo "</tr>";
						
						
						
						foreach ($array as $rows) {
						if($rows['quantity']<($rows["start_amt"]/2)) {
							$bg = "#FFCCCB";
						} else {
							$bg = "white"; //NO STYLE
						}
						echo "<tr style='background-color:".$bg."';>";
						echo "<td>" . $rows["product_name"]. "</td>";
						echo "<td class='counter'>";
						echo "<button style='font-size: 16px;' class='decrement' data-id='" . $rows["ProdID"] . "'>–</button> ";
						echo "<input style='width:40%; text-align:center;' type='number' class='counterValue' min='1' value='" . $rows["quantity"] . "'> ";
						echo "<button style='font-size: 16px;' class='increment' data-id='" . $rows["ProdID"] . "'>+</button>";
						echo "</td>";
						echo "<td>" . $rows["unit"]. "</td>";
						echo "<td>" . date("M d, Y", strtotime($rows["date_acq"])). "</td>";
						echo "<td>" . date("M d, Y", strtotime($rows["date_exp"])). "</td>";
						echo "<td>" . $rows["supplier"]. "</td>";
						echo "</tr>";
						}
					}
			?>
			</table>
			<script src="jquery-3.2.1.min.js"></script>
			<script src="script.js"></script>
		</div>
	</div>

	<!-- SUB CONTENT -->
	<div class="sub-content">

		<!-- NEW STOCKS -->
		<div id="new-btn" class="side-modals-btn">
			<p><b>NEW STOCKS</b></p>
			<h3><?php 
			$ID = $_SESSION['BranchID'];
			$result = mysqli_query($conn,"SELECT branches.BranchID, product.*
				FROM branches INNER 
				JOIN product ON branches.BranchID='$ID' 
				AND branches.BranchID = product.BranchID 
				AND product.status= 'active'
				AND product.date_acq >= DATE_SUB(DATE(now()), INTERVAL 30 DAY)
				");
			$itemnum = mysqli_num_rows($result);
			echo $itemnum;?></h3>
			<p>as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></p>
		</div>
		<div id="NewModal" class="side-modal">
		<div class="side-modal-content">
			<div class="side-modal-header">
				<button class="new-close">x</button> 
				<h2>New Stocks as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></h2>
			</div>
			<div class="side-modal-body">
				<!--FORM KEME-->
				<table id="side-table">
				<?php
				$ID = $_SESSION['BranchID'];
				$sortBy = isset($_POST["sortBy"]) ? $_POST["sortBy"] : null;
				$query = "SELECT branches.BranchID, product.*
				FROM branches INNER 
				JOIN product ON branches.BranchID='$ID' 
				AND branches.BranchID = product.BranchID 
				AND product.status= 'active'
				AND product.date_acq >= DATE_SUB(DATE(now()), INTERVAL 30 DAY)";
					if ($sortBy != null) {
						if ($sortBy=="date_acq") {
							$query .= " ORDER BY " . $sortBy . " DESC";
						} else {
							if ($sortBy=="date_exp") {
								$query .= " ORDER BY " . $sortBy . " ASC";
							} else {
								$query .= " ORDER BY " . $sortBy;
							}
						}
					}
					
					($result = mysqli_query($conn, $query)) or die(mysqli_error());
					//print columns from branches-
					if (mysqli_num_rows($result) > 0) 
					{
					// output data of each row
						echo "<tr>";
						echo "<th>Product Name</th>";
						echo "<th>Available</th>";
						echo "<th>Unit</th>";
						echo "<th>Date Acquired</th>";
						echo "<th>Expiry Date</th>";
						echo "<th>Supplier</th>";
						echo "</tr>";
						
						while ($row = mysqli_fetch_array($result))
						{
						echo "<tr>";
						echo "<td>" . $row["product_name"]. "</td>";
						echo "<td>" . $row["quantity"]. "</td>";
						echo "<td>" . $row["unit"]. "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_acq"])). "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_exp"])). "</td>";
						echo "<td>" . $row["supplier"]. "</td>";
						echo "</tr>";
						}
					}
				?>
				</table><br>
			</div>
		</div>
		</div>

		<!-- ARCHIVED STOCKS -->
		<div id="archive-btn" class="side-modals-btn">
			<p><b>ARCHIVED STOCKS</b></p>
			<h3><?php 
			$ID = $_SESSION['BranchID'];
			$result = mysqli_query($conn,"SELECT branches.BranchID, product.*
				FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'archived'");
			$itemnum = mysqli_num_rows($result);
			echo $itemnum;
			?></h3>
			<p>as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></p>
		</div>
		<div id="Archived-Modal" class="side-modal">
		<div class="side-modal-content">
			<div class="side-modal-header">
				<button class="archived-close">x</button> 
				<h2>Archived Stocks as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></h2>
			</div>
			<div class="side-modal-body">
				<!--FORM KEME-->
				<table id="side-table">
				<?php
				$ID = $_SESSION['BranchID'];
				$sortBy = isset($_POST["sortBy"]) ? $_POST["sortBy"] : null;
				$query = "SELECT branches.BranchID, product.*
				FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'archived'";
					if ($sortBy != null) {
						if ($sortBy=="date_acq") {
							$query .= " ORDER BY " . $sortBy . " DESC";
						} else {
							if ($sortBy=="date_exp") {
								$query .= " ORDER BY " . $sortBy . " ASC";
							} else {
								$query .= " ORDER BY " . $sortBy;
							}
						}
					}
					
					($result = mysqli_query($conn, $query)) or die(mysqli_error());
					//print columns from branches-
					if (mysqli_num_rows($result) > 0) 
					{
					// output data of each row
						echo "<tr>";
						echo "<th>Date Archived</th>";
						echo "<th>Product Name</th>";
						echo "<th>Available</th>";
						echo "<th>Unit</th>";
						echo "<th>Date Acquired</th>";
						echo "<th>Expiry Date</th>";
						echo "</tr>";
						
						while ($row = mysqli_fetch_array($result))
						{
						echo "<tr>";
						if(!is_null($row["date_archived"]) ) 
						{
							echo "<td>" . date("M d, Y", strtotime($row["date_archived"])). "</td>";
						} else {
							echo "<td></td>";}
						echo "<td>" . $row["product_name"]. "</td>";
						echo "<td>" . $row["quantity"]. "</td>";
						echo "<td>" . $row["unit"]. "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_acq"])). "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_exp"])). "</td>";
						echo "</tr>";
						}
					}
				?>
				</table><br>
			</div>
		</div>
		</div>

		<!-- LOW IN STOCKS -->
		<div id="low-btn" class="side-modals-btn">
			<p><b>LOW IN STOCKS</b></p>
			<h3><?php 
			$ID = $_SESSION['BranchID'];
			$result = mysqli_query($conn,"SELECT branches.BranchID, product.*
				FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active' AND (product.start_amt/2)>product.quantity");
			$itemnum = mysqli_num_rows($result);
			echo $itemnum;
			?></h3>
			<p>as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></p>
		</div>
		<div id="LowModal" class="side-modal">
		<div class="side-modal-content">
			<div class="side-modal-header">
				<button class="low-close">x</button> 
				<h2>Low in Stocks as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></h2>
			</div>
			<div class="side-modal-body">
			<table id="side-table">
			<!--db connection & query-->
			<?php
				$ID = $_SESSION['BranchID'];
				$sortBy = isset($_POST["sortBy"]) ? $_POST["sortBy"] : null;
				$query = "SELECT branches.BranchID, product.*
				FROM branches INNER JOIN product ON branches.BranchID='$ID' AND branches.BranchID = product.BranchID AND product.status= 'active' AND (product.start_amt/2)>product.quantity";
					if ($sortBy != null) {
						if ($sortBy=="date_acq") {
							$query .= " ORDER BY " . $sortBy . " DESC";
						} else {
							if ($sortBy=="date_exp") {
								$query .= " ORDER BY " . $sortBy . " ASC";
							} else {
								$query .= " ORDER BY " . $sortBy;
							}
						}
					}
					
					($result = mysqli_query($conn, $query)) or die(mysqli_error());
					//print columns from branches-
					if (mysqli_num_rows($result) > 0) 
					{
					// output data of each row
						echo "<tr>";
						echo "<th>Product Name</th>";
						echo "<th>Available</th>";
						echo "<th>Starting<br>Quantity</th>";
						echo "<th>Unit</th>";
						echo "<th>Date Acquired</th>";
						echo "<th>Expiry Date</th>";
						echo "<th>Supplier</th>";
						echo "</tr>";
						
						while ($row = mysqli_fetch_array($result))
						{
						echo "<tr>";
						echo "<td>" . $row["product_name"]. "</td>";
						echo "<td>" . $row["quantity"]. "</td>";
						echo "<td>" . $row["start_amt"]. "</td>";
						echo "<td>" . $row["unit"]. "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_acq"])). "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_exp"])). "</td>";
						echo "<td>" . $row["supplier"]. "</td>";
						echo "</tr>";
						}
					}
			?>
			</table><br>
			</div>
		</div>
		</div>

		<!-- EXPIRING STOCKS -->
		<div id="exp-btn" class="side-modals-btn">
			<p><b>EXPIRING STOCKS</b></p>
			<h3><?php 
			$ID = $_SESSION['BranchID'];
			$result = mysqli_query($conn,"SELECT branches.BranchID, product.*
				FROM branches 
				INNER JOIN product ON branches.BranchID='$ID' 
				AND branches.BranchID = product.BranchID 
				AND product.status= 'active'
				AND product.date_exp >= DATE(now())
				AND product.date_exp <= DATE_ADD(DATE(now()), INTERVAL 7 DAY)");
			$itemnum = mysqli_num_rows($result);
			echo $itemnum;
		?></h3>
			<p>as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></p>
		</div>
		<div id="ExpModal" class="side-modal">
		<div class="side-modal-content">
			<div class="side-modal-header">
				<button class="exp-close">x</button> 
				<h2>Expiring Stocks as of <?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></h2>
			</div>
			<div class="side-modal-body">
				<!--FORM KEME-->
				<table id="side-table">
				<?php
				$ID = $_SESSION['BranchID'];
				$sortBy = isset($_POST["sortBy"]) ? $_POST["sortBy"] : null;
				$query = "SELECT branches.BranchID, product.*
				FROM branches 
				INNER JOIN product ON branches.BranchID='$ID' 
				AND branches.BranchID = product.BranchID 
				AND product.status= 'active'
				AND product.date_exp >= DATE(now())
				AND product.date_exp <= DATE_ADD(DATE(now()), INTERVAL 7 DAY)";
					if ($sortBy != null) {
						if ($sortBy=="date_acq") {
							$query .= " ORDER BY " . $sortBy . " DESC";
						} else {
							if ($sortBy=="date_exp") {
								$query .= " ORDER BY " . $sortBy . " ASC";
							} else {
								$query .= " ORDER BY " . $sortBy;
							}
						}
					}
					
					($result = mysqli_query($conn, $query)) or die(mysqli_error());
					//print columns from branches-
					if (mysqli_num_rows($result) > 0) 
					{
					// output data of each row
						echo "<tr>";
						echo "<th>Product Name</th>";
						echo "<th>Available</th>";
						echo "<th>Unit</th>";
						echo "<th>Date Acquired</th>";
						echo "<th>Expiry Date</th>";
						echo "<th>Supplier</th>";
						echo "</tr>";
						
						while ($row = mysqli_fetch_array($result))
						{
						echo "<tr>";
						echo "<td>" . $row["product_name"]. "</td>";
						echo "<td>" . $row["quantity"]. "</td>";
						echo "<td>" . $row["unit"]. "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_acq"])). "</td>";
						echo "<td>" . date("M d, Y", strtotime($row["date_exp"])). "</td>";
						echo "<td>" . $row["supplier"]. "</td>";
						echo "</tr>";
						}
					}
				?>
				</table><br>
			</div>
		</div>
		</div>
	</div>
</body>

<script>
	/*SIDE BAR MODAL*/
	function openSideBar() {
	  document.getElementById("SideBar").style.width = "20%";
	  if (window.matchMedia('screen and (max-width: 800px)').matches){
	  	document.getElementById("SideBar").style.width = "50%";
	  }
	}
	function closeSideBar() {
	  document.getElementById("SideBar").style.width = "0";
	}

	/*SIDE MODAL NEW PRODUCTS*/
	var new_modal = document.getElementById("NewModal");
	var new_btn = document.getElementById("new-btn");
	var new_close = document.getElementsByClassName("new-close")[0];

	new_btn.onclick = function() {
  		new_modal.style.display = "block"; }
  	new_close.onclick = function() {
  		new_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == new_modal) {
    		new_modal.style.display = "none";
  		}
	}

	/*SIDE MODAL ARCHIVED PRODUCTS*/
	var side_archive_modal = document.getElementById("Archived-Modal");
	var side_archive_btn = document.getElementById("archive-btn");
	var side_archive_close = document.getElementsByClassName("archived-close")[0];

	side_archive_btn.onclick = function() {
  		side_archive_modal.style.display = "block"; }
  	side_archive_close.onclick = function() {
  		side_archive_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == side_archive_modal) {
    		side_archive_modal.style.display = "none";
  		}
	}

	/*SIDE MODAL LOW IN STOCK*/
	var low_modal = document.getElementById("LowModal");
	var low_btn = document.getElementById("low-btn");
	var low_close = document.getElementsByClassName("low-close")[0];

	low_btn.onclick = function() {
  		low_modal.style.display = "block"; }
  	low_close.onclick = function() {
  		low_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == low_modal) {
    		low_modal.style.display = "none";
  		}
	}

	/*SIDE MODAL EXPIRING STOCK*/
	var exp_modal = document.getElementById("ExpModal");
	var exp_btn = document.getElementById("exp-btn");
	var exp_close = document.getElementsByClassName("exp-close")[0];

	exp_btn.onclick = function() {
  		exp_modal.style.display = "block"; }
  	exp_close.onclick = function() {
  		exp_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == exp_modal) {
    		exp_modal.style.display = "none";
  		}
	}

	/*TOP CREATE MODAL*/
	var create_modal = document.getElementById("CreateModal");
	var create_btn = document.getElementById("CreateModalBtn");
	var create_close = document.getElementsByClassName("create-close")[0];

	create_btn.onclick = function() {
  		create_modal.style.display = "block"; }
  	create_close.onclick = function() {
  		create_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == create_modal) {
    		create_modal.style.display = "none";
  		}
	}

	/*TOP UPDATE MODAL*/
	var update_modal = document.getElementById("UpdateModal");
	var update_btn = document.getElementById("UpdateModalBtn");
	var update_close = document.getElementsByClassName("update-close")[0];
	var update_form = document.querySelector("#ProductSearchDropdown form")
	
	update_btn.onclick = function() {
		update_modal.style.display = "block";}
	update_close.onclick = function() {
		update_modal.style.display = "none";}
	window.onclick = function(event) {
		if (event.target == update_modal) {
			update_modal.style.display = "none";
		}
	}
	/*Update Search*/
	function ProductSearchDrop(){
		document.getElementById("ProductSearchDropdown").classList.toggle("show");
	}

	function Updatefilter() {
		var i;
		var update_input = document.getElementById("ProductInput");
		var update_filter = update_input.value.toUpperCase();
		var update_div = document.getElementById("ProductSearchDropdown");
		var update_option = update_div.getElementsByTagName("option");

		for (i = 0; i < update_option.length; i++) {
			txtValue = update_option[i].textContent || update_option[i].innerText;
			if (txtValue.toUpperCase().indexOf(update_filter) > -1) {
			update_option[i].style.display = "";
			} else {
			update_option[i].style.display = "none";
			}
		}

		// Set the value of the ProdID field to the ID of the selected product
		var selected_option = update_div.querySelector("option[selected]");
		if (selected_option) {
			var selected_id = selected_option.getAttribute("data-id");
			var prod_id_field = document.getElementById("ProdID");
			prod_id_field.value = selected_id;
		}
	}

	
	// [5]
	update_form.addEventListener("submit", function(){
		event.preventDefault();
		/* ↓↓↓ ADDED BLOCK OF CODE ↓↓↓*/
		var selected_option = update_form.querySelector("select[name='ProdID'] option:checked");
		var product_id = selected_option.getAttribute("data-id");;
		var product_name = selected_option.getAttribute("data-name");;
		var product_supplier = selected_option.getAttribute("data-supplier");
		var product_acquired = selected_option.getAttribute("data-acquired");
		var product_expiry = selected_option.getAttribute("data-expiry");
		var product_start = selected_option.getAttribute("data-start");
		var product_quantity = selected_option.getAttribute("data-quantity");
		var product_unit = selected_option.getAttribute("data-unit");

		// Update the modal with the new product information
		var product_id_element = document.querySelector("#UpdateModal input[name='ProductID']");
		var product_name_element = document.querySelector("#UpdateModal input[name='ProductName']");
		var product_supplier_element = document.querySelector("#UpdateModal input[name='Supplier']");
		var product_acquired_element = document.querySelector("#UpdateModal input[name='DateAcquired']");
		var product_expiry_element = document.querySelector("#UpdateModal input[name='Expiry']");
		var product_start_element = document.querySelector("#UpdateModal input[name='Starting']");
		var product_quantity_element = document.querySelector("#UpdateModal input[name='Quantity']");
		var product_unit_element = document.querySelector("#UpdateModal input[name='Unit']");

		// Clear previous values from the modal
		product_id_element.value = '';
		product_name_element.value = '';
		product_supplier_element.value = '';
		product_acquired_element.value = '';
		product_expiry_element.value = '';
		product_start_element.value = '';
		product_quantity_element.value = '';
		product_unit_element.value = '';
		
		product_id_element.value = product_id;
		product_name_element.value = product_name;
		product_supplier_element.value = product_supplier;
		product_acquired_element.value = product_acquired;
		product_start_element.value = product_start;
		product_expiry_element.value = product_expiry;
		product_quantity_element.value = product_quantity;
		product_unit_element.value = product_unit;
		/* ↑↑↑ ADDED BLOCK OF CODE ↑↑↑*/
		update_modal.style.display = "block";
	});

	/*TOP ARCHIVE MODAL*/
	var archive_modal = document.getElementById("ArchiveModal");
	var archive_btn = document.getElementById("ArchiveModalBtn");
	var archive_close = document.getElementsByClassName("archive-close")[0];
	var archive_form = document.querySelector("#AProductSearchDropdown form")
	
	archive_btn.onclick = function() {
  		archive_modal.style.display = "block"; }
  	archive_close.onclick = function() {
  		archive_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == archive_modal) {
    		archive_modal.style.display = "none";
  		}
	}
	/*Archive Search*/
	function AProductSearchDrop(){
        document.getElementById("AProductSearchDropdown").classList.toggle("show");
    }

    function Archivefilter() {
          var j;
          var archive_input = document.getElementById("AProductInput");
          var archive_filter = archive_input.value.toUpperCase();
          var archive_div = document.getElementById("AProductSearchDropdown");
          var archive_option = archive_div.getElementsByTagName("option");

          for (j = 0; j < archive_option.length; j++) {
            txtValue1 = archive_option[j].textContent || archive_option[j].innerText;
            if (txtValue1.toUpperCase().indexOf(archive_filter) > -1) {
                  archive_option[j].style.display = "";
            } else {
                  archive_option[j].style.display = "none";
            }
          }
    }
	
	archive_form.addEventListener("submit", function(){
		event.preventDefault();
		/* ↓↓↓ ADDED BLOCK OF CODE ↓↓↓*/
		var selected_option = archive_form.querySelector("select[name='ProdID'] option:checked");
		var product_id = selected_option.getAttribute("data-id");
		// Update the modal with the new product information
		var product_id_element = document.querySelector("#ArchiveModal input[name='ProductID']");
		
		document.getElementById("NameProd").innerHTML = selected_option.textContent;
		// Clear previous values from the modal
		product_id_element.value = '';
		
		product_id_element.value = product_id;
		/* ↑↑↑ ADDED BLOCK OF CODE ↑↑↑*/
		archive_modal.style.display = "block";
	});
	
	/*TOP UNARCHIVE MODAL*/
	var unarchive_modal = document.getElementById("UnarchiveModal");
	var unarchive_btn = document.getElementById("UnarchiveModalBtn");
	var unarchive_close = document.getElementsByClassName("unarchive-close")[0];
	var unarchive_form = document.querySelector("#UProductSearchDropdown form")
	
	unarchive_btn.onclick = function() {
  		unarchive_modal.style.display = "block"; }
  	unarchive_close.onclick = function() {
  		unarchive_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == unarchive_modal) {
    		unarchive_modal.style.display = "none";
  		}
	}
	/*Unarchive Search*/
	function UProductSearchDrop(){
        document.getElementById("UProductSearchDropdown").classList.toggle("show");
    }

    function Unarchivefilter() {
          var j;
          var unarchive_input = document.getElementById("UProductInput");
          var unarchive_filter = unarchive_input.value.toUpperCase();
          var unarchive_div = document.getElementById("UProductSearchDropdown");
          var unarchive_option = unarchive_div.getElementsByTagName("option");

          for (j = 0; j < unarchive_option.length; j++) {
            txtValue1 = unarchive_option[j].textContent || unarchive_option[j].innerText;
            if (txtValue1.toUpperCase().indexOf(unarchive_filter) > -1) {
                  unarchive_option[j].style.display = "";
            } else {
                  unarchive_option[j].style.display = "none";
            }
          }
    }
	
	unarchive_form.addEventListener("submit", function(){
		event.preventDefault();
		/* ↓↓↓ ADDED BLOCK OF CODE ↓↓↓*/
		var selected_option = unarchive_form.querySelector("select[name='ProdID'] option:checked");
		var product_id = selected_option.getAttribute("data-id");
		// Update the modal with the new product information
		var product_id_element = document.querySelector("#UnarchiveModal input[name='ProductID']");
		
		document.getElementById("UNameProd").innerHTML = selected_option.textContent;
		// Clear previous values from the modal
		product_id_element.value = '';
		
		product_id_element.value = product_id;
		/* ↑↑↑ ADDED BLOCK OF CODE ↑↑↑*/
		unarchive_modal.style.display = "block";
	});
	
	/*TOP REPORT MODAL*/
	var report_modal = document.getElementById("ReportModal");
	var report_btn = document.getElementById("ReportModalBtn");
	var report_close = document.getElementsByClassName("report-close")[0];

	report_btn.onclick = function() {
  		report_modal.style.display = "block"; }
  	report_close.onclick = function() {
  		report_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == report_modal) {
    		report_modal.style.display = "none";
  		}
	}
</script>
</html>