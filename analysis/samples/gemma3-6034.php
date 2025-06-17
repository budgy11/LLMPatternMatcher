
  <script>
    // Optional: Add a little styling or behavior to the search bar
    // Example:  Hide the submit button when the input is focused
    document.getElementById('search_term').addEventListener('focus', function() {
      document.getElementById('search_submit').style.display = 'none';
    });

    document.getElementById('search_term').addEventListener('blur', function() {
      document.getElementById('search_submit').style.display = 'block';
    });
  </script>

</body>
</html>


function searchDatabase($search_term) {
    // Sanitize the search term (add more sanitization as needed)
    $search_term = mysqli_real_escape_string($GLOBALS['conn'], $search_term);

    // Construct the SQL query (using prepared statement)
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Execute the query
    $result = mysqli_query($GLOBALS['conn'], $sql);

    if ($result) {
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row['name']; // Adjust this based on your table structure
        }
        mysqli_free_result($result);
        return $results;
    } else {
        return false; // Handle the error
    }
}


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Ensures padding and border are included in width */
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
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

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      const searchTerm = document.getElementById("searchInput").value;
      const resultsDiv = document.getElementById("results");

      // **Replace this with your actual search logic**
      // This is a placeholder for demonstration purposes.
      const data = [
        { title: "Apple Pie Recipe", description: "A classic apple pie recipe." },
        { title: "Chocolate Cake", description: "Delicious chocolate cake recipe." },
        { title: "PHP Tutorial", description: "Learn PHP programming." },
        { title: "Search Bar Example", description: "This is a demonstration of a search bar." }
      ];

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = "Please enter a search term.";
        return;
      }

      const resultsHtml = data
        .filter(item => item.title.toLowerCase().includes(searchTerm.toLowerCase()) || item.description.toLowerCase().includes(searchTerm.toLowerCase()))
        .map(item => `
          <div>
            <h3>${item.title}</h3>
            <p>${item.description}</p>
          </div>
        `)
        .join('');

      resultsDiv.innerHTML = resultsHtml;
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume you've connected to a database named 'mydatabase'
// and have a table named 'products' with a column 'name'

// Example database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "mydatabase";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}


// Get the search term from the search bar (e.g., from a POST request)
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';


// Sanitize the search term to prevent SQL injection
$searchTerm = $conn->real_escape_string($searchTerm);


// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Use LIKE for partial matches


// Execute the query
$result = $conn->query($sql);


?>
