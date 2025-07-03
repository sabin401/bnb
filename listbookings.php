<!DOCTYPE HTML>
<html>
<head>
<title>Make a booking</title> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$(function() {
    $("#datepicker").datepicker({ dateFormat: "yy-mm-dd" });
    $("#mydatepicker").datepicker({ dateFormat: "yy-mm-dd" });
    $("#start_date").datepicker({ dateFormat: "yy-mm-dd" });
    $("#end_date").datepicker({ dateFormat: "yy-mm-dd" });
});
</script>
</head>
<body>

<h1>Make a booking</h1>

<h2>
  <a href='currentbookings.php'>[Return to the Bookings listing]</a>
  <a href='/bnb/'>[Return to the main page]</a>
</h2>
<h2>Booking for Test</h2>

<form method="POST" action="currentbookings.php">
  <p>
    <label for="firstname">Room (name,type,beds): </label>
    <select id="firstname" name="room_id" style="width:150px;">
      <option value="1">Kellie, S, 5</option>
      <option value="2">Herman, D, 2</option>
    </select>
  </p> 
    
  <p>
    <label for="Checkin date">Checkin date:</label>
    <input type="text" id="datepicker" name="checkin_date" placeholder="yyyy-mm-dd" required>
  </p>  

  <p>  
    <label for="Checkout date">Checkout date: </label>
    <input type="text" id="mydatepicker" name="checkout_date" placeholder="yyyy-mm-dd" required> 
  </p>

  <p>
    <label for="Contact number">Contact number: </label>
    <input type="tel" name="contact_number" pattern="^\(\d{3}\) \d{3} \d{4}$" 
           placeholder="(###) ### ####" minlength="14" maxlength="14" required> 
  </p> 

  <p>   
    <label for="Booking extras:">Bookings extras: </label>
    <input type="text" name="booking_extras" style="height:100px; width:300px" required>
  </p>

  <p>
    <input type="submit" name="add" value="Add">
    <a href="currentbookings.php">[Cancel]</a>
  </p>
</form>

<hr>

<h2>Search for room availability</h2>

<form method="get" action="listbookings.php">
  <label>Start date:
    <input type="text" id="start_date" name="start_date" value="<?php echo $_GET['start_date'] ?? ''; ?>" required>
  </label>
  <label>End date:
    <input type="text" id="end_date" name="end_date" value="<?php echo $_GET['end_date'] ?? ''; ?>" required>
  </label>
  <input type="submit" name="search" value="Search availability">
</form>

<?php
if (isset($_GET['search'])) {
    include('dbconnect.php'); // adjust path if needed

    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    if (!$start_date || !$end_date) {
        echo "<p>Please select both start and end dates.</p>";
    } else {
        // Query to get available rooms (not booked in given range)
        $sql = "
            SELECT * FROM room
            WHERE roomID NOT IN (
                SELECT roomID FROM booking
                WHERE (
                    (checkInDate <= :end AND checkOutDate >= :start)
                )
            )
            ORDER BY roomID ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':start' => $start_date, ':end' => $end_date]);
        $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Room #</th><th>Roomname</th><th>Room Type</th><th>Beds</th></tr>";

        if (count($availableRooms) > 0) {
            foreach ($availableRooms as $room) {
                echo "<tr>";
                echo "<td>{$room['roomID']}</td>";
                echo "<td>{$room['roomname']}</td>";
                echo "<td>{$room['roomtype']}</td>";
                echo "<td>{$room['beds']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No rooms available for the selected dates.</td></tr>";
        }
        echo "</table>";
    }
}
?>

</body>
</html>
