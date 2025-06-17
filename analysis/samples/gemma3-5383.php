
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* Basic Styling - Customize to your needs */
    #search-container {
      width: 80%;
      margin: 20px auto;
      text-align: center;
    }

    #search-input {
      width: 70%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    #search-results {
      margin-top: 20px;
      display: none; /* Initially hidden */
    }

    .result-item {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <h1>Search</h1>
    <input type="text" id="search-input" placeholder="Enter search term...">
    <button id="search-button">Search</button>

    <div id="search-results">
      <!-- Search results will be displayed here -->
    </div>
  </div>


  <script>
    document.getElementById('search-button').addEventListener('click', function() {
      var searchTerm = document.getElementById('search-input').value.toLowerCase();
      var searchResultsDiv = document.getElementById('search-results');
      searchResultsDiv.style.display = 'block'; // Show results

      // *** Replace this with your actual search logic ***
      // This is just a placeholder for demonstration purposes.
      var data = [
        { title: "Example Title 1", description: "This is a description for example title 1." },
        { title: "Another Title", description: "A different description." },
        { title: "Search Term 1", description: "A description matching the search term." },
        { title: "Yet Another Title", description: "Something completely different." }
      ];

      var results = [];
      for (var i = 0; i < data.length; i++) {
        if (data[i].title.toLowerCase().includes(searchTerm) || data[i].description.toLowerCase().includes(searchTerm)) {
          results.push(data[i]);
        }
      }


      // Clear previous results
      searchResultsDiv.innerHTML = '';

      // Display the results
      if (results.length > 0) {
        results.forEach(function(result) {
          var resultDiv = document.createElement('div');
          resultDiv.className = 'result-item';
          resultDiv.innerHTML = '<h3>' + result.title + '</h3><p>' + result.description + '</p>';
          resultDiv.appendChild(resultDiv);
        });
      } else {
        searchResultsDiv.innerHTML = '<p>No results found.</p>';
      }
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - Important for security!
$searchTerm = htmlspecialchars(trim($searchTerm));

// Validate the search term - (Optional, but recommended)
if (empty($searchTerm)) {
    $results = []; // Return an empty array if the search term is empty
} else {
    // Construct the SQL query
    $tableName = 'your_table_name';  // Replace with your table name
    $columnName = 'your_column_name'; // Replace with the column you want to search in
    $sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%$searchTerm%'";

    // Execute the query
    $result = mysqli_query($db, $sql); // Replace $db with your database connection

    if ($result) {
        $results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    } else {
        // Handle query error
        echo "Error executing query: " . mysqli_error($db);
    }
}

?>
