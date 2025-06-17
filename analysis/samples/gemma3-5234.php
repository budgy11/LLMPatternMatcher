
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }

    #search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    #search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    #search-results li {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results"></ul>

  <script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim();

      if (searchTerm === "") {
        searchResults.innerHTML = ""; // Clear results if search term is empty
        return;
      }

      // **Replace this with your actual data source and search logic**
      const data = [
        { title: "Apple iPhone 14", description: "The latest iPhone..." },
        { title: "Samsung Galaxy S23", description: "A powerful Android phone..." },
        { title: "Google Pixel 7", description: "Google's flagship phone..." },
        { title: "Laptop", description: "A portable computer..." }
      ];

      const results = data.filter(item =>
        item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.description.toLowerCase().includes(searchTerm.toLowerCase())
      );


      searchResults.innerHTML = ""; // Clear previous results

      if (results.length === 0) {
        searchResults.innerHTML = "<p>No results found.</p>";
        return;
      }

      results.forEach(item => {
        const listItem = document.createElement('li');
        listItem.textContent = `${item.title} - ${item.description}`;
        searchResults.appendChild(listItem);
      });
    });

    // Optionally:  Add event listener to the input field for "Enter" key press
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            searchButton.click(); // Simulate button click on Enter key
        }
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'products' with a column 'name' that you want to search.
// This code provides a basic search bar implementation.

// Get the search term from the GET request (e.g., from a form submission)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));  // Escape HTML and remove whitespace

// Database connection details (replace with your actual credentials)
// $db = new mysqli("localhost", "username", "password", "database");
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }


// Query to search in the 'name' column of the 'products' table
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
// $result = $db->query($sql);

// Example assuming you have a $db object already connected
// (This section is for demonstration purposes - adjust to your setup)
//  If you're using a database object (like PDO), you'd use its methods.

// Example using mysqli:
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) > 0) {
  // Output the search results
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Enter search term'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
  echo "<ul>";

  while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Assuming you have a 'description' column
  }

  echo "</ul>";
} else {
  echo "<p>No results found for '" . $searchTerm . "'</p>";
}

// Close the database connection (important!)
// $db->close();

?>
