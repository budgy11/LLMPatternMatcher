
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
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width */
    }

    button {
      padding: 10px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      margin-left: 5px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const resultsDiv = document.getElementById('results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim(); // Get the value and remove leading/trailing whitespace

      if (searchTerm) { // Only perform the search if there is a search term
        // Simulate a search (replace with your actual search logic)
        const data = [
          "apple", "banana", "orange", "grape", "pear", "kiwi",
          "strawberry", "blueberry", "raspberry", "mango", "pineapple"
        ];

        const filteredData = data.filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));

        // Display the results
        resultsDiv.innerHTML = ''; // Clear previous results

        if (filteredData.length > 0) {
          filteredData.forEach(item => {
            const resultItem = document.createElement('p');
            resultItem.textContent = item;
            resultsDiv.appendChild(resultItem);
          });
        } else {
          resultsDiv.textContent = 'No results found.';
        }
      } else {
        alert('Please enter a search term.');
      }
    });

    // Optional: Handle Enter key press
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        searchButton.click(); // Trigger the search button
      }
    });
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established ($conn)

// Get search term from request
$searchTerm = $_GET['search'];

// Sanitize the search term
$searchTerm = htmlspecialchars($searchTerm); // Prevent XSS

// Construct and execute the SQL query
$sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";
$result = mysqli_query($conn, $sql);

// Process the results
if ($result) {
    echo '<ul>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<li>' . $row['your_column_name'] . '</li>';  // Adjust column names
    }
    echo '</ul>';
} else {
    echo "Error executing query.";
}
?>
