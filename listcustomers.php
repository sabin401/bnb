<!DOCTYPE HTML>
<html>
<head>
  <title>Browse customers with AJAX autocomplete</title>
  <script>
    function searchResult(searchstr) {
      if (searchstr.length == 0) {
        return;
      }

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          // Convert JSON response to JavaScript array
          var mbrs = JSON.parse(this.responseText);
          var tbl = document.getElementById("tblcustomers");

          // Clear previous results (keep header row)
          var rowCount = tbl.rows.length;
          for (var i = rowCount - 1; i > 0; i--) {
            tbl.deleteRow(i);
          }

          // Populate table with customer data
          for (var i = 0; i < mbrs.length; i++) {
            var mbrid = mbrs[i]['customerID'];
            var fn = mbrs[i]['firstname'];
            var ln = mbrs[i]['lastname'];

            var urls = '<a href="viewcustomer.php?id=' + mbrid + '">[view]</a> ';
            urls += '<a href="editcustomer.php?id=' + mbrid + '">[edit]</a> ';
            urls += '<a href="deletecustomer.php?id=' + mbrid + '">[delete]</a>';

            var tr = tbl.insertRow(-1);

            // LASTNAME column first (match table header)
            var tabCell = tr.insertCell(-1);
            tabCell.innerHTML = ln;

            // FIRSTNAME column second
            tabCell = tr.insertCell(-1);
            tabCell.innerHTML = fn;

            // ACTIONS column third
            tabCell = tr.insertCell(-1);
            tabCell.innerHTML = urls;
          }
        }
      }

      // Call PHP backend
      xmlhttp.open("GET", "customersearch.php?sq=" + searchstr, true);
      xmlhttp.send();
    }
  </script>
</head>
<body>

<h1>Customer List Search by Lastname</h1>
<h2>
  <a href='registercustomer.php'>[Create new Customer]</a>
  <a href="/bnb/">[Return to main page]</a>
</h2>

<form>
  <label for="lastname">Lastname: </label>
  <input id="lastname" type="text" size="30"
         onkeyup="searchResult(this.value)"
         onclick="this.value='';"
         placeholder="Start typing a last name">
</form>

<table id="tblcustomers" border="1">
  <thead>
    <tr>
      <th>Lastname</th>
      <th>Firstname</th>
      <th>actions</th>
    </tr>
  </thead>
</table>

</body>
</html>
