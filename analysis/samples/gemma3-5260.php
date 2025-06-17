

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

    .search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" class="search-input" id="searchInput" placeholder="Search...">
    <button class="search-button" onclick="search()">Search</button>
  </div>

  <div id="searchResults" style="margin-top: 20px; display: none;">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("searchResults");

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Example data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 14", description: "The latest iPhone..." },
        { title: "Samsung Galaxy S23", description: "A powerful Android phone..." },
        { title: "Google Pixel 7", description: "Google's flagship phone..." },
        { title: "Laptop - Dell XPS 15", description: "A high-performance laptop..." },
        { title: "Headphones - Sony WH-1000XM5", description: "Noise-canceling headphones..." }
      ];

      // Filter the data based on the search term
      for (var i = 0; i < data.length; i++) {
        if (data[i].title.toLowerCase().includes(searchTerm) ||
            data[i].description.toLowerCase().includes(searchTerm)) {
          var resultItem = document.createElement("div");
          resultItem.innerHTML = `
            <h3>${data[i].title}</h3>
            <p>${data[i].description}</p>
          `;
          resultItem.style.borderBottom = "1px solid #eee";
          resultItem.style.paddingBottom = "10px";
          resultItem.style.textAlign = "left";
          resultItem.style.marginBottom = "10px";
          resultItem.style.cursor = "pointer";

          // Add a click event to expand/collapse the result (optional)
          resultItem.addEventListener("click", function() {
            // Implement your desired action here, like expanding a detailed view
          });

          resultsDiv.appendChild(resultItem);
        }
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established as $conn

$searchTerm = $_GET['search']; // Get the search term from the URL

// Sanitize the input to prevent SQL injection
$searchTerm = $conn->real_escape_string($searchTerm);

// Example database query (replace with your actual query)
$sql = "SELECT title, description FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div id='searchResults'>";
    while($row = $result->fetch_assoc()) {
        echo "<div class='result-item'>";
        echo "<h3>" . $row["title"] . "</h3>";
        echo "<p>" . $row["description"] . "</p>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>No results found.</p>";
}
?>
