  </div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    width: 300px;
    margin: 0 auto; /* Center the container */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-family: sans-serif;
  }

  input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box; /* Important for consistent sizing */
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
  const searchResultsDiv = document.getElementById('searchResults');
  const items = [
    { id: 1, name: "Apple", description: "A juicy fruit." },
    { id: 2, name: "Banana", description: "A yellow fruit." },
    { id: 3, name: "Orange", description: "A citrus fruit." },
    { id: 4, name: "Pineapple", description: "A tropical fruit." }
  ];

  searchInput.addEventListener('keyup', function() {
    const searchTerm = searchInput.value.toLowerCase();
    const results = items.filter(item => {
      return item.name.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
    });

    searchResultsDiv.innerHTML = ''; // Clear previous results

    if (results.length > 0) {
      results.forEach(result => {
        const resultDiv = document.createElement('div');
        resultDiv.innerHTML = `
          <h3>${result.name}</h3>
          <p>${result.description}</p>
        `;
        searchResultsDiv.appendChild(resultDiv);
      });
    } else {
      searchResultsDiv.innerHTML = '<p>No results found.</p>';
    }
  });
</script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and a database table named 'products' with a 'name' column.

//  **Important:** Replace these with your actual database connection details!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Initialize the search term
$search_term = "";

// Handle the form submission (searching)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST["search_term"];
}

// Build the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

// Execute the query (using mysqli)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($query);

?>
