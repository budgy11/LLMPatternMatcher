
</body>
</html>


<?php

// Assuming you have a form with an input field named 'search_term'
// and you want to search through a data source (e.g., a database, an array, etc.).
// This example demonstrates searching through a simple array.

// **1. Get the Search Term from the Form**

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $search_term = htmlspecialchars($_POST["search_term"]); // Sanitize input

  // **2. Define Your Data Source (Example: Array)**
  $data = [
    "apple", "banana", "cherry", "date", "elderberry",
    "fig", "grape", "honeydew", "kiwi", "lemon"
  ];

  // **3. Perform the Search**

  $results = [];
  foreach ($data as $item) {
    // Convert both the item and the search term to lowercase for case-insensitive search
    if (stristr($item, $search_term)) { // stristr is case-insensitive
      $results[] = $item;
    }
  }

  // **4. Display the Results**
  echo "<form method='post' action=''>"; // Simple form to allow refreshing the search
  echo "Search: <input type='text' name='search_term'>";
  echo "<input type='submit' value='Search'>";
  echo "</form>";

  if (empty($results)) {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
  }
} else {
  // If the form hasn't been submitted, display a basic form
  echo "<form method='post' action=''>";
  echo "Search: <input type='text' name='search_term'>";
  echo "<input type='submit' value='Search'>";
  echo "</form>";
}

?>
