<?php
session_start();

function checkUser() {
    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit;
    }
}

function loginStatus() {
    if (isset($_SESSION['username'])) {
        echo "<p>Welcome, " . htmlspecialchars($_SESSION['username']) . " | <a href='logout.php'>Logout</a></p>";
    } else {
        echo "<p><a href='login.php'>Login</a></p>";
    }
}
?>
