

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
    box-sizing: border-box;
  }
  button {
    padding: 8px 12px;
    background-color: #4CAF50; /* Green */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  button:hover {
    background-color: #3e8e41;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search...">
  <button onclick="search()">Search</button>
</div>

<script>
function search() {
  var searchTerm = document.getElementById("searchInput").value;

  // Perform your search logic here.  This is just a placeholder.
  // Replace this with your actual search implementation.
  if (searchTerm.trim() === "") {
    alert("Please enter a search term.");
    return;
  }

  console.log("Searching for: " + searchTerm);
  // Example:  Let's pretend we're searching an array of names
  var names = ["Alice Smith", "Bob Johnson", "Charlie Brown", "David Lee"];

  var results = names.filter(function(name) {
    return name.toLowerCase().includes(searchTerm.toLowerCase());
  });

  // Display the results (for demonstration)
  var resultsHTML = "<h2>Search Results:</h2><ul>";
  if (results.length > 0) {
    results.forEach(function(result) {
      resultsHTML += "<li>" + result + "</li>";
    });
  } else {
    resultsHTML += "<li>No results found.</li>";
  }
  resultsHTML += "</ul>";

  // Display the results in a container (replace this with your actual output)
  document.getElementById("resultsContainer").innerHTML = resultsHTML;  //Requires a <div id="resultsContainer"></div> in your HTML
}
</script>


<div id="resultsContainer">
</div>

</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["searchTerm"];

  // Example:  This is placeholder.  Replace with your actual database query.
  $results = array(
    "Product 1",
    "Product 2",
    "Product 3",
    "Product 4"
  );

  // Simulate a database search (replace with your actual query)
  $searchResults = [];
  foreach ($results as $result) {
    if (strtolower($result) == strtolower($searchTerm)) {
      $searchResults[] = $result;
    }
  }

  echo json_encode($searchResults);  // Returns JSON for easy JavaScript consumption
}
?>
