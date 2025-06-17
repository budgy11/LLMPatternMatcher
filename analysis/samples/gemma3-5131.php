

<?php

// Basic Search Bar Implementation

// Define variables
$searchTerm = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = isset($_POST["search"]) ? htmlspecialchars($_POST["search"]) : ""; 

  // You can now process the $searchTerm and perform your search logic
  // For example:
  if ($searchTerm != "") {
    echo "You searched for: " . $searchTerm . "<br>";

    // Placeholder for your search functionality (replace with your actual logic)
    // Example: Searching through an array
    $items = ["apple", "banana", "orange", "grape"];
    $results = array_filter($items, function($item) use ($searchTerm) {
        return stripos($item, $searchTerm) !== false; // Case-insensitive search
    });

    if (count($results) > 0) {
      echo "Search Results:<br>";
      foreach ($results as $result) {
        echo "- " . $result . "<br>";
      }
    } else {
      echo "No results found for '" . $searchTerm . "'.";
    }
  }
}
?>
