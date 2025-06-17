    <?php endif; ?>

</body>
</html>


$searchTerm = trim($query);

// Assuming your table is named 'products' and the search column is 'name'
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}

return $results;


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }
    .search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }
    .search-button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
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
      var searchTerm = document.getElementById('searchInput').value;

      //  Example:  You would replace this with your actual data processing logic.
      //  This is just a placeholder to demonstrate the search functionality.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Simulate searching through an array
      var data = ["apple", "banana", "orange", "grape", "kiwi", "mango"];

      var results = [];
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
          results.push(data[i]);
        }
      }

      // Display the results (replace this with your actual display method)
      if (results.length > 0) {
        alert("Search results: " + results.join(", "));
      } else {
        alert("No results found for '" + searchTerm + "'.");
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar with Results List</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }
    .search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    .search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    #resultsList {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" class="search-input" id="searchInput" placeholder="Search...">
    <button class="search-button" onclick="search()">Search</button>
  </div>

  <ul id="resultsList"></ul>

  <script>
    function search() {
      var searchTerm = document.getElementById('searchInput').value;

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      var data = ["apple", "banana", "orange", "grape", "kiwi", "mango"];
      var results = [];

      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
          results.push(data[i]);
        }
      }

      if (results.length > 0) {
        var resultsHTML = "<ul>";
        for (var i = 0; i < results.length; i++) {
          resultsHTML += "<li>" + results[i] + "</li>";
        }
        resultsHTML += "</ul>";
        document.getElementById('resultsList').innerHTML = resultsHTML;
      } else {
        document.getElementById('resultsList').innerHTML = "<li>No results found.</li>";
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    width: 300px;
    margin: 0 auto;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
  }

  #search-input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: none;
    outline: none;
    border-radius: 5px;
  }

  #search-button {
    background-color: #4CAF50; /* Green */
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
  }

  #search-button:hover {
    background-color: #3e8e41;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" id="search-input" placeholder="Search...">
  <button id="search-button">Search</button>
</div>

<script>
  // Get references to the input and button elements
  const searchInput = document.getElementById('search-input');
  const searchButton = document.getElementById('search-button');

  // Add an event listener to the button
  searchButton.addEventListener('click', function() {
    // Get the value entered in the input field
    const searchTerm = searchInput.value;

    // Prevent the form from submitting (which would refresh the page)
    event.preventDefault();

    // Check if the search term is empty
    if (searchTerm.trim() === "") {
      alert("Please enter a search term.");
      searchInput.focus(); // Set focus back to the input field
      return;
    }

    // Perform your search logic here
    // Example: Display the search term in an alert
    alert("You searched for: " + searchTerm);

    //  Replace this with your actual search function
    //  For example, you might call a function to filter your data
    //  based on the searchTerm.

    //  To avoid page reload, you should replace the alert() call
    //  with JavaScript to dynamically update your content.
  });

  // Add a "keydown" event listener to the input field
  // to trigger the search on Enter key press
  searchInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      // Perform the search
      //  Same search logic as above
      event.preventDefault(); // Prevent default form submission

      //  Example:  Call the search function here.
      alert("You searched for: " + searchTerm);
    }
  });

</script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db is your connection object)

// Get the search term from the form submission
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and XSS attacks
$searchTerm = htmlspecialchars(trim($searchTerm)); // Trims whitespace and encodes HTML entities.

//  Escape the search term for SQL queries (important for security)
//  This depends on your database and how you construct your queries.
//  Example using prepared statements (recommended):
//  $stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
//  $stmt->bind_param("s", "%" . $searchTerm . "%");  // s for string
//  $stmt->execute();

//  Alternative (less secure, avoid if possible):  Escaping is only as good as the code that generates the query
//  $searchTerm = str_replace("'", "''", $searchTerm); //  For escaping single quotes in MySQL.  Adapt as needed for your database.

// Perform the search (Example using a hypothetical 'products' table)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = $db->query($sql);

// Check if the search returned any results
if ($result->num_rows > 0) {
  echo '<form action="" method="get">
        <input type="text" name="search" placeholder="Search... " value="' . $searchTerm . '" style="width:300px;">
        <button type="submit">Search</button>
      </form>';

  echo '<br>';

  echo '<ul>';
  // Output the search results
  while ($row = $result->fetch_assoc()) {
    echo '<li>' . $row['name'] . ' - ' . $row['description'] . '</li>';
  }
  echo '</ul>';
} else {
  echo '<p>No results found for "' . $searchTerm . '".</p>';
}

// Close the database connection (Important!)
$result->free();
$db->close();

?>
