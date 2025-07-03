<!DOCTYPE HTML>
<html>
<head>
    <title>Delete Bookings</title>
</head>
<body>
    <h1>Booking preview before deletion</h1>
    <h2><a href='currentbookings.php'>[Return to the booking listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>

    
    
    <fieldset>
        <legend>Room detail #2</legend>
        <dl>
            <dt>Room name:</dt>
            <dd>Kellie</dd>

            <dt>Checkin date:</dt>
            <dd>2018-09-15</dd>

            <dt>Checkout date::</dt>
            <dd>2018-09-19</dd>

        </dl>
    </fieldset>

    <form method="POST" action="deleteroom.php">
        <h2>Are you sure you want to delete this Room?</h2>
        <input type="hidden" name="id" value="1">
        <input type="submit" name="submit" value="Delete">
        <a href="currentbookings.php">[Cancel]</a>
    </form>

   
</body>
</html>