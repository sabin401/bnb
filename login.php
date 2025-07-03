<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // plaintext for now

    $query = "SELECT * FROM customer WHERE email='$email' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['email'] = $email;
        header("Location: listbookings.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="POST" action="">
    Email: <input type="text" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
<p style="color:red;"><?php echo $error; ?></p>
</body>
</html>