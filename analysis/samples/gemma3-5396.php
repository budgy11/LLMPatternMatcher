  <input type="submit" value="Search">
</form>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling - customize as needed */
    .search-container {
      width: 300px;
      margin: 0 auto;
    }
    .search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important to include padding and border in the element's total width */
    }
    .search-button {
      padding: 10px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    .results {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" class="search-input" placeholder="Search...">
    <button class="search-button" onclick="performSearch()">Search</button>
  </div>

  <div id="results" class="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function performSearch() {
      var searchTerm = document.querySelector('.search-input').value;

      //  Implement your search logic here.
      //  This is just a placeholder example - replace with your actual logic.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Placeholder example - Replace this with your actual search code.
      var resultsHTML = "<ul>";
      for (var i = 0; i < 5; i++) {
        resultsHTML += "<li>Result " + (i + 1) + "</li>";
      }
      resultsHTML += "</ul>";

      document.getElementById('results').innerHTML = resultsHTML;
    }
  </script>

</body>
</html>


<?php
// Example data (replace with your actual data source)
$data = [
  "apple", "banana", "cherry", "date", "elderberry"
];

function performSearch() {
  $searchTerm = $_POST['search_term']; // Get search term from the form

  if (empty($searchTerm)) {
    echo "<p>Please enter a search term.</p>";
    return;
  }

  $resultsHTML = "<ul>";
  foreach ($data as $item) {
    if (strtolower($item) === strtolower($searchTerm)) { // Case-insensitive search
      $resultsHTML .= "<li>" . $item . "</li>";
    }
  }
  $resultsHTML .= "</ul>";

  document.getElementById('results').innerHTML = resultsHTML;
}

// Code to handle the form submission and trigger the search
?>
