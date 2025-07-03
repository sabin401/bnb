<!DOCTYPE HTML>
<html>
<head>
<title>Edit a booking</title> 
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
    $(document).ready(function() {
    $("#firstname").change(function(){
    
    });
});

    $( function() {
    $("#datepicker").datepicker();
});

    $( function() {
    $("#mydatepicker").datepicker();
});
</script>
</head>
 <body>

<h1>Edit a booking</h1>

<h2><a href='currentbookings.php'>[Return to the Bookings listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>
<h2>Booking made for Test</h2>
<form method="POST" action="registercustomer.php">
  <p>
    <label for="firstname">Room (name,type,beds): </label>
    <select id = "firstname" style=width:150px>
    <option value = "1"> Kellie, S, 5 </option>
    <option value = "2"> Herman, D, 2 </option>
    </select>
</p> 
    
  <p>
    <label for="Checkin date">Checkin date:</label>
    <input type="text" id="datepicker" placeholder="yyyy-mm-dd" required>
  </p>  
  <p>  
    <label for="Checkout date">Checkout date: </label>
    <input type="text" id="mydatepicker" placeholder="yyyy-mm-dd"  required> 
   </p>
  <p>
    <label for="Contact number">Contact number: </label>
    <input type="tel" pattern="[\(]\d{3}[\)]\d{3} \d{4}" placeholder="(# # #)  # # #  # # # #" minlength="8" maxlength="32" required> 
  </p> 
  <p>   
    <label for="Booking extras:">Bookings extras: </label>
    <input type="text"   style="height:100px; width:300px" required>
   </p>
   <p>   
    <label for="Room Review:">Room Review: </label>
    <input type="text"  style="height:100px; width:300px" required>
   </p>
  <p>
   <input type="submit" name="add" value="Update">
   <a href="currentbookings.php">[Cancel]</a>
  </p>
 </form>



</body>
</html>