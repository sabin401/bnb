<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1:3306", DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
  echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
  exit; //stop processing the page further
}

//function to clean input but not validate type and content
function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

$error = 0;
$msg = '';

//retrieve the roomid from the URL (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "<h2>Invalid room ID</h2>"; //simple error feedback
        exit;
    }
}

//check if we are saving data (POST)
if (isset($_POST['submit']) && !empty($_POST['submit']) && ($_POST['submit'] == 'Update')) {

    if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
       $id = cleanInput($_POST['id']);
    } else {
       $error++;
       $msg .= 'Invalid room ID ';
       $id = 0;
    }

    $roomname = cleanInput($_POST['roomname']);
    $description = cleanInput($_POST['description']);
    $roomtype = cleanInput($_POST['roomtype']);
    $beds = cleanInput($_POST['beds']);

    // Validate beds is integer between 1 and 5
    if (!is_numeric($beds) || intval($beds) < 1 || intval($beds) > 5) {
        $error++;
        $msg .= 'Beds must be a number between 1 and 5 ';
    }

    if ($error == 0 && $id > 0) {
        $query = "UPDATE room SET roomname=?, description=?, roomtype=?, beds=? WHERE roomID=?";
        $stmt = mysqli_prepare($DBC, $query);
        if ($stmt === false) {
            echo "<h2>Prepare failed: " . htmlspecialchars(mysqli_error($DBC)) . "</h2>";
            exit;
        }
        
        $beds_int = intval($beds);
        $id_int = intval($id);

        mysqli_stmt_bind_param($stmt, 'sssii', $roomname, $description, $roomtype, $beds_int, $id_int);
        
        $execute_result = mysqli_stmt_execute($stmt);
        if (!$execute_result) {
            echo "<h2>Execute failed: " . htmlspecialchars(mysqli_stmt_error($stmt)) . "</h2>";
            exit;
        }
        mysqli_stmt_close($stmt);

        // Redirect after successful update
        header('Location: listrooms.php');
        exit;
    } else {
        echo "<h2>$msg</h2>";
    }
}

// Fetch current room data for the form
$query = 'SELECT roomID, roomname, description, roomtype, beds FROM room WHERE roomID=' . intval($id);
$result = mysqli_query($DBC, $query);
if (!$result) {
    echo "<h2>Query failed: " . htmlspecialchars(mysqli_error($DBC)) . "</h2>";
    exit;
}
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE HTML>
<html>
<head><title>Edit a room</title></head>
<body>

<h1>Room Details Update</h1>
<h2><a href='listrooms.php'>[Return to the room listing]</a> <a href='/bnb/'>[Return to the main page]</a></h2>

<form method="POST" action="editroom.php">
  <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
  <p>
    <label for="roomname">Room name: </label>
    <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" value="<?php echo htmlspecialchars($row['roomname']); ?>" required>
  </p>
  <p>
    <label for="description">Description: </label>
    <input type="text" id="description" name="description" size="100" minlength="5" maxlength="200" value="<?php echo htmlspecialchars($row['description']); ?>" required>
  </p>
  <p>
    <label for="roomtype">Room type: </label>
    <input type="radio" id="roomtype_s" name="roomtype" value="S" <?php echo ($row['roomtype']=='S') ? 'checked' : ''; ?>> Single
    <input type="radio" id="roomtype_d" name="roomtype" value="D" <?php echo ($row['roomtype']=='D') ? 'checked' : ''; ?>> Double
  </p>
  <p>
    <label for="beds">Beds (1-5): </label>
    <input type="number" id="beds" name="beds" min="1" max="5" value="<?php echo intval($row['beds']); ?>" required>
  </p>
  <input type="submit" name="submit" value="Update">
</form>

</body>
</html>

<?php 
} else {
  echo "<h2>Room not found with that ID</h2>";
}
mysqli_free_result($result);
mysqli_close($DBC);
?>
