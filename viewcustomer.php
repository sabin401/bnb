<!DOCTYPE HTML>
<html>
<head>
    <title>View Customer</title> 
</head>
<body>

<?php
// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php"; // Load DB credentials

// Connect to database
$DBC = mysqli_connect("127.0.0.1:3306", DBUSER, DBPASSWORD, DBDATABASE);

// Check connection
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}

// Validate customer ID from GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h2>Invalid customer ID</h2>";
    exit;
}

$id = (int)$_GET['id'];

// Use prepared statement
$query = "SELECT * FROM customer WHERE customerID = ?";
$stmt = mysqli_prepare($DBC, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h1>Customer Details View</h1>
<h2>
    <a href='listcustomers.php'>[Return to Customer Listing]</a>
    <a href='/bnb/'>[Return to Main Page]</a>
</h2>

<?php
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "<fieldset><legend>Customer Detail #$id</legend><dl>";
    echo "<dt>Firstname:</dt><dd>" . htmlspecialchars($row['firstname']) . "</dd>";
    echo "<dt>Lastname:</dt><dd>" . htmlspecialchars($row['lastname']) . "</dd>";
    echo "<dt>Email:</dt><dd>" . htmlspecialchars($row['email']) . "</dd>";
    echo "<dt>Password:</dt><dd>" . htmlspecialchars($row['password']) . "</dd>";
    echo "</dl></fieldset>";
} else {
    echo "<h2>No customer found!</h2>";
}

// Close resources
mysqli_free_result($result);
mysqli_close($DBC);
?>

</body>
</html>
