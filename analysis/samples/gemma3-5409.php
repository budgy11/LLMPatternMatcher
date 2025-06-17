

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic Styling - Customize as needed */
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    .search-input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .search-button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .search-button:hover {
      background-color: #3e8e41;
    }

    .results {
      margin-top: 20px;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" class="search-input" placeholder="Search...">
    <button id="searchButton" class="search-button">Search</button>
  </div>

  <div id="results" class="results" style="display: none;">
    <!-- Results will be displayed here -->
  </div>

  <script>
    // Get references to the input and button elements
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const resultsDiv = document.getElementById('results');

    // Add an event listener to the button
    searchButton.addEventListener('click', function() {
      // Get the search term
      const searchTerm = searchTerm.trim();

      // Perform your search logic here
      // Example:  Search a simple array of strings
      const data = ["Apple", "Banana", "Orange", "Grape", "Strawberry"];

      // Filter the data based on the search term
      const filteredData = data.filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));

      // Display the results
      if (filteredData.length > 0) {
        resultsDiv.innerHTML = `<h3>Search Results:</h3><ul>`;
        filteredData.forEach(item => {
          resultsDiv.innerHTML += `<li>${item}</li>`;
        });
        resultsDiv.innerHTML += `</ul>`;
        resultsDiv.style.display = 'block'; // Show the results
      } else {
        resultsDiv.innerHTML = '<p>No results found.</p>';
        resultsDiv.style.display = 'block';
      }
    });

    // Handle Enter key press (alternative to button click)
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        this.focus();  // Keep the input focused
        // You can also trigger the search logic here if desired
        //  (e.g., call the same function as the button click)
      }
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db is your database connection)
// and you want to search through a table named 'products' with a column 'name'

// 1. Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize the search term (Important for security)
$searchTerm = htmlspecialchars(trim($searchTerm)); 

// 3. Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// 4. Execute the query
$result = mysqli_query($db, $sql); // Assuming $db is your mysqli connection

// 5. Display the results (or an error message if no results are found)
echo "<form method='GET'>
      <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search products...'>
      <button type='submit'>Search</button>
    </form>";

if (mysqli_num_rows($result) > 0) {
  echo "<ul>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Adjust based on your product table structure
  }
  echo "</ul>";
} else {
  echo "<p>No products found matching your search.</p>";
}


// Close the database connection when you're done.
mysqli_close($db);

?>
