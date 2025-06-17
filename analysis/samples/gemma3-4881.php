
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px; /* Adjust width as needed */
      margin: 20px auto; /* Center the search bar */
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width */
    }

    button {
      padding: 10px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
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
      // Get the value from the input field
      var searchTerm = document.getElementById("searchInput").value;

      // **Important:** Replace this with your actual search logic.
      // This is just a placeholder to demonstrate the process.

      // Example:  Simple console log to show the search term
      console.log("Searching for: " + searchTerm);

      // **Your Search Logic Here**
      // This is where you would:
      // 1.  Fetch data based on searchTerm (e.g., from a database).
      // 2.  Process the data.
      // 3.  Display the results.

      // For example, if you had a database of items:
      // let results = searchDatabase(searchTerm);
      // document.getElementById("resultsArea").innerHTML = results; // Assuming you have a <div id="resultsArea"></div>

    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established in your PHP code
// (e.g., $conn is your database connection object)

$searchTerm = $_POST['searchInput']; // Get the search term from the form

$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output the data of each row as a JSON array
    $output = array();
    while($row = $result->fetch_assoc()) {
        $output[] = $row;
    }
    echo json_encode($output);

} else {
    echo "No results found.";
}
?>
