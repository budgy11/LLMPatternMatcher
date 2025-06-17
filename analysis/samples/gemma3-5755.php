

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .results {
      margin-top: 20px;
    }
    .result-item {
      padding: 10px;
      border: 1px solid #eee;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search</h2>
    <input type="text" id="search-input" placeholder="Enter search term">
    <button onclick="performSearch()">Search</button>
  </div>

  <div class="results" id="results-container">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function performSearch() {
      const searchTerm = document.getElementById('search-input').value.toLowerCase();
      const resultsContainer = document.getElementById('results-container');

      // **Important:  Replace this with your actual data source and logic**
      const data = [
        { title: 'Apple iPhone 14', description: 'The latest iPhone with a fantastic camera.' },
        { title: 'Samsung Galaxy S23', description: 'A powerful Android phone.' },
        { title: 'Google Pixel 7', description: 'Google\'s flagship phone.' },
        { title: 'Laptop - Dell XPS 13', description: 'A sleek and powerful laptop.' },
        { title: 'Gaming PC - Corsair', description: 'A high-performance gaming PC.' },
      ];

      // Clear previous results
      resultsContainer.innerHTML = '';

      // Loop through the data and display matching results
      data.forEach(item => {
        const title = item.title.toLowerCase();
        const description = item.description.toLowerCase();

        if (title.includes(searchTerm) || description.includes(searchTerm)) {
          const resultItem = document.createElement('div');
          resultItem.classList.add('result-item');
          resultItem.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
          `;
          resultsContainer.appendChild(resultItem);
        }
      });
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established
$searchTerm = $_GET['search']; // Get the search term from the query string

// Database connection details (replace with your details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Construct the SQL query (case-insensitive search)
$sql = "SELECT title, description FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<div class='result-item'>" .
         "<h3>" . $row["title"] . "</h3>" .
         "<p>" . $row["description"] . "</p>" .
         "</div>";
  }
} else {
  echo "<p>No results found.</p>";
}

$conn->close();
?>
