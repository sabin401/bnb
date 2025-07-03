<?php
include('dbconnect.php'); // make sure this file exists and works

$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$guests = $_GET['guests'] ?? '';

if (!$checkin || !$checkout || !$guests) {
    echo "All fields are required.";
    exit;
}

try {
    // Get all available rooms (not booked between checkin and checkout)
    $sql = "
        SELECT * FROM room
        WHERE roomID NOT IN (
            SELECT roomID FROM booking
            WHERE (
                (checkInDate <= :checkout AND checkOutDate >= :checkin)
            )
        )
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':checkin' => $checkin, ':checkout' => $checkout]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rooms) > 0) {
        echo "<h3>Available Rooms:</h3><ul>";
        foreach ($rooms as $room) {
            echo "<li><strong>{$room['roomname']}</strong> - Beds: {$room['beds']} | Type: {$room['roomtype']}</li>";
        }
        echo "</ul>";
    } else {
        echo "No rooms available for selected dates.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
