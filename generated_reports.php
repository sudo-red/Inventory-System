<?php include('functions.php');
error_reporting(0);
if(!isset($_SESSION['BranchID'])){
	$error[] = 'Select Branch - Click Sidebar Menu';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Needed Stocks Summary</title>
	<!-- add icon link -->
    <link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/summary.css?v=<?php echo time(); ?>">
	<title></title>
</head>
<body>
	<!---HEADER--->
	<div class="header">
		<!---SIDE BAR BUTTON--->
		<button class="SideBarBtn" onclick="openSideBar()">☰</button> 

		<img class="logo" src="logo.png">
		<span class="Fae-Fae"><b>FAE-FAE</b></span>
		<img class="user" src="usericon.png">
		<span class="Username"> <?php echo $_SESSION['name'];?></span>
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
		<a href="summary_available.php">
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
	<h2 style="text-align: center;"><b>SUMMARY OF STOCKS</b></h2>

	<div class="button-container">
		<button onclick="window.location.href='summary_available.php';" style="font-size: 20px; border-radius: 10px;">Available</button>
		<button onclick="window.location.href='summary_needed.php';" style="font-size: 20px; border-radius: 10px;">Needed</button><br><br>
		<button onclick="window.location.href='generated_reports.php';" style="font-size: 20px; border-radius: 10px;">Generated Reports</button>
	</div>

	<br><br>

	<!--Main Content-->
	<div class="summary-container" id="needed">
		<div class="container-title">
			<p><b>Today is  </b><?php
			// Set the new timezone
			date_default_timezone_set('Asia/Manila');
			$date = date('M d, Y');
			echo $date;
			?></p>
		</div><br>
		
		<?php
		$conn = new mysqli($host_name, $username, $password, $database);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// Execute the SQL query to retrieve all distinct branches
		$sqlBranches = "SELECT * FROM branches";
		$resultBranches = $conn->query($sqlBranches);

		$branches = array();
		while ($rowBranches = $resultBranches->fetch_assoc()) {
			$branches[] = $rowBranches;
		}

		// Output the table
		echo "<table id='summary-needed'>";
		echo "<tr><th>Branch Name</th>";
		echo "<th>Last Known Generated Report</th></tr>";
		foreach ($branches as $rowBranches) {
			echo "<tr><td>" . $rowBranches["BranchName"]. "</td>";
			
			if (is_null($rowBranches["LastGenerated"])) {
				$date = '';
			} else {
				$date = date("M d, Y", strtotime($rowBranches["LastGenerated"]));
			}
			echo "<td>" .$date. "</td></tr>";
		}
		echo "</table>";

		$conn->close();
		?>
	</div>

	<br><br>

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