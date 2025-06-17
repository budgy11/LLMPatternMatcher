
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling - Customize as needed */
    #search-container {
      margin: 20px;
      width: 300px;
    }

    #search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding doesn't add to width */
    }

    #search-button {
      background-color: #4CAF50; /* Green */
      color: white;
      padding: 10px 20px;
      border: none;
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
      // Get the search term from the input field
      var searchTerm = document.getElementById('search-input').value;

      // Prevent the form from submitting (if there's a form)
      if (searchTerm) {
        // Do something with the search term.  This is where you'd
        // typically call a function to search your data.
        console.log("Searching for: " + searchTerm);

        // Example:  You might want to display the search term in a div.
        //  This is just for demonstration.
        var searchResultsDiv = document.getElementById("search-results");
        if (searchResultsDiv) {
          searchResultsDiv.innerHTML = "<p>You searched for: " + searchTerm + "</p>";
        }

        // You could also redirect the user to a results page, or AJAX the data.

      } else {
        // The search input is empty, so do nothing.
        alert("Please enter a search term.");
      }
    });
  </script>

</body>
</html>


<?php

// Sample data (replace this with your actual data source)
$items = [
    'apple',
    'banana',
    'orange',
    'grape',
    'strawberry',
    'watermelon',
    'pineapple',
    'mango',
];

// Check if the search term is provided
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Sanitize input!
    $results = [];

    // Loop through the items and check if they contain the search term (case-insensitive)
    foreach ($items as $item) {
        if (stripos($item, $searchTerm) !== false) { //stripos is case-insensitive
            $results[] = $item;
        }
    }

    // Display the results
    if (empty($results)) {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    } else {
        echo "<h2>Search Results for '" . $searchTerm . "'</h2>";
        echo "<ol>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ol>";
    }

} else {
    // Display the search bar if no search term is provided
    echo "<form method='get' action=''>"; //  'action' points to the same script
    echo "<label for='search'>Search:</label><br>";
    echo "<input type='text' id='search' name='search' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
}

?>
