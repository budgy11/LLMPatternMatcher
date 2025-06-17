

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50; /* Green */
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

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchResultsDiv = document.getElementById("searchResults");

      // Clear previous results
      searchResultsDiv.innerHTML = "";

      // Example: Search a simple array (replace with your data source)
      var data = [
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Elderberry",
        "Fig",
        "Grape",
        "Honeydew",
        "Jackfruit",
        "Kiwi"
      ];

      if (searchTerm.trim() === "") {
        return; // Do nothing if search term is empty
      }

      var results = data.filter(function(item) {
        return item.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
      });

      if (results.length > 0) {
        results.forEach(function(result) {
          var resultElement = document.createElement("p");
          resultElement.textContent = result;
          searchResultsDiv.appendChild(resultElement);
        });
      } else {
        var noResultsElement = document.createElement("p");
        noResultsElement.textContent = "No results found.";
        searchResultsDiv.appendChild(noResultsElement);
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example (Database)</title>
  <style>
    /* ... (CSS Styling) ... */
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchResultsDiv = document.getElementById("searchResults");

      searchResultsDiv.innerHTML = "";

      // **Replace with your actual database connection and query**
      // This is just an example - adapt to your database system (MySQL, PostgreSQL, etc.)
      // Assume you have a database connection established in a separate script or object
      // (e.g., $db)
      //  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
      //  $result = mysqli_query($db, $query);

      //  Example using placeholder data
      var data = [
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Elderberry",
        "Fig",
        "Grape",
        "Honeydew",
        "Jackfruit",
        "Kiwi"
      ];

      if (searchTerm.trim() === "") {
        return;
      }

      var results = data.filter(function(item) {
        return item.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
      });

      if (results.length > 0) {
        results.forEach(function(result) {
          var resultElement = document.createElement("p");
          resultElement.textContent = result;
          searchResultsDiv.appendChild(resultElement);
        });
      } else {
        var noResultsElement = document.createElement("p");
        noResultsElement.textContent = "No results found.";
        searchResultsDiv.appendChild(noResultsElement);
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 0 auto;
      margin-top: 20px;
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
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value;

      // Add your search logic here
      // For example, you could display results based on the searchTerm

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      //  Example:  Display the search term in an alert box
      alert("You searched for: " + searchTerm);
    }
  </script>

</body>
</html>


   $searchTerm = $_GET["search"]; // Get the search term from the URL
   $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";
   
   * **Important Security Note:** *Never* directly include user input (like `$searchTerm`) into your SQL query without proper sanitization and escaping. This is extremely vulnerable to SQL injection attacks.  Use prepared statements (with PDO or mysqli) to prevent SQL injection.  If you're using `LIKE` with `%`,  make sure your database is properly indexed for the `your_column` column to improve search performance.
5. **Display Results:** After executing the SQL query, you'll need to fetch the results and display them on the page (e.g., in a table).

Example of using PDO and prepared statements (much more secure):



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
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    const searchInput = document.getElementById('searchInput');
    const resultsDiv = document.getElementById('results');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchTerm.trim(); // Trim whitespace
      let resultsHTML = '';

      // Simulate a data source (replace with your actual data source)
      const data = [
        { title: 'Apple iPhone 14', description: 'The latest iPhone...' },
        { title: 'Samsung Galaxy S23', description: 'A powerful Android phone...' },
        { title: 'Sony WH-1000XM5 Headphones', description: 'Noise-canceling headphones...' },
        { title: 'Google Pixel 7 Pro', description: 'Google\'s flagship phone...' },
        { title: 'Amazon Echo Dot (5th Gen)', description: 'A smart speaker...' }
      ];

      if (searchTerm) {
        data.forEach(item => {
          const title = item.title.toLowerCase();
          const description = item.description.toLowerCase();

          if (title.includes(searchTerm.toLowerCase()) || description.includes(searchTerm.toLowerCase())) {
            resultsHTML += `
              <div class="search-result">
                <h3>${item.title}</h3>
                <p>${item.description}</p>
              </div>
            `;
          }
        });
      }

      if (resultsHTML) {
        resultsDiv.innerHTML = resultsHTML;
      } else {
        resultsDiv.innerHTML = '<p>No results found.</p>';
      }
    });
  </script>

</body>
</html>


<?php
// Example PHP code to return search results
// Replace this with your actual database query

$searchTerm = $_GET['search']; // Get the search term from the URL
if (!$searchTerm) {
  $results = [];
} else {
  $results = [
    ['title' => 'Apple iPhone 14', 'description' => 'The latest iPhone...'],
    ['title' => 'Samsung Galaxy S23', 'description' => 'A powerful Android phone...'],
    ['title' => 'Sony WH-1000XM5 Headphones', 'description' => 'Noise-canceling headphones...'],
    ['title' => 'Google Pixel 7 Pro', 'description' => 'Google\'s flagship phone...'],
    ['title' => 'Amazon Echo Dot (5th Gen)', 'description' => 'A smart speaker...']
  ];
}

// Output the results as JSON
header('Content-Type: application/json');
echo json_encode($results);
?>
