
</body>
</html>


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
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
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

      // Perform your search logic here
      // This is just an example:
      if (searchTerm.length > 0) {
        alert("Searching for: " + searchTerm); // Replace with your actual search implementation
        // Example:  you could fetch data from a database or array based on searchTerm
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form submission
$search_term = isset($_POST['search']) ? $_POST['search'] : '';

// Sanitize the search term (very important for security)
$search_term = htmlspecialchars(trim($search_term)); 

//  Escape characters to prevent SQL injection.  This is critical.
//  Using prepared statements is generally preferred for production environments.
$search_term = $db->real_escape_string($search_term);


// Perform the search
if ($search_term) {
  // Replace this with your actual database query.  This is just an example.
  // Adjust table name, column names, and search condition as needed.
  $query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'"; 
  $result = $db->query($query);

  if ($result) {
    echo '<form method="post" action="" style="margin-bottom: 20px;">
          <input type="text" name="search" placeholder="Search..." value="' . $search_term . '">
          <button type="submit">Search</button>
        </form>';

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '<div class="search-result">
              <strong>' . htmlspecialchars($row['name']) . '</strong> - ' . htmlspecialchars($row['description']) . '
              <a href="product.php?id=' . $row['id'] . '">View Details</a>
            </div>';
      }
    } else {
      echo '<p>No results found for "' . $search_term . '".</p>';
    }
  } else {
    echo '<p>Error executing query: ' . $db->error . '</p>';
  }
} else {
  // If no search term is provided, display the initial search form
  echo '<form method="post" action="" style="margin-bottom: 20px;">
          <input type="text" name="search" placeholder="Search..." value="">
          <button type="submit">Search</button>
        </form>';
}

?>
