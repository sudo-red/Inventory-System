<?php

@include 'config.php';

session_start();


// ------------------LOGIN FUNCTION------------------------//
//LOGIN
if(isset($_POST['login'])){

$email = mysqli_real_escape_string($conn, $_POST['email']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);
$pass = md5($_POST['password']);

$select = "SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";

$result = mysqli_query($conn, $select);

if(mysqli_num_rows($result) > 0){

	$row = mysqli_fetch_array($result);
	$_SESSION['name'] = $row['name'];
	$_SESSION['phone'] = $row['phone'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['role'] = $row['user_type'];
	$_SESSION['empID'] = $row['EmpID'];
	$ID = $row['BranchID'];

	$sql = mysqli_query($conn, "SELECT * FROM branches WHERE BranchID='$ID'");
	if($row = $sql->fetch_assoc()) {
		$_SESSION['Location'] = $row['Unit']. " " . $row['Street'] . ", " . $row['Province'];
		$_SESSION['BranchID'] = $row['BranchID'];
		$_SESSION['BranchName'] = $row['BranchName'];
	}
	if($_SESSION['role']=="owner") {
		header('location:summary.php');
	} else {
			header('location:dashboard.php');
	}
	} else{
		$error[] = 'incorrect email or password!';
	}
}


// ------------------REGISTER FUNCTION------------------------//
//REGISTER
if(isset($_POST['register'])){
	$error = array(); 	
	$user = $_POST['name'];
	$email = $_POST['email'];
	$pass = $_POST['password'];
	$cpass = $_POST['cpassword'];
	$user_type = $_POST['user_type'];
	$phone = $_POST['phone'];
	$branch = $_POST['branch'];
	$owner =  $_POST['owner'];
	$passowner = md5($_POST['owner']);
	$select = " SELECT * FROM user_form WHERE email = '$email' ";
	$result = mysqli_query($conn, $select);

	if(mysqli_num_rows($result) > 0){
	  $error[] = 'user already exists!';
	} 
	
	if($pass != $cpass){
	  $error[] = 'password does not match';
	}
	
	if (count($error) == 0) 
	{
		$select = " SELECT * FROM user_form WHERE user_type = 'owner' && password = '$passowner' ";
		$result = mysqli_query($conn, $select);

		if(mysqli_num_rows($result) > 0){
			$pass = md5($pass);
			$insert = "INSERT INTO user_form(name, email, password, phone, user_type, BranchID) VALUES('$user','$email','$pass','$phone','$user_type','$branch')";

			mysqli_query($conn, $insert);
			$_SESSION['success'] = "Account Created";
		} else {
			$error[]= "Owner password is incorrect";
		}
	}	
}



// ------------------SIDEMENU FUNCTIONS------------------------//
//SELECT BRANCH
if(isset($_POST['submit'])){
	
	$_SESSION['BranchID'] = $_POST['branch'];
	$ID = $_SESSION['BranchID'];

		$sql = mysqli_query($conn, "SELECT * FROM branches WHERE BranchID='$ID'");
   
		if($row = $sql->fetch_assoc())
		{
			$_SESSION['Location'] = $row['Unit']. " " . $row['Street'] . ", " . $row['Province'];
			$_SESSION['BranchID'] = $row['BranchID'];
			$_SESSION['BranchName'] = $row['BranchName'];
		}
		
	header('location:dashboard.php');
}


// ------------------DASHBOARD BUTTONS FUNCTION------------------------//
//CREATE PRODUCT
if(isset($_POST['create'])){

	$name = mysqli_real_escape_string($conn, $_POST['ProductName']);
	$supplier = $_POST['Supplier'];
	$acq = $_POST['DateAcquired'];
	$exp = $_POST['Expiry'];
	$start = $_POST['Starting'];
	$quantity = $_POST['Quantity'];
	$unit = $_POST['Unit'];
	$supplier = $_POST['Supplier'];
	$status = "active";
	$id = $_SESSION['BranchID'];
	
	
	if($exp < $acq) {
		$error[] = 'Product is already expired. Cannot be added. Try again';
	}
	else{
		mysqli_query($conn,"INSERT INTO product(product_name, date_acq, date_exp, start_amt, quantity, unit, status, BranchID, supplier) VALUES('$name','$acq','$exp','$start','$quantity','$unit', '$status', '$id', '$supplier')");
		
		$_SESSION['success'] = "New Product Created";
	}
}


//UPDATE PRODUCT
if(isset($_POST['update'])){

	$p_id = $_POST['ProductID'];
	$name = $_POST['ProductName'];
	$acq = $_POST['DateAcquired'];
	$exp = $_POST['Expiry'];
	$start = $_POST['Starting'];
	$quantity = $_POST['Quantity'];
	$unit = $_POST['Unit'];
	$supplier = $_POST['Supplier'];
	
	$sql = "UPDATE product SET 
	product_name = '$name', date_acq = '$acq',date_exp = '$exp', start_amt = '$start', quantity = '$quantity', unit = '$unit', supplier ='$supplier' WHERE ProdID ='$p_id' ";
		
	mysqli_query($conn, $sql);
		$count = $conn -> affected_rows;
	if($count > 0){
		$_SESSION['success'] = "Product Updated";
		
	} else{
		$error[] = 'Cannot Update Product';
	}
}

//ARCHIVE PRODUCT
if(isset($_POST['archive'])){

	$empID = $_SESSION['empID'];
	$id = $_POST['ProductID'];
	$pass = md5($_POST['password']);

	$select = "SELECT * FROM user_form WHERE EmpID = '$empID' && password = '$pass' ";
	$result = mysqli_query($conn, $select);
 
	if(mysqli_num_rows($result) > 0){
		date_default_timezone_set('Asia/Manila');
		$sql = "UPDATE product SET status='archived', date_archived=CURDATE() WHERE ProdID='".$id."'";
		mysqli_query($conn, $sql);
		
		$count = $conn -> affected_rows;
		if($count > 0){
			$_SESSION['success'] = "Product Archived";
			
		} else{
			$error[] = 'Cannot Archive Product';
		}
	} else {
			$error[] = 'Wrong Password';
	}
}

//UNARCHIVE PRODUCT
if(isset($_POST['unarchive'])){

	$empID = $_SESSION['empID'];
	$id = $_POST['ProductID'];
	$pass = md5($_POST['password']);
	
	$select = "SELECT * FROM user_form WHERE EmpID = '$empID' && password = '$pass' ";
	$result = mysqli_query($conn, $select);
 
	if(mysqli_num_rows($result) > 0){
		date_default_timezone_set('Asia/Manila');
		$sql = "UPDATE product SET status='active' WHERE ProdID='".$id."'";
		mysqli_query($conn, $sql);
		
		$count = $conn -> affected_rows;
		if($count > 0){
			$_SESSION['success'] = "Product Unarchived";
			
		} else{
			$error[] = 'Cannot Archive Product';
		}
	} else {
			$error[] = 'Wrong Password';
	}
}
// ------------------UPDATE PROFILE PAGE FUNCTIONS------------------------//

// Username
if(isset($_POST['upuser'])){
	
	$old = $_SESSION['name'];
	$name = $_POST['username'];
	$pass = md5($_POST['password']);
	
	if($name == $old){
		$error[] = 'New username cannot be old username';
	}else{
		$insert = "UPDATE user_form SET name='$name' WHERE name ='$old' && password ='$pass' ";
		mysqli_query($conn, $insert);
		$count = $conn -> affected_rows;
		if($count > 0){
			$_SESSION['success'] = "Username Updated";
			$_SESSION['name'] = $name;
		} else{
			$error[] = 'Incorrect Password. Try Again';
		}
	}
}

// E-mail
if(isset($_POST['upemail'])){
	$old = $_SESSION['email'];
	$email = $_POST['email'];
	$pass = md5($_POST['password']);
	
	if($old == $email){
		$error[] = 'New email cannot be old email';
	}else{
		$insert = "UPDATE user_form SET email='$email' WHERE email ='$old' && password ='$pass' ";
		mysqli_query($conn, $insert);
		$count = $conn -> affected_rows;
		if($count > 0){
			$_SESSION['success'] = "Email Updated";
			$_SESSION['email'] = $email;
		} else{
			$error[] = 'Incorrect Password. Try Again';
		}
	}
}

// Phone
if(isset($_POST['uphone'])){
	$old = $_SESSION['phone'];
	$phone = $_POST['phone'];
	$pass = md5($_POST['password']);
	
	if($phone == $old){
		$error[] = 'New phone number cannot be old number';
	} else{
		$insert = "UPDATE user_form SET phone='$phone' WHERE phone ='$old' && password ='$pass' ";
		mysqli_query($conn, $insert);
		$count = $conn -> affected_rows;
		if($count > 0){
			$_SESSION['success'] = "Phone Number Updated";
			$_SESSION['phone'] = $phone;
			
		} else{
			$error[] = 'Incorrect Password. Try Again';
		}
	}
}

//Password
if(isset($_POST['upass'])) {
	$error = array(); 
	$password = $_POST['password'];
	$newpassword =  $_POST['newpassword'];
	$npassword = $_POST['npassword'];
	$number = preg_match('@[0-9]@', $newpassword);
	$uppercase = preg_match('@[A-Z]@', $newpassword);
	$lowercase = preg_match('@[a-z]@', $newpassword);
	$specialChars = preg_match('@[^\w]@', $newpassword);
	
	if($password == $newpassword){
		$error[] = 'New password cannot be old password';
	}  else {
		if($newpassword != $npassword) {
			$error[] = 'New password does not match!';
		} else {
			if(strlen($newpassword) < 8) {
				$error[] = "New password must be at least 8 characters in length";
			} 
			if(!$number) {
				$error[] = "New password must contain at least one number";
			}
			if(!$uppercase) {
				$error[] = "New password must contain at least one upper case letter";
			} 
			if(!$lowercase) {
				$error[] = "New password must contain at least one lower case letter";
			} 
			if(!$specialChars) {
				$error[] = "New password must contain at least one special character";
			} 
		}
	}
	
	$pass = md5($_POST['password']);
	$newpass = md5($_POST['newpassword']);
	$npass = md5($_POST['npassword']);
	
	if (count($error) == 0) 
	{
		$insert = "UPDATE user_form SET password='$newpass' WHERE password ='$pass' ";
		mysqli_query($conn, $insert);
		$count = $conn -> affected_rows;
		if($count > 0){
			$_SESSION['success'] = "Password Updated";
			
		} else{
			$error[] = 'Incorrect Password. Try Again';
		}
	}
}


// ------------------CREATE BRANCH PAGE FUNCTIONS------------------------//

if(isset($_POST['cbranch'])){
	$owner = $_SESSION['name'];
	$name = $_POST['name'];
	$prov = $_POST['province'];
	$str = $_POST['street'];
	$unit = $_POST['unit'];
	$pass = md5($_POST['password']);
	
	$select = "SELECT * FROM user_form WHERE name = '$owner' && password = '$pass' ";
	$result = mysqli_query($conn, $select);			
	
	if(mysqli_num_rows($result) > 0) {
		$query = mysqli_query($conn,"INSERT INTO branches(BranchName, Province, Street, Unit) VALUES('$name','$prov','$str','$unit')");
		
		if($query)
		{
			$_SESSION['success'] = "New Branch Created";
		} else {
			$error[] = 'Cannot Create New Branch';
		}
	} else {
		$error[] = 'Incorrect password!';
	}
}
?>