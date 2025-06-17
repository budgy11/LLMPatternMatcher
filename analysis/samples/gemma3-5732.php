
</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    width: 300px;
    margin: 20px auto;
  }

  .search-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Important for consistent sizing */
  }

  .search-button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }

  .search-results {
    margin-top: 20px;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" class="search-input" placeholder="Search...">
  <button class="search-button" onclick="search()">Search</button>
</div>

<div class="search-results">
  <!-- Search results will be displayed here -->
</div>

<script>
  function search() {
    var searchTerm = document.querySelector('.search-input').value;
    var resultsDiv = document.querySelector('.search-results');

    if (searchTerm.trim() === "") {
      resultsDiv.innerHTML = ""; // Clear results if input is empty
      return;
    }

    // Simulate a search (replace with your actual search logic)
    var data = [
      "Apple", "Banana", "Orange", "Grape", "Strawberry",
      "Pineapple", "Mango", "Kiwi", "Watermelon", "Peach"
    ];

    var results = data.filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));

    if (results.length === 0) {
      resultsDiv.innerHTML = "<p>No results found.</p>";
    } else {
      var resultHTML = "";
      results.forEach(item => {
        resultHTML += "<p>" + item + "</p>";
      });
      resultsDiv.innerHTML = resultHTML;
    }
  }
</script>

</body>
</html>


<?php

// Assuming you have a form to collect the search term.  This is just an example
// and you'll need to adapt it to your HTML form.

// Example Form (place this in your HTML)
// <form method="GET" action="search.php">
//   <input type="text" name="search_term" placeholder="Search...">
//   <button type="submit">Search</button>
// </form>

// This script (search.php) receives the search term from the form.

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Get the search term from the form
  $search_term = isset($_GET["search_term"]) ? trim($_GET["search_term"]) : "";

  // Sanitize and validate the search term (important for security!)
  $search_term = htmlspecialchars(trim($search_term)); // Converts special chars to HTML entities
  //  Further validation:  check length, allow only alphanumeric, etc.  This is crucial.
  if (empty($search_term)) {
      // No search term entered, display a message or redirect
      echo "<p>Please enter a search term.</p>";
      // Or, redirect to the same page to prompt the user.
      // header("Location: search.php");
      exit;
  }

  // Perform the search (replace with your database query or logic)
  $results = performSearch($search_term); // Call a function to handle the search

  // Display the search results
  echo "<h2>Search Results for: " . $search_term . "</h2>";

  if (empty($results)) {
    echo "<p>No results found for '" . $search_term . "'.</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
} else {
  // Handle other request methods (e.g., POST) if needed
  echo "<p>Invalid request method.</p>";
}


// Helper function to perform the search (replace with your actual search logic)
function performSearch($term) {
  // This is a placeholder. Replace this with your database query or other search method.
  // Example:  Simulated search based on the search term.
  $results = [
    "Product 1 -  A great product!",
    "Product 2 -  Another amazing product.",
    "Service A -  Professional service.",
    "Service B -  Reliable service."
  ];

  return $results;
}
?>
