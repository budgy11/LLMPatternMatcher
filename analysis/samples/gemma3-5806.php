

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
      box-sizing: border-box; /* Ensures padding doesn't increase the width */
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchFunction()" >
    <button onclick="searchFunction()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Sample data for demonstration
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Kiwi",
        "Strawberry",
        "Blueberry"
      ];

      // Loop through the data and display matches
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().includes(searchTerm)) {
          var resultItem = document.createElement("p");
          resultItem.textContent = data[i];
          resultsDiv.appendChild(resultItem);
        }
      }
    }
  </script>

</body>
</html>


<?php

$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Example data (replace with your database query)
$data = [
    "Apple",
    "Banana",
    "Orange",
    "Grape",
    "Kiwi",
    "Strawberry",
    "Blueberry"
];

if (isset($_GET["search"])) {
    $searchTerm = $_GET["search"];
} else {
    $searchTerm = "";
}

// Search the data array (replace with your database query)
$results = [];
foreach ($data as $item) {
    if (strtolower($item) === strtolower($searchTerm)) {
        $results[] = $item;
    }
}

?>
