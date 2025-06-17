

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px; /* Adjust width as needed */
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
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

      //  Example:  Display the search term in an alert (replace with your logic)
      if (searchTerm) {
        alert("You searched for: " + searchTerm);

        //  Replace this with your actual search logic here
        //  This is just a placeholder for how you would
        //  search your data (e.g., database, array, etc.)
        //  Example:
        //  fetch(`/search.php?query=${searchTerm}`)
        //    .then(response => response.json())
        //    .then(data => {
        //      // Process the search results
        //      console.log(data);
        //    });
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
//  $conn = new mysqli("host", "username", "password", "database");
//  if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//  }

//  // Example data (replace with your actual database data)
//  $data = [
//      ["id" => 1, "name" => "Apple"],
//      ["id" => 2, "name" => "Banana"],
//      ["id" => 3, "name" => "Orange"],
//      ["id" => 4, "name" => "Grape"],
//  ];


// Function to handle the search query
function searchData($searchTerm, $data) {
    $results = [];
    foreach ($data as $item) {
        // Case-insensitive search (you can adjust this)
        if (stripos($item["name"], $searchTerm) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Get the search term from the form (assuming a simple HTML form)
if (isset($_GET["search"])) {
    $searchTerm = $_GET["search"];
} else {
    $searchTerm = ""; //  Start with an empty search term if none is provided
}


// Perform the search
$searchResults = searchData($searchTerm, $data);  // Replace $data with your actual data source

?>
