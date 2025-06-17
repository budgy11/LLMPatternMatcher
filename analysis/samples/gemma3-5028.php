

// Using prepared statements (more secure)
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
$stmt->bind_param("s", "%" . $searchTerm . "%");  // "s" indicates a string
$stmt->execute();
$result = $stmt->get_result();
// ... process $result ...
$stmt->close();


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 90%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
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

      //  Replace this with your actual search logic.
      //  This is just a placeholder to demonstrate the concept.
      //  You'll likely want to fetch data from a database or array.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      //  Example:  If you have an array of data called 'data'
      //  you could do something like this:
      //  var results = data.filter(function(item) {
      //    return item.name.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1;
      //  });

      //  For demonstration, let's just display the search term in an alert.
      alert("Searching for: " + searchTerm);
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form (using GET or POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm); //Sanitize for security

// Optional: Sanitize the search term further to prevent injection attempts.
// You can add more specific validation based on your database structure.

//  Example:  Using a whitelist of allowed characters.
//  $allowedChars = ['a-z', 'A-Z', '0-9', '-'];
//  $searchTerm = preg_replace('/[^' . implode('|', $allowedChars) . ']/', '', $searchTerm);


// Perform the search in your database
//  This is a placeholder; replace with your actual database query logic
$results = searchDatabase($searchTerm);

// Function to simulate a database search
function searchDatabase($searchTerm) {
    // This is a placeholder - replace with your actual database query
    $filteredResults = [];
    $searchTerm = htmlspecialchars($searchTerm); // Sanitize again here for the function

    // Example: Simple string search (replace with your query)
    $query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";

    // Simulate fetching results (replace with your database query execution)
    $results = [];
    if ($searchTerm) {
        $results = [
            ['id' => 1, 'name' => 'Product A', 'description' => 'This is Product A'],
            ['id' => 2, 'name' => 'Product B', 'description' => 'This is Product B with ' . $searchTerm . ''],
            ['id' => 3, 'name' => 'Product C', 'description' => 'Another product'],
        ];
    }

    return $results;
}


?>
