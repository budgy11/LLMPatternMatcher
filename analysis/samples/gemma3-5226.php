

<!DOCTYPE html>
<html>
<head>
<title>PHP Search Bar</title>
<style>
  .search-container {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
  }

  input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  button {
    padding: 8px 15px;
    background-color: #4CAF50;
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

    //  Basic example - replace this with your actual data retrieval logic
    //  This example just logs the search term.

    console.log("Searching for:", searchTerm);

    // **Important: Replace this with your data retrieval code.**
    // This code is just a placeholder.

    // Example: Retrieve data from an array (for demonstration)
    var data = [
      "Apple", "Banana", "Orange", "Grape", "Kiwi", "Mango"
    ];

    var results = data.filter(function(item) {
      return item.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
    });

    // Display results (replace with your desired display method)
    if (results.length > 0) {
      alert("Found results: " + results.join(", "));
    } else {
      alert("No results found.");
    }
  }
</script>

</body>
</html>


<?php
// Assume you have a database connection established: $conn

if (isset($_SERVER["REQUEST_METHOD"]) && "POST" == $_SERVER["REQUEST_METHOD"]) {
    $searchTerm = $_POST["searchInput"];

    // Prepare SQL query (important for security!)
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    $products = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    // Output (example - replace with your desired display method)
    if (count($products) > 0) {
      echo "<ul>";
      foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - " . $product['price'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No products found.</p>";
    }
}
?>
