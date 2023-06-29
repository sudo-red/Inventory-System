<?php include('functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Branch</title>
	<!-- add icon link -->
    <link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/createbranch.css?v=<?php echo time(); ?>">
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
	<h2 style="text-align: center;"><b>CREATE BRANCH</b></h2>

	<!---NOTIFICATION MESSAGE--->
	<div class="content">
		<?php if (isset($_SESSION['success'])) : ?>
		<div class="success" >
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

	<div class="container">
		<h3>Branch Information</h3>
		<div class="form">
			<form method="post">
				<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
				<p>Enter Branch Name<b style="color:red;">*</b></p>
				<input type="text" name="name" required placeholder="Branch Name">
				<p>Enter Branch Province / City<b style="color:red;">*</b></p>
				<input type="text" name="province" required placeholder="Branch Province / City">
				<p>Enter Branch Street<b style="color:red;">*</b></p>
				<input type="text" name="street" required placeholder="Branch Street">
				<p>Enter Branch Unit<b style="color:red;">*</b></p>
				<input type="text" name="unit" required placeholder="Branch Unit">
				<p>Confirm with Password<b style="color:red;">*</b></p>
				<input type="password" name="password" required placeholder="Enter your Password"><br><br>
				<input type="submit" name="cbranch" required placeholder="Submit" style ="background-color: #FD914C; height: 50px">
			</form><br>
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
</script>
</html>