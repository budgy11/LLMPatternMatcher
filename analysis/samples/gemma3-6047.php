
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
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
    <h1>Search</h1>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="performSearch()">Search</button>
  </div>


  <script>
    function performSearch() {
      var searchTerm = document.getElementById("searchInput").value;

      //  You'll replace this with your actual search logic
      //  This is just a placeholder to demonstrate the functionality.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Echo the search term back to the page
      document.getElementById("results").innerHTML = "<p>Searching for: " + searchTerm + "</p>";

      //  Implement your actual search logic here:
      //  - Fetch data from a database or other source
      //  - Filter the data based on the searchTerm
      //  - Display the results on the page.


    }
  </script>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

</body>
</html>


$searchTerm = $_GET['search'];  // Example: getting search term from a URL parameter

$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :searchTerm OR description LIKE :searchTerm");
$searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matches

$stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Display $results


<?php

// Sample data (replace this with your actual data source)
$products = [
    "Red T-Shirt",
    "Blue Jeans",
    "Black Leather Jacket",
    "Gray Wool Sweater",
    "Black Boots",
    "Red Hat",
    "Blue Scarf",
    "Black Gloves",
];

// Function to perform the search
function searchProducts($searchTerm, $products) {
    $results = [];

    foreach ($products as $product) {
        if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
            $results[] = $product;
        }
    }

    return $results;
}

// Get the search term from the form submission (or directly from URL if needed)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>
