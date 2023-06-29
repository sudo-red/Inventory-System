<?php
// Connect to the database
include('config.php');

// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}


// Loop through the form data and update the database for each element
for ($i = 0; $i < count($_POST['id']); $i++) {
	$id = mysqli_real_escape_string($conn, $_POST['id'][$i]);
	$need = mysqli_real_escape_string($conn, $_POST['need'][$i]);

	$sql = "UPDATE product SET amt_need='$need' WHERE ProdID='$id'";
	mysqli_query($conn, $sql);
}

// Close connection
mysqli_close($conn);
function_alert("Amount Needed Updated");
function function_alert($msg) {
	echo "<script type='text/javascript'>alert('$msg');window.location.href='dashboard.php';</script>";
}


?>