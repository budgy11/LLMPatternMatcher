    </div>

</body>
</html>


<?php

// Basic Search Bar Implementation

// Get the search term from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = trim($_POST["searchTerm"]);

  // Sanitize the search term (important for security!)
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING);

  // Optional:  Further sanitize (e.g., remove special characters)
  // $searchTerm = preg_replace('/[^a-zA-Z0-9\s]/', '', $searchTerm);

  // Perform the search (replace with your actual search logic)
  $results = searchDatabase($searchTerm); // Call a function to do the search

  // Display the results
  echo "<form method='post' action=''>";
  echo "<input type='text' name='searchTerm' placeholder='Search...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";

  if (!empty($results)) {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
} else {
  // If not a POST request, display the search form
  echo "<form method='post' action=''>";
  echo "<input type='text' name='searchTerm' placeholder='Search...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}


//  Example Search Database Function (replace with your actual database logic)
function searchDatabase($searchTerm) {
  // This is a placeholder.  Replace this with your database query.
  // This example just returns some dummy data.

  $data = [
    "Product A",
    "Product B - New Version",
    "Product C - Old Version",
    "Document 1 - Important"
  ];

  $results = [];
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }
  return $results;
}

?>
