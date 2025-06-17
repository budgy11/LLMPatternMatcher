
<form action="" method="get">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
</form>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px; /* Adjust width as needed */
      font-size: 16px;
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50; /* Green */
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
    <h2>Search</h2>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      // Get the search term from the input field
      var searchTerm = document.getElementById("searchInput").value;

      //  Example:  Replace this with your actual search logic.
      //  This example just displays the search term in an alert.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      alert("Searching for: " + searchTerm);

      //  **Important:  Replace this placeholder with your actual search code.**
      //  You'll likely want to:
      //  1. Send the 'searchTerm' to a server-side script (e.g., a PHP file).
      //  2. Receive the results from the server.
      //  3. Display the results on the page.
    }
  </script>

</body>
</html>


<?php
  // Assuming you have a connection to your database
  $searchTerm = $_POST['searchTerm'];  // Get the search term from the form

  // Check if the search term is empty
  if (empty($searchTerm)) {
    echo json_encode(array('results' => ''));  // Return an empty result
    exit;
  }

  // Example SQL query (adapt to your database schema)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  $result = mysqli_query($conn, $sql); // Replace $conn with your database connection

  $results = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
  }

  mysqli_close($conn);

  // Convert the results to JSON for easy use in JavaScript
  echo json_encode($results);
?>
