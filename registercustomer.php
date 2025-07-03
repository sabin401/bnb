<!DOCTYPE HTML>
<html>
<head>
  <title>Register new customer</title>
</head>
<body>

<?php
// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Clean input function
function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Register') {
    include "config.php";
    $DBC = mysqli_connect("127.0.0.1:3306", DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit;
    }

    $error = 0;
    $msg = 'Error: ';

    // Validate firstname
    if (isset($_POST['firstname']) && !empty($_POST['firstname']) && is_string($_POST['firstname'])) {
        $fn = cleanInput($_POST['firstname']);
        $firstname = (strlen($fn) > 50) ? substr($fn, 0, 50) : $fn;
    } else {
        $error++;
        $msg .= 'Invalid firstname ';
        $firstname = '';
    }

    // Lastname
    $lastname = cleanInput($_POST['lastname']);

    // Email
    $email = cleanInput($_POST['email']);

    // Password
    $password = cleanInput($_POST['password']);

    if ($error == 0) {
        $query = "INSERT INTO customer (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($DBC, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $firstname, $lastname, $email, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($DBC);

        // Redirect to customer listing after successful registration
        header("Location: listcustomers.php");
        exit;
    } else {
        echo "<h2>$msg</h2>";
        mysqli_close($DBC);
    }
}
?>

<h1>New Customer Registration</h1>
<h2>
  <a href='listcustomers.php'>[Return to Customer listing]</a>
  <a href='/bnb/'>[Return to Main Page]</a>
</h2>

<form method="POST" action="registercustomer.php">
  <p>
    <label for="firstname">First Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="2" maxlength="50" required>
  </p>
  <p>
    <label for="lastname">Last Name: </label>
    <input type="text" id="lastname" name="lastname" minlength="2" maxlength="50" required>
  </p>
  <p>
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100" size="50" required>
  </p>
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" minlength="8" maxlength="32" required>
  </p>

  <input type="submit" name="submit" value="Register">
</form>

</body>
</html>
