
</body>
</html>


<?php

// Assuming you have a form to capture the search term

// 1. Get the search term from the form
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];
} else {
  $searchTerm = ''; // Handle the case where the search field is empty
}


// 2. Sanitize the search term (very important for security!)
$searchTerm = htmlspecialchars($searchTerm); // Escape for HTML output
//  You might want to add more robust sanitization depending on your needs, 
//  e.g., stripping out characters, lowercasing, etc.


// 3. Perform your search here
// Example: Search through a database
//  Replace this with your actual search logic.

//  For demonstration, let's just search a simple array.
$myArray = array(
  "apple",
  "banana",
  "cherry",
  "date",
  "grapefruit"
);

$searchResults = array();
foreach ($myArray as $item) {
  if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
    $searchResults[] = $item;
  }
}

// 4. Display the search results
?>
