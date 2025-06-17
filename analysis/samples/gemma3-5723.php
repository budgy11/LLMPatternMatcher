        <input type="text" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
/* Basic styling for the search bar */
.search-container {
  width: 300px;
  margin: 0 auto;
  text-align: center;
}

.search-input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  box-sizing: border-box; /* Include padding and border in the element's total width */
}

.search-button {
  padding: 10px 20px;
  background-color: #4CAF50; /* Green */
  color: white;
  border: none;
  cursor: pointer;
  margin-left: 10px;
}

.search-button:hover {
  background-color: #3e8e41;
}
</style>
</head>
<body>

<div class="search-container">
  <input type="text" class="search-input" id="searchInput" placeholder="Search...">
  <button class="search-button" onclick="search()">Search</button>
</div>

<script>
function search() {
  var searchTerm = document.getElementById("searchInput").value;

  if (searchTerm.trim() === "") {
    // Handle empty search
    alert("Please enter a search term.");
    return;
  }

  //  Replace this with your actual search logic.  This is just a placeholder.
  console.log("Searching for: " + searchTerm);
  // Example:  You'd likely fetch data from an API or your database here.
  // For demonstration, we'll just display the search term in an alert.
  alert("Searching for: " + searchTerm);

  // Clear the input field after the search
  document.getElementById("searchInput").value = "";
}
</script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
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
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchFunction()">
    <button onclick="searchFunction()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // **Replace this with your actual data retrieval logic**
      var data = [
        { title: "Apple Pie", description: "Delicious apple pie recipe." },
        { title: "Banana Bread", description: "Classic banana bread recipe." },
        { title: "Chocolate Cake", description: "Rich chocolate cake recipe." },
        { title: "Strawberry Shortcake", description: "Sweet strawberry shortcake recipe." }
      ];

      // Iterate through the data and filter based on the search term
      for (var i = 0; i < data.length; i++) {
        if (data[i].title.toLowerCase().indexOf(searchTerm) > -1) {
          var listItem = document.createElement("div");
          listItem.innerHTML = "<b>" + data[i].title + "</b><br>" + data[i].description;
          listItem.style.borderBottom = "1px solid #eee";
          listItem.style.paddingBottom = "10px";
          listItem.style.marginBottom = "5px";
          listItem.style.textAlign = "left";
          resultsDiv.appendChild(listItem);
        }
      }

      // If no results are found
      if (resultsDiv.innerHTML === "") {
        resultsDiv.innerHTML = "<p>No results found for '" + searchTerm + "'</p>";
      }
    }
  </script>

</body>
</html>


<?php

// Simple search bar implementation using PHP

// 1. Get the search term from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search"]) ? htmlspecialchars($_POST["search"]) : "";
}

// 2.  Data to search through (example - replace with your data source)
$data = [
    ["id" => 1, "name" => "Apple", "description" => "A sweet fruit."],
    ["id" => 2, "name" => "Banana", "description" => "A yellow fruit."],
    ["id" => 3, "name" => "Orange", "description" => "A citrus fruit."],
    ["id" => 4, "name" => "Grape", "description" => "Small, juicy fruit."]
];

// 3.  Search the data
if ($searchTerm) {
  $searchResults = [];
  foreach ($data as $item) {
    // Case-insensitive search
    $found = false;
    $searchTermLower = strtolower($searchTerm);
    $nameLower = strtolower($item["name"]);
    $descriptionLower = strtolower($item["description"]);

    if (strpos($nameLower, $searchTermLower) !== false ||
        strpos($descriptionLower, $searchTermLower) !== false) {
      $found = true;
    }

    if ($found) {
      $searchResults[] = $item;
    }
  }
} else {
  $searchResults = []; // Reset if search term is empty
}

?>
