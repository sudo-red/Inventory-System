<?php include('functions.php');?>
<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/summary.css?v=<?php echo time(); ?>">
	<title>SUMMARY</title>
</head>
<body>
	<!---HEADER--->
	<div class="header">
		<!---SIDE BAR BUTTON--->
		<button class="SideBarBtn" onclick="openSideBar()">☰</button> 

		<img class="logo" src="logo.png">
		<span class="Fae-Fae"><b>FAE-FAE</b></span>
		<img class="user" src="usericon.png">
		<span class="Username"><?php echo $_SESSION['name']; ?></span>
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

	<h2 style="text-align: center;"><b>SUMMARY OF STOCKS</b></h2>


	<!--DATE-->
	<div class="container-title">
		<p>Today is <b><?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></b></p>
	</div>


	<div class="row">
		<!--AVAILABLE CONTENT-->
		<div class="available-content">
			<div class="available-container">
				<h4>Available Stocks</h4>
				<p style="font-size: 14px; color: #FFBE65;">Branches that have generated their report for the day are <u>Underlined</u></p>
				<?php
				$conn = new mysqli($host_name, $username, $password, $database);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				// Execute the SQL query to retrieve all distinct branches
				$sqlBranches = "SELECT DISTINCT BranchName FROM branches";
				$resultBranches = $conn->query($sqlBranches);

				$branches = array();
				while ($rowBranches = $resultBranches->fetch_assoc()) {
					$branches[] = $rowBranches['BranchName'];
				}

				// Execute the SQL query to retrieve product names and total quantity for each branch
				$sqlProducts = "SELECT P.product_name, P.unit, ";
				foreach ($branches as $branch) {
					$sqlProducts .= "(SELECT SUM(quantity) FROM product WHERE product_name = P.product_name AND status='active' AND BranchID IN (SELECT BranchID FROM branches WHERE BranchName = '$branch')) AS '$branch', ";
				}
				$sqlProducts = rtrim($sqlProducts, ', ');
				$sqlProducts .= " FROM product P
								 WHERE P.status = 'active'
								 GROUP BY P.product_name";

				$resultProducts = $conn->query($sqlProducts);

				// Output the table
				echo "<table id='available-table'>";
				echo "<tr><th>Product Name</th>";
				echo "<th><br>Unit</th>";

				// Output branch headers
				foreach ($branches as $branch) {
					if ($branch == "Ibaan"){
						echo "<th><mark style='background-color: white;'>MAIN:</mark><br><p>$branch</p></th>";
					} else {
						echo "<th><br>$branch</th>";
					}
				}
				echo "<th>Total<br>Quantity Available</th>";
				echo "</tr><br>";

				if ($resultProducts->num_rows > 0) {
					while ($rowProducts = $resultProducts->fetch_assoc()) {
						echo "<tr>";
						echo "<td>" . $rowProducts["product_name"] . "</td>";
						echo "<td>" . $rowProducts["unit"] . "</td>";

						// Output total quantity for each branch
						$totalQuantity = 0;
						foreach ($branches as $branch) {
							$quantity = $rowProducts[$branch] ?? 0;
							echo "<td>" . $quantity . "</td>";
							$totalQuantity += $quantity;
						}
						echo "<td>" . $totalQuantity . "</td>";
						echo "</tr>";
					}
				} else {
					echo "<tr><td colspan='" . (count($branches) + 1) . "'>No data found.</td></tr>";
				}

				echo "</table><br>";

				$conn->close();
				?>
			</div>
		</div>


		<!--NEEDED CONTENT-->
		<div class="needed-content">
			<div class="needed-container">
				<h4>Needed Stocks</h4>
				<p style="font-size: 14px;">Branches that have generated their report for the day are <u>Underlined</u></p>
				<?php
				$conn = new mysqli($host_name, $username, $password, $database);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				// Execute the SQL query to retrieve all distinct branches
				$sqlBranches = "SELECT DISTINCT BranchName FROM branches";
				$resultBranches = $conn->query($sqlBranches);

				$branches = array();
				while ($rowBranches = $resultBranches->fetch_assoc()) {
					$branches[] = $rowBranches['BranchName'];
				}

				// Execute the SQL query to retrieve product names and total quantity for each branch
				$sqlProducts = "SELECT P.product_name, P.unit, ";
				foreach ($branches as $branch) {
					$sqlProducts .= "(SELECT SUM(amt_need) FROM product WHERE product_name = P.product_name AND status='active' AND BranchID IN (SELECT BranchID FROM branches WHERE BranchName = '$branch')) AS `$branch`, ";
				}
				$sqlProducts = rtrim($sqlProducts, ', ');
				$sqlProducts .= " FROM product P
								 WHERE P.status = 'active'
								 GROUP BY P.product_name";

				$resultProducts = $conn->query($sqlProducts);
				
				$branchDates = array();
				foreach ($branches as $branch) {
					$sqlGen = "(SELECT LastGenerated FROM branches WHERE BranchName = '$branch')";
					$resultGen = $conn->query($sqlGen);
					$rowGen = $resultGen->fetch_assoc();
					$branchDates[$branch] = $rowGen['LastGenerated'];
				}

				// Output the table
				echo "<table id='needed-table'>";
				echo "<tr><th>Product Name</th>";
				echo "<th><br>Unit</th>";
				
				foreach ($branches as $branch) {
					$underline = '';
					date_default_timezone_set('Asia/Manila');
					if ($branchDates[$branch] == date('Y-m-d')) {
						$underline = "text-decoration: underline;";
					}
					if ($branch == "Ibaan"){
						echo "<th><mark style='background-color: white;'>MAIN:</mark><br><p style='$underline'>$branch</p></th>";
					} else {
						echo "<th style='$underline'><br>$branch</th>";
					}
					
				}
				echo "<th>Total<br>Quantity Needed</th>";
				echo "</tr><br>";
				
				if ($resultProducts->num_rows > 0) {
					while ($rowProducts = $resultProducts->fetch_assoc()) {
						echo "<tr>";
						echo "<td>" . $rowProducts["product_name"] . "</td>";
						echo "<td>" . $rowProducts["unit"] . "</td>";

						// Output total quantity for each branch
						$totalQuantity = 0;
						foreach ($branches as $branch) {
							$quantity = $rowProducts[$branch] ?? 0;
							echo "<td>" . $quantity . "</td>";
							$totalQuantity += $quantity;
						}

						echo "<td>" . $totalQuantity . "</td>";
						echo "</tr>";
					}
				} else {
					echo "<tr><td colspan='" . (count($branches) + 2) . "'>No data found.</td></tr>";
				}

				echo "</table><br>";

				$conn->close();
				?>
			</div>
		</div>
	</div>

	<br><br>

	<!--SUMMARY CONTENT-->
	<div class="summary-content">
		<div class="summary-container">
			<h4>Available vs Needed</h4>
			<?php
				$conn = new mysqli($host_name, $username, $password, $database);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				// Execute the SQL query to retrieve all distinct branches
				$sqlBranches = "SELECT DISTINCT BranchName FROM branches";
				$resultBranches = $conn->query($sqlBranches);

				$branches = array();
				while ($rowBranches = $resultBranches->fetch_assoc()) {
					$branches[] = $rowBranches['BranchName'];
				}

				// Execute the SQL query to retrieve product names and total quantity for each branch
				$sqlProducts = "SELECT P.product_name, P.unit, ";
				foreach ($branches as $branch) {
					$sqlProducts .= "(SELECT SUM(quantity) FROM product WHERE product_name = P.product_name AND status='active' AND BranchID IN (SELECT BranchID FROM branches WHERE BranchName = '$branch')) AS '$branch', ";
				}
				$sqlProducts = rtrim($sqlProducts, ', ');
				$sqlProducts .= " FROM product P
								 WHERE P.status = 'active'
								 GROUP BY P.product_name";

				$resultProducts = $conn->query($sqlProducts);
				
				// Query for getting total quantity needed
				$sqlNeeded = "SELECT P.product_name, P.unit, ";
				foreach ($branches as $branch) {
					$sqlNeeded .= "(SELECT SUM(amt_need) FROM product WHERE product_name = P.product_name AND status='active' AND BranchID IN (SELECT BranchID FROM branches WHERE BranchName = '$branch')) AS '$branch', ";
				}
				$sqlNeeded = rtrim($sqlNeeded, ', ');
				$sqlNeeded .= " FROM product P
								 WHERE P.status = 'active'
								 GROUP BY P.product_name";

				$resultNeeded = $conn->query($sqlNeeded);

				// Output the table
				echo "<table id='summary-table'>";
				echo "<tr><th>Product Name</th>";
				echo "<th>Unit</th>";
				echo "<th>Total Available</th>";
				echo "<th>Total Needed</th>";
				echo "<th>Variance</th>";
				echo "</tr><br>";

				if ($resultProducts->num_rows > 0) {
					//Available Column (incl total)
					while (($rowProducts = $resultProducts->fetch_assoc()) && ($rowNeeded = $resultNeeded->fetch_assoc()))  {
						echo "<tr>";
						echo "<td>" . $rowProducts["product_name"] . "</td>";
						echo "<td>" . $rowProducts["unit"] . "</td>";

						// Output total quantity for each branch
						$totalQuantity = 0;
						foreach ($branches as $branch) {
							$quantity = $rowProducts[$branch] ?? 0;
							$totalQuantity += $quantity;
						}
						echo "<td>" . $totalQuantity . "</td>";
						
						//Needed Column (incl total)
						$totalNeeded = 0;
						foreach ($branches as $branch) {
							$quantity = $rowNeeded[$branch] ?? 0;
							$totalNeeded += $quantity;
						}
						echo "<td>" . $totalNeeded . "</td>";
						echo "<td>" . ($totalQuantity-$totalNeeded) . "</td>";
						echo "</tr>";
					}
				} else {
					echo "<tr><td colspan='" . (count($branches) + 1) . "'>No data found.</td></tr>";
				}

				echo "</table><br>";

				$conn->close();
				?>
		</div>
	</div>


</body>

<script>
	/*SIDE BAR MODAL*/
	function openSideBar() {
	  document.getElementById("SideBar").style.width = "20%";
	  if (window.matchMedia('screen and (max-width: 600px)').matches) {
	  	document.getElementById("SideBar").style.width = "70%";
	  }
	}
	function closeSideBar() {
	  document.getElementById("SideBar").style.width = "0";
	}
</script>

</html>