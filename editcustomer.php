<!DOCTYPE HTML>
<html>
<head>
    <title>Edit Customer</title>
</head>
<body>

<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php"; // Load DB credentials
$DBC = mysqli_connect("127.0.0.1:3306", DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}

// Clean input function
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get ID from URL (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (empty($id) || !is_numeric($id)) {
        echo "<h2>Invalid Customer ID</h2>";
        exit;
    }
}

// Handle form submission (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $_POST['submit'] == 'Update') {
    $error = 0;
    $msg = 'Error: ';

    if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
        $id = cleanInput($_POST['id']);
    } else {
        $error++;
        $msg .= 'Invalid Customer ID ';
        $id = 0;
    }

    // Collect and clean form data
    $firstname = cleanInput($_POST['firstname']);
    $lastname = cleanInput($_POST['lastname']);
    $email = cleanInput($_POST['email']);

    if ($error == 0 && $id > 0) {
        $query = "UPDATE customer SET firstname = ?, lastname = ?, email = ? WHERE customerID = ?";
        $stmt = mysqli_prepare($DBC, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $firstname, $lastname, $email, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<h2>Customer details updated.</h2>";

        // Optional redirect to list
        // header('Location: listcustomers.php', true, 303);
        // exit;
    } else {
        echo "<h2>$msg</h2>";
    }
}

// Load existing customer info
if (!isset($id)) {
    echo "<h2>No customer ID provided.</h2>";
    exit;
}

$query = "SELECT customerID, firstname, lastname, email FROM customer WHERE customerID = $id";
$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    $row = mysqli_fetch_assoc($result);
?>

<h1>Customer Details Update</h1>
<h2>
    <a href='listcustomers.php'>[Return to Customer Listing]</a>
    <a href='/bnb/'>[Return to Main Page]</a>
</h2>

<form method="POST" action="editcustomer.php">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <p>
        <label for="firstname">First Name: </label>
        <input type="text" id="firstname" name="firstname" minlength="2" maxlength="50" required value="<?php echo htmlspecialchars($row['firstname']); ?>">
    </p>

    <p>
        <label for="lastname">Last Name: </label>
        <input type="text" id="lastname" name="lastname" minlength="2" maxlength="50" required value="<?php echo htmlspecialchars($row['lastname']); ?>">
    </p>

    <p>
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" maxlength="100" size="50" required value="<?php echo htmlspecialchars($row['email']); ?>">
    </p>

    <input type="submit" name="submit" value="Update">
</form>

<?php
} else {
    echo "<h2>Customer not found with that ID</h2>";
}

mysqli_close($DBC);
?>

</body>
</html>
