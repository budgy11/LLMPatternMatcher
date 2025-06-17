
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      // Get the value from the search input
      var searchTerm = document.getElementById("searchInput").value;

      //  Basic Search (Replace with your actual search logic)
      if (searchTerm.trim() === "") {
        return; // Do nothing if the input is empty
      }

      //  Example: Display the search term in an alert
      alert("You searched for: " + searchTerm);

      //  Replace this with your actual search implementation.
      //  For example, you could:
      //  1.  Send the searchTerm to a PHP script for processing.
      //  2.  Filter a database or array based on the searchTerm.
      //  3.  Display the results on the page.
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established and named $conn

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the search term from the POST request
  $searchTerm = $_POST['search_term'];

  // Sanitize the search term (very important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm)); //Remove whitespace and convert to HTML entities

  //  Database Query (Example using MySQL)
  $sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";  //Adjust table and column names as needed
  $result = mysqli_query($conn, $sql);

  // Display the results (Adjust this part based on how you want to display the search results)
  if (mysqli_num_rows($result) > 0) {
    echo "<h3>Search Results:</h3>";
    echo "<form method='post' action=''>"; //Form for easy re-searching
    echo "<input type='text' name='search_term' value='" . $searchTerm . "' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>Product Name: " . $row['name'] . " - Description: " . $row['description'] . "</li>";
    }
    echo "</ul>";

  } else {
    echo "<p>No results found for '" . $searchTerm . "'</p>";
  }
} else {
  // If the request method is not POST, display the search form
  echo "<h3>Search Products:</h3>";
  echo "<form method='post' action=''>";
  echo "<input type='text' name='search_term' placeholder='Enter search term'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}

?>
