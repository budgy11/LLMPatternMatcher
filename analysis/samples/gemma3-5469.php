

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px; /* Adjust width as needed */
      margin: 0 auto; /* Center the search bar */
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
  </div>

  <script>
    const searchInput = document.getElementById('search-input');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();
      //  Here you would implement your search logic
      //  Example:
      //  document.querySelectorAll('.search-item').forEach(item => {
      //     if (item.textContent.toLowerCase().includes(searchTerm)) {
      //        item.style.display = 'block';
      //     } else {
      //         item.style.display = 'none';
      //     }
      // });

      // Placeholder for demonstration - just prints the search term
      console.log(searchTerm);
    });
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      width: 300px;
      margin: 0 auto;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
  </div>

  <script>
    const searchInput = document.getElementById('search-input');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();
      const items = [
        { name: "Apple", description: "A red fruit" },
        { name: "Banana", description: "A yellow fruit" },
        { name: "Orange", description: "A citrus fruit" }
      ];

      document.querySelectorAll('.search-item').forEach(item => {
        if (item.textContent.toLowerCase().includes(searchTerm)) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });
  </script>

  <div class="search-item" style="display: none;">Item 1</div>
  <div class="search-item" style="display: none;">Item 2</div>
  <div class="search-item" style="display: none;">Item 3</div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
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
    <button id="searchButton">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    // JavaScript to handle the search functionality

    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchResultsDiv = document.getElementById('searchResults');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim();

      if (searchTerm === '') {
        searchResultsDiv.innerHTML = ''; // Clear results if search term is empty
        return;
      }

      // Simulate a search (replace with your actual search logic)
      const results = simulateSearch(searchTerm);

      if (results.length === 0) {
        searchResultsDiv.innerHTML = '<p>No results found.</p>';
      } else {
        searchResultsDiv.innerHTML = '';
        for (let i = 0; i < results.length; i++) {
          const resultItem = document.createElement('div');
          resultItem.textContent = results[i];
          resultItem.style.marginBottom = '10px';  // Add spacing between results
          resultItem.style.borderBottom = '1px solid #eee';
          resultItem.addEventListener('click', function() {
            // Add click handling for each result (e.g., navigate to a page)
            alert('Clicked: ' + results[i]);
          });
          searchResultsDiv.appendChild(resultItem);
        }
      }
    });

    // Simulate a search function (replace with your database query or API call)
    function simulateSearch(searchTerm) {
      // This is just a placeholder.  Replace this with your actual search logic.
      const simulatedResults = [
        `Result 1 for ${searchTerm}`,
        `Result 2 for ${searchTerm}`,
        `Result 3 for ${searchTerm}`
      ];
      return simulatedResults;
    }
  </script>

</body>
</html>


<?php
// ... (HTML code from above) ...

// ... (JavaScript code from above) ...

// Simulate a search function (replace with your database query)
function simulateSearch($searchTerm) {
  //  DANGER:  This is just for demonstration and is vulnerable to SQL injection.
  //  DO NOT use this in production without proper sanitization and/or prepared statements.

  // Using a prepared statement is the correct approach, but for demonstration,
  // I'll show an example.  You *must* use prepared statements in production.

  $db = mysqli_connect("your_host", "your_user", "your_password", "your_database");
  if (mysqli_connect_errno($db)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      return false;
  }

  $query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $db->real_escape_string($searchTerm) . "%'"; // Correct escaping

  $result = mysqli_query($db, $query);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_close($db);
  return $rows;
}

// ... (JavaScript code from above) ...
?>
