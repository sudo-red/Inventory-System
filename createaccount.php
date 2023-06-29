<?php include('functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Account</title>
	<!-- add icon link -->
    <link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/createaccount.css?v=<?php echo time(); ?>">
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
	<h2 style="text-align: center;"><b>CREATE ACCOUNT</b></h2>

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
		<h3>Account Information</h3>
		<div class="form">
			<p style="font-size: 14px;"><b style="color:red;">*</b> required fields</p>
			<form method="post">
				<p>Username<b style="color:red;">*</b></p>
				<input type="text" name="name" required placeholder="Enter username">
				<p>Phone Number<b style="color:red;">*</b></p>
				<input type="text" name="phone" placeholder="09XXXXXXXXX">
				<p>Email<b style="color:red;">*</b></p>
				<input type="text" name="email" required placeholder="Enter Valid Email">
				<p>Role<b style="color:red;">*</b></p>
				<select name="user_type" style="width: 90%; padding: 12px;" required>
					<option value="manager">Manager</option>
					<option value="supervisor">Supervisor</option>
					<option value="crew">Crew</option>
				</select>
				<p>Branch<b style="color:red;">*</b></p>
				<select name="branch" style="width: 90%; padding: 12px;" required>
					<?php
						$sql = mysqli_query($conn, "SELECT * FROM branches");
						while ($row = $sql->fetch_assoc())
						{
							echo '<option value="'.$row['BranchID'].'">'.$row['BranchName'].'</option>';
						}
					?>
				</select>
				<p>Password<b style="color:red;">*</b></p>
				<input type="password" name="password" id="npass" required placeholder="Enter Password"><br>
				

				<p>Confirm New Password<b style="color:red;">*</b></p>
				<input type="password" name="cpassword" required placeholder="Enter Password"><br><br><br>
				
				<p>Verify with Owner Password<b style="color:red;">*</b></p>
				<input type="password" name="owner" required placeholder="Enter Your Password"><br><br>
				<input type="submit" name="register" required placeholder="Submit">

				<!--PASSWORD HANDLING-->
				<div id="message">
					<p style="padding-block: 10px; margin-left: 0px;">Password must contain the following:</p>
					<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
					<p id="capital" class="invalid">An <b>uppercase</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="special" class="invalid"><b>Special Characters</b></p>
					<p id="length" class="invalid">Minimum of <b>8 characters</b></p>
				</div>
			</form><br>
		</div>
	</div><br><br>
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

		/*PASSWORD HANDLING*/
	var myInput = document.getElementById("npass");
	var letter = document.getElementById("letter");
	var capital = document.getElementById("capital");
	var number = document.getElementById("number");
	var special = document.getElementById("special");
	var length = document.getElementById("length");

	// When the user clicks outside of the password field, hide the message box
	myInput.onblur = function() {
	  document.getElementById("message").style.display = "none";
	}

	// When the user clicks on the password field, show the message box
	myInput.onfocus = function() {
	  document.getElementById("message").style.display = "block";
	}

	// When the user starts to type something inside the password field
	myInput.onkeyup = function() {

	  // Validate lowercase letters
	  var lowerCaseLetters = /[a-z]/g;
	  if(myInput.value.match(lowerCaseLetters)) {
	    letter.classList.remove("invalid");
	    letter.classList.add("valid");
	  } else {
	    letter.classList.remove("valid");
	    letter.classList.add("invalid");
	}

	  // Validate capital letters
	  var upperCaseLetters = /[A-Z]/g;
	  if(myInput.value.match(upperCaseLetters)) {
	    capital.classList.remove("invalid");
	    capital.classList.add("valid");
	  } else {
	    capital.classList.remove("valid");
	    capital.classList.add("invalid");
	  }

	  // Validate numbers
	  var numbers = /[0-9]/g;
	  if(myInput.value.match(numbers)) {
	    number.classList.remove("invalid");
	    number.classList.add("valid");
	  } else {
	    number.classList.remove("valid");
	    number.classList.add("invalid");
	  }

	  // Validate special characters
	  var specialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/g;
	  if(myInput.value.match(specialChars)) {
	    special.classList.remove("invalid");
	    special.classList.add("valid");
	  } else {
	    special.classList.remove("valid");
	    special.classList.add("invalid");
	  }

	  // Validate length
	  if(myInput.value.length >= 8) {
	    length.classList.remove("invalid");
	    length.classList.add("valid");
	  } else {
	    length.classList.remove("valid");
	    length.classList.add("invalid");
	  }
	}

</script>
</html>