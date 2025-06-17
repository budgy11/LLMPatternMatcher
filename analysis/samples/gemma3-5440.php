  </div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* Basic Styling - Adjust as needed */
    #search-container {
      margin: 20px;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      overflow: hidden; /* Important for clip: rect() to work properly */
    }

    #search-input {
      width: 80%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box; /* Important for width calculation */
    }

    #search-button {
      background-color: #4CAF50; /* Green */
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-left: 5px;
    }

    #search-button:hover {
      background-color: #3e8e41;
    }

  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <script>
    // JavaScript to handle the search button click
    document.getElementById('search-button').addEventListener('click', function() {
      var searchTerm = document.getElementById('search-input').value;

      // Perform your search logic here.  This is just an example.
      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Echo the search term back to the page
      // You'd replace this with your actual search function
      alert("You searched for: " + searchTerm);

      // Example:  Sending the search term to a PHP script
      // fetch('your_php_script.php?search_term=' + encodeURIComponent(searchTerm))
      //   .then(response => response.json())
      //   .then(data => {
      //     // Process the data from your PHP script
      //     console.log(data);
      //   })
      //   .catch(error => console.error('Error:', error));
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume you have a connection variable called $conn

// Get the search term from the search bar
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (very important to prevent SQL injection)
$search_term = htmlspecialchars(trim($search_term));

// Escape the search term for use in the SQL query
$search_term = $conn->real_escape_string($search_term);  //  Use real_escape_string for mysqli

//  Alternatively,  use prepared statements for even better security:
//  $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
//  $stmt->bind_param("s", $search_term, $search_term);
//  $stmt->execute();



//  Example:  Searching a table named 'products' with columns 'name' and 'description'

$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";

$result = $conn->query($sql);

?>
