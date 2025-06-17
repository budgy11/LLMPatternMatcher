

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }
    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
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

      //  Add your search logic here. This is just a placeholder.
      //  Replace this with your actual search functionality, e.g.,
      //  -  Query a database
      //  -  Filter an array of data

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example: Display the search term in an alert
      alert("You searched for: " + searchTerm);

      //  You could also perform a search operation here and update the
      //  content of your page based on the results.  For example:
      //  -  Update a div with an id of "searchResults"
      //  -  Change the URL (e.g., using JavaScript's location.href)
    }
  </script>

</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Replace with your actual database query
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // Simple LIKE query

  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Process the results and display them
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "No results found.";
  }
}
?>
