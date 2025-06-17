

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 0 auto; /* Center the search bar */
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box; /* Important for consistent width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchFunction()">
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var input, filter, ul, li, r, i;
      input = document.getElementById("searchInput");
      filter = input.value.toLowerCase();
      ul = document.getElementById("searchResults");
      li = ul.getElementsByTagName("li");

      // Clear previous results
      for (i = 0; i < ul.children.length; i++) {
        ul.removeChild(ul.children[i]);
      }

      // Loop through each list item and check if it matches the search term
      for (i = 0; i < ul.children.length; i++) {
        r = ul.children[i].innerHTML.toLowerCase();
        if (r != "") { // Avoid showing empty results
          ul.innerHTML += '<li class="result">'+r+'</li>';
        }
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established ($db)

// Replace this with your actual query
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchInput->value . "%'";
$result = mysqli_query($db, $query); // Or your database query function

// ... (rest of the code - display results in list form)
?>
