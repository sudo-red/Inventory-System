<?php include('functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Profile</title>
	<!-- add icon link -->
    <link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/updateprofile.css?v=<?php echo time(); ?>">
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
	<h2 style="text-align: center;"><b>UPDATE PROFILE</b></h2>

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
	
	<!---MAIN CONTAINER--->
	<div class="container">
		
			<!---USER NAME--->		
			<p><b>Username:  </b><?php echo $_SESSION['name'];?></p>
			<button id="UsernameBtn" class="ModalBtn">Update Username</button><br> <br>
			
			<div id="Username" class="InfoModal">
				<div class="InfoModal-content">
					<div class="InfoModal-header">
						<button class="username-close">x</button> 
						<h2>Update Username</h2>
					</div>
					<div class="InfoModal-body">
						<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
						<form method="post">
							<input type="text" name="username" required placeholder="Enter New Username*"><br>
							<input type="password" name="password" required placeholder="Enter Password*"><br><br>
							<input type="submit" name="upuser">
						</form>
					</div>
				</div>
			</div>

			<!---EMAIL--->	
			<p><b>Email:  </b><?php echo $_SESSION['email'];?></p>
			<button id="EmailBtn" class="ModalBtn">Update Email</button> <br><br>
			<div id="Email" class="InfoModal">
				<div class="InfoModal-content">
					<div class="InfoModal-header">
						<button class="email-close">x</button> 
						<h2>Update Email</h2>
					</div>
					<div class="InfoModal-body">
						<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
						<form method="post">
							<input type="email" name="email" required placeholder="Enter New Email Address*"><br>
							<input type="password" name="password" required placeholder="Enter Password*"><br><br>
							<input type="submit" name="upemail">
						</form>
					</div>
				</div>
			</div>

			<!---PHONE--->	
			<p><b>Phone:     </b><?php echo $_SESSION['phone'];?></p>
			<button id="PhoneBtn" class="ModalBtn">Update Phone</button> <br><br>
			<div id="Phone" class="InfoModal">
				<div class="InfoModal-content">
					<div class="InfoModal-header">
						<button class="phone-close">x</button> 
						<h2>Update Phone</h2>
					</div>
					<div class="InfoModal-body">
						<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
						<form method="post">
							<input type="text" name="phone" required placeholder="Enter New Phone Number*"><br>
							<input type="password" name="password" required placeholder="Enter Password*"><br><br>
							<input type="Submit" name="uphone">
						</form>
					</div>
				</div>
			</div>

			<!---PASSWORD--->	
			<p><b>Password:</b></p>
			<button id="PassBtn" class="ModalBtn">Update Password</button>
			<div id="Pass" class="InfoModal">
				<div class="InfoModal-content">
					<div class="InfoModal-header">
						<button class="pass-close">x</button> 
						<h2>Update Password</h2>
					</div>
					<div class="InfoModal-body">
						<p style="font-size: 12px;"><b style="color:red;">*</b> required fields</p>
						<form method="post">
							<input type="password" name="password" required placeholder="Enter Old Password*" ><br>
							<input type="password" name="newpassword" id="npass" required placeholder="Enter New Password*" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z].{8,})"><br>
							<input type="password" name="npassword" required placeholder="Confirm New Password*"><br><br>
							<input type="Submit" name="upass">

							<!--PASSWORD HANDLING-->
							<div id="message">
								<p style="padding-block: 10px; margin-left: 0px;">Password must contain the following:</p>
								<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
								<p id="capital" class="invalid">An <b>uppercase</b> letter</p>
								<p id="number" class="invalid">A <b>number</b></p>
								<p id="special" class="invalid"><b>Special Characters</b></p>
								<p id="length" class="invalid">Minimum of <b>8 characters</b></p>
							</div>

						</form>
					</div>
				</div>
			</div>
		<br>
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

	/*USERNAME MODAL*/
	var username_modal = document.getElementById("Username");
	var username_btn = document.getElementById("UsernameBtn");
	var username_close = document.getElementsByClassName("username-close")[0];

	username_btn.onclick = function() {
  		username_modal.style.display = "block"; }
  	username_close.onclick = function() {
  		username_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == username_modal) {
    		username_modal.style.display = "none";
  		}
	}

	/*EMAIL MODAL*/
	var email_modal = document.getElementById("Email");
	var email_btn = document.getElementById("EmailBtn");
	var email_close = document.getElementsByClassName("email-close")[0];

	email_btn.onclick = function() {
  		email_modal.style.display = "block"; }
  	email_close.onclick = function() {
  		email_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == email_modal) {
    		email_modal.style.display = "none";
  		}
	}

	/*PHONE MODAL*/
	var phone_modal = document.getElementById("Phone");
	var phone_btn = document.getElementById("PhoneBtn");
	var phone_close = document.getElementsByClassName("phone-close")[0];

	phone_btn.onclick = function() {
  		phone_modal.style.display = "block"; }
  	phone_close.onclick = function() {
  		phone_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == phone_modal) {
    		phone_modal.style.display = "none";
  		}
	}

	/*PASSWORD MODAL*/
	var pass_modal = document.getElementById("Pass");
	var pass_btn = document.getElementById("PassBtn");
	var pass_close = document.getElementsByClassName("pass-close")[0];

	pass_btn.onclick = function() {
  		pass_modal.style.display = "block"; }
  	pass_close.onclick = function() {
  		pass_modal.style.display = "none"; }
  	window.onclick = function(event) {
  		if (event.target == pass_modal) {
    		pass_modal.style.display = "none";
  		}
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
