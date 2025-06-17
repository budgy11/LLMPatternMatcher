
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Ensures padding doesn't affect width */
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // Sample data (replace with your actual data source)
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry",
        "Pineapple",
        "Mango",
        "Watermelon",
        "Kiwi",
        "Peach"
      ];

      // Filter data based on the search term
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm) !== -1) {
          var listItem = document.createElement("p");
          listItem.textContent = data[i];
          resultsDiv.appendChild(listItem);
        }
      }
    }
  </script>

</body>
</html>


<?php
  // Assuming you have a database connection established ($conn)

  $searchTerm = $_GET['search']; // Get search term from URL

  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Process the results here (e.g., display in a table)
    while ($row = mysqli_fetch_assoc($result)) {
      echo "Product Name: " . $row['name'] . "<br>";
      echo "Price: " . $row['price'] . "<br>";
      // ... other product details ...
    }
  } else {
    echo "No results found.";
  }
?>
