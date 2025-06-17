
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
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
      padding: 10px 20px;
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
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here.  This is just a placeholder example.
      // You'd replace this with your actual search code (e.g., searching a database,
      // an array, etc.)

      if (searchTerm) {
        // Example: Display the search term in an alert
        alert("You searched for: " + searchTerm);

        // Example:  Simulated search results (replace with your actual data)
        var results = ["Result 1 for " + searchTerm, "Result 2 for " + searchTerm, "Result 3 for " + searchTerm];

        // Display the results in an alert (for demo purposes)
        for (var i = 0; i < results.length; i++) {
          alert(results[i]);
        }
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established in your PHP code
$searchTerm = $_GET['search']; // Get the search term from the URL

if ($searchTerm) {
    $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Process the search results here
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Product Name: " . $row['name'] . "<br>";
            echo "Price: " . $row['price'] . "<br>";
            echo "<br>";
        }
        mysqli_free_result($result); // Free the result set
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    echo "Please enter a search term.";
}
?>
