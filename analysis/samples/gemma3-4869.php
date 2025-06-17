

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin: 20px;
    }

    #search-input {
      width: 300px;
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
      list-style: none;
      padding: 0;
    }

    #search-results li {
      margin-bottom: 10px;
      padding: 10px;
      background-color: #f9f9f9;
      border: 1px solid #eee;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Search</h1>
    <input type="text" id="search-input" placeholder="Enter search term">
    <button id="search-button">Search</button>

    <ul id="search-results"></ul>
  </div>


  <script>
    constsearchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim();

      if (searchTerm) {
        //  Replace this with your actual search logic (database query, etc.)
        const results = performSearch(searchTerm); 

        searchResults.innerHTML = ''; // Clear previous results

        if (results.length > 0) {
          results.forEach(result => {
            const listItem = document.createElement('li');
            listItem.textContent = result;
            searchResults.appendChild(listItem);
          });
        } else {
          searchResults.innerHTML = '<p>No results found.</p>';
        }
      } else {
        searchResults.innerHTML = '<p>Please enter a search term.</p>';
      }
    });

    // Example of performing a search (replace with your actual implementation)
    function performSearch(searchTerm) {
      //  This is a placeholder.  Replace with your actual search logic.
      const data = [
        'Apple',
        'Banana',
        'Orange',
        'Grape',
        'Strawberry'
      ];

      const results = data.filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));
      return results;
    }
  </script>

</body>
</html>


// (Inside your JavaScript, after fetch or similar)
function performSearch(searchTerm) {
    // Connect to your database (assuming PDO)
    try {
        $dsn = 'mysql:host=localhost;dbname=your_database';
        $username = 'your_username';
        $password = 'your_password';

        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for easier error handling

        $query = "SELECT * FROM your_table WHERE title LIKE :searchTerm OR description LIKE :searchTerm"; // Example query
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%'); // Use LIKE with wildcards
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        // Handle database errors here (e.g., log the error)
        echo "Database error: " . $e->getMessage();
        return []; // Return an empty array in case of error
    }
}


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
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
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const items = [
      { name: 'Apple', description: 'A delicious fruit.' },
      { name: 'Banana', description: 'A yellow fruit.' },
      { name: 'Orange', description: 'A citrus fruit.' },
      { name: 'Grape', description: 'Small and sweet.' },
      { name: 'Strawberry', description: 'Red and juicy.' }
    ];

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchTerm.toLowerCase(); //Convert to lowercase for case-insensitive search
      const results = items.filter(item => {
        return item.name.toLowerCase().includes(searchTerm);
      });

      //Clear previous results
      searchResults.innerHTML = '';

      //Display results
      if (results.length > 0) {
        results.forEach(result => {
          const listItem = document.createElement('div');
          listItem.innerHTML = `${result.name}: ${result.description}`;
          searchResults.appendChild(listItem);
        });
      } else {
        searchResults.innerHTML = 'No results found.';
      }
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Sanitize the search term to prevent SQL injection
  $searchTerm = htmlspecialchars($searchTerm);

  // Perform your search query here.  This is just an example and needs to be adapted
  // to your specific database structure and needs.
  $query = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'"; 

  // Execute the query
  $result = mysqli_query($conn, $query);

  // Display the results
  if ($result) {
    echo "<h2>Search Results for: '$searchTerm'</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Name</th><th>Description</th></tr></thead>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['description'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";

    // Optional:  Link back to the search form
    echo "<br><a href='search.php'>Back to Search</a>";
  } else {
    echo "<p>No results found for: '$searchTerm'</p>";
  }
}

?>
