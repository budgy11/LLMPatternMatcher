

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
    font-size: 16px;
  }
</style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchBox" placeholder="Search..." onkeyup="searchFunction()">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>


  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchBox").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // Replace this with your actual data retrieval logic
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry",
        "Pineapple",
        "Mango",
        "Avocado"
      ];

      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm) > -1) {
          var listItem = document.createElement("p");
          listItem.textContent = data[i];
          resultsDiv.appendChild(listItem);
        }
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established
// e.g., $conn = mysqli_connect("host", "user", "password", "database");

// ... (HTML code as above) ...

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchBox").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = "";

      // SQL query (replace with your actual query and table name)
      $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
      $result = mysqli_query($conn, $sql);

      if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
          var listItem = document.createElement("p");
          listItem.textContent = $row['name'];  // Assuming 'name' is the column for product name
          resultsDiv.appendChild(listItem);
        }
        mysqli_free_result($result); // Free the result set
      } else {
        resultsDiv.innerHTML = "No results found.";
      }
    }
  </script>


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
    <h1>Search:</h1>
    <input type="text" id="search-input" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById('search-input').value;
      var resultsDiv = document.getElementById('results');

      //  Replace this with your actual search logic.  This is a basic example.
      if (searchTerm.trim() === "") {
          resultsDiv.innerHTML = "Please enter a search term.";
          return;
      }

      //  Example: Search through a simple array
      var data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry", "Mango", "Pineapple"
      ];

      var resultsHTML = "";
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1) {
          resultsHTML += "<p>" + data[i] + "</p>";
        }
      }

      if (resultsHTML === "") {
        resultsDiv.innerHTML = "No results found.";
      } else {
        resultsDiv.innerHTML = resultsHTML;
      }
    }
  </script>

</body>
</html>


<?php
//  Example - this is conceptual and needs database setup!

//  Assume you have a database connection established (e.g., $conn)

if (isset($_SERVER['HTTP_POST']) && $_SERVER['HTTP_POST']['_METHOD'] === 'POST') {
    $searchTerm = $_POST['search_term'];

    // Build your SQL query (sanitize this carefully!)
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Process the results
    $resultsHTML = "";
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $resultsHTML .= "<p>" . $row['name'] . " - " . $row['description'] . "</p>";
        }
    } else {
        $resultsHTML = "Error executing query.";
    }

    // Display the results
    if ($resultsHTML === "") {
      echo "No results found.";
    } else {
      echo $resultsHTML;
    }

    mysqli_close($conn); // Close the database connection
}
?>
