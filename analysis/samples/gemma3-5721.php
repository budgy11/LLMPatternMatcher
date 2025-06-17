
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
      display: flex;
      align-items: center;
    }

    .search-input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      flex: 1; /* Take up available space */
    }

    .search-button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-left: 10px;
    }

    .search-results {
      margin-top: 20px;
    }

    .result-item {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" class="search-input" id="searchInput" placeholder="Search...">
    <button class="search-button" onclick="search()">Search</button>
  </div>

  <div id="searchResults" class="search-results">
    <!-- Search results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("searchResults");

      // Example data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 14", description: "The latest iPhone.", link: "https://example.com/iphone14" },
        { title: "Samsung Galaxy S23", description: "A powerful Android phone.", link: "https://example.com/s23" },
        { title: "Google Pixel 7", description: "Google's flagship phone.", link: "https://example.com/pixel7" },
        { title: "Laptop", description: "A portable computer.", link: "https://example.com/laptop" }
      ];

      // Clear previous results (optional)
      resultsDiv.innerHTML = "";

      // Filter the data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the filtered results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var resultItem = document.createElement("div");
          resultItem.classList.add("result-item");
          resultItem.innerHTML = `
            <h3><a href="${item.link}">${item.title}</a></h3>
            <p>${item.description}</p>
          `;
          resultsDiv.appendChild(resultItem);
        });
      } else {
        var noResultsMessage = document.createElement("p");
        noResultsMessage.innerHTML = "No results found.";
        resultsDiv.appendChild(noResultsMessage);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established, let's call it $db

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";
    
    if ($searchTerm) {
        // Perform your database search here
        // Example using a hypothetical "products" table
        $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
        
        // Execute the query
        // Example: $result = mysqli_query($db, $query);  // Use your database library's function
        
        //  Instead of mysqli_query, consider using PDO or another more secure and flexible method
        //  Here's an example using PDO:
        try {
            $stmt = $db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            //Handle the error gracefully (e.g., log it, display a user-friendly message)
            die(); //Stop execution if database error is critical
        }
        
        // Process the results and display them
        if ($results) {
            echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
            echo "<table border='1'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Description</th></tr></thead>";
            echo "<tbody>";
            foreach ($results as $row) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
        }
    }
}
?>
