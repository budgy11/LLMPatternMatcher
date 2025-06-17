
</body>
</html>


<?php

// Simple Search Bar Implementation

// Initialize variables
$search_term = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_term = trim($_POST["search_term"]); // Trim whitespace
}

// Search Logic (Example: Searching through an array)
$products = [
  "Laptop - 15 inch",
  "Wireless Mouse",
  "Keyboard - Mechanical",
  "Monitor - 27 inch",
  "USB Hub",
];

if (!empty($search_term)) {
  $results = [];
  foreach ($products as $product) {
    if (stripos($product, $search_term) !== false) { // Case-insensitive search
      $results[] = $product;
    }
  }

  if (!empty($results)) {
    echo "<p>Search Results for: " . htmlspecialchars($search_term) . "</p>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'</p>";
  }
}

// HTML Form for the Search Bar
?>
