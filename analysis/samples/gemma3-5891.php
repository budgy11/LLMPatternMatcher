

<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin: 20px;
  }
  input[type="text"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 300px;
    box-sizing: border-box;
  }
  button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
</style>
</head>
<body>

<div class="search-container">
  <h2>Search:</h2>
  <input type="text" id="searchInput" placeholder="Enter search term">
  <button onclick="search()">Search</button>
</div>

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;

    // **Important: Replace this with your actual search logic**
    // This example just logs the search term to the console.  You
    // need to replace it with your own database query or other
    // search implementation.

    if (searchTerm) {
      console.log("Search term:", searchTerm);
      // Example:  Display the search term on the page.
      // document.getElementById("searchResults").innerHTML = "You searched for: " + searchTerm;
    } else {
      console.log("Please enter a search term.");
    }
  }
</script>

</body>
</html>


<?php
// ... (HTML above) ...

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;

    if (searchTerm) {
      // **Important: Sanitize the input to prevent SQL injection!**
      $safeSearchTerm = mysqli_real_escape_string($conn, $searchTerm); // Assuming $conn is your database connection

      // Build the SQL query (adapt to your database schema)
      $sql = "SELECT * FROM products WHERE name LIKE '%" . $safeSearchTerm . "%'";

      // Execute the query (replace this with your database connection)
      $result = mysqli_query($conn, $sql);

      // Process the results (display them, etc.)
      if (mysqli_num_rows($result) > 0) {
        // Display the results (example - adapt to your needs)
        //  for ($row = 0; $row < mysqli_num_rows($result); $row++) {
        //    $row = mysqli_fetch_assoc($result);
        //    console.log("Product Name:", $row['name']);
        //  }
        console.log("Search results for:", searchTerm);
      } else {
        console.log("No results found for:", searchTerm);
      }
    } else {
      console.log("Please enter a search term.");
    }
  }
</script>

<?php
// Your PHP code for database connection and query execution would go here.
// For example:
// $conn = mysqli_connect("localhost", "username", "password", "database_name");
// if (!$conn) {
//   die("Connection failed: " . mysqli_connect_error());
// }
?>
