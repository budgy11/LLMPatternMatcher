

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 10px;
      width: 300px;
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
      font-size: 16px;
    }
    .search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    .search-results li {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div class="search-results" id="searchResults">
    <!-- Search results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsContainer = document.getElementById("searchResults");
      resultsContainer.innerHTML = ""; // Clear previous results

      // Sample data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 13", description: "The latest iPhone." },
        { title: "Samsung Galaxy S22", description: "A powerful Android phone." },
        { title: "Google Pixel 6", description: "Google's flagship phone." },
        { title: "Amazon Echo Dot", description: "A voice-controlled smart speaker." },
        { title: "Sony WH-1000XM5", description: "Noise-canceling headphones." }
      ];

      // Filter the data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the filtered results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var listItem = document.createElement("li");
          listItem.innerHTML = item.title + "<br>" + item.description;
          resultsContainer.appendChild(listItem);
        });
      } else {
        var noResultsMessage = document.createElement("p");
        noResultsMessage.innerHTML = "No results found.";
        resultsContainer.appendChild(noResultsMessage);
      }
    }
  </script>

</body>
</html>


<?php
// Assume you have a database connection established (e.g., $conn)

$searchTerm = $_GET['search']; // Get the search term from the URL

if (empty($searchTerm)) {
    // Handle empty search (e.g., display all data)
    $results = mysqli_query($conn, "SELECT * FROM products"); // Example
} else {
    $sql = "SELECT * FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
    $results = mysqli_query($conn, $sql);
}

// Output the results (simplified for example)
if (mysqli_num_rows($results) > 0) {
    echo "<ul>";
    while($row = mysqli_fetch_assoc($results)) {
        echo "<li>" . $row['title'] . " - " . $row['description'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found.</p>";
}
?>
