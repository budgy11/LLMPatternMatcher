
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
  <style>
    /* Basic styling - feel free to customize */
    #search-container {
      margin-bottom: 10px;
    }
    #search-input {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box; /* Ensures padding doesn't add to the width */
    }
    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    #search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    #search-results li {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results"></ul>

  <script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim(); // Get the value and remove leading/trailing whitespace

      if (searchTerm) { // Only perform the search if there's something to search for

        //  Replace this with your actual search logic
        const data = [
          { title: 'Apple iPhone 15', description: 'The latest iPhone' },
          { title: 'Samsung Galaxy S23', description: 'A powerful Android phone' },
          { title: 'Google Pixel 8', description: 'Google\'s flagship phone' },
          { title: 'Amazon Echo Dot', description: 'Smart speaker' }
        ];

        const results = data.filter(item =>
          item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||  // Search in the title
          item.description.toLowerCase().includes(searchTerm.toLowerCase()) // Also search in description
        );

        searchResults.innerHTML = ''; // Clear previous results

        if (results.length > 0) {
          results.forEach(result => {
            const listItem = document.createElement('li');
            listItem.textContent = `${result.title} - ${result.description}`;
            searchResults.appendChild(listItem);
          });
        } else {
          searchResults.innerHTML = '<p>No results found.</p>';
        }
      } else {
        searchResults.innerHTML = ''; // Clear if the input is empty
      }
    });

    // Add a keypress event listener to trigger the search when the user presses Enter
    searchInput.addEventListener('keypress', function(event) {
      if (event.key === 'Enter') {
        searchButton.click(); // Simulate a button click
      }
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// This example uses mysqli, but you can adapt it to other database connections.
// Make sure you have a database connection object assigned to $conn.

// Example Database Connection (Replace with your actual connection details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the form (e.g., a GET or POST request)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent SQL injection)
$searchTerm = $conn->real_escape_string($searchTerm);

// Example: Search in a table named 'products' with a column named 'name'
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

?>
