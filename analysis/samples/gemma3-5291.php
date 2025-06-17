
</body>
</html>


function performSearch($searchTerm) {
  // Example: Search through an array of products
  $products = [
    "Red T-Shirt" => "Description of Red T-Shirt",
    "Blue Jeans" => "Description of Blue Jeans",
    "Green Hat" => "Description of Green Hat"
  ];

  $results = [];
  foreach ($products as $key => $value) {
    if (stripos($key, $searchTerm) !== false) {  // Case-insensitive search
      $results[$key] = $value;
    }
  }
  return $results;
}


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
      font-size: 16px;
    }

    button {
      padding: 8px 16px;
      background-color: #4CAF50;
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
    <!-- Search results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      // Clear previous results
      resultsDiv.innerHTML = "";

      if (searchTerm.trim() === "") {
        return; // Do nothing if the search term is empty
      }

      //  Simulated data (replace with your actual data source)
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grapefruit",
        "Strawberry",
        "Pineapple",
        "Mango",
        "Avocado",
        "Kiwi"
      ];

      // Search the data
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1) {
          // Create a list item for the result
          var listItem = document.createElement("li");
          listItem.textContent = data[i];
          listItem.style.marginBottom = "5px";
          resultsDiv.appendChild(listItem);
        }
      }

      if (resultsDiv.innerHTML === "") {
          var noResults = document.createElement("p");
          noResults.textContent = "No results found.";
          resultsDiv.appendChild(noResults);
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Example query (replace with your actual search query)
$searchTerm = $_GET["search"]; // Get the search term from the URL

$sql = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%'";  // Replace 'title' and 'your_table'

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    // Display the data
    echo "Title: " . $row["title"] . "<br>";
    echo "Description: " . $row["description"] . "<br>";
  }
} else {
  echo "No results found";
}

$conn->close();
?>
