<?php
include('config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the ID and quantity from the AJAX request
$id = $_POST["id"];
$quantity = $_POST["quantity"];

// Update the value in the database
$sql = "UPDATE product SET quantity = $quantity WHERE ProdID = $id";
if ($conn->query($sql) === TRUE) {
    echo json_encode("Counter updated successfully!");
} else {
    echo json_encode("Error updating counter: " . $conn->error);
}

// Close the connection
$conn->close();
?>
