
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 0 auto; /* Center the search bar */
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: sans-serif;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button id="searchButton">Search</button>
  </div>

  <script>
    // Get references to the search input and button
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    // Add an event listener for the search button click
    searchButton.addEventListener('click', function() {
      // Get the search term
      const searchTerm = searchInput.value.trim();

      // Prevent submission of the form if the search term is empty
      if (searchTerm === "") {
        alert("Please enter a search term.");
        return;
      }

      // Do something with the search term (e.g., make an AJAX request)
      console.log("Search term:", searchTerm);

      // Example:  Display the search term on the page
      document.getElementById('searchResults').innerHTML = "You searched for: " + searchTerm;

      //  More advanced:  Send data to a server-side script
      //  fetch('/your_server_script.php', {
      //    method: 'POST',
      //    headers: {
      //      'Content-Type': 'application/x-www-form-urlencoded'
      //    },
      //    body: 'term=' + encodeURIComponent(searchTerm)
      //  })
      //  .then(response => response.json())
      //  .then(data => {
      //    // Process the response from the server
      //    console.log("Server response:", data);
      //  })
      //  .catch(error => {
      //    console.error("Error:", error);
      //  });
    });
  </script>

  <!--  Optional:  A place to display search results  -->
  <div id="searchResults" style="margin-top: 10px;"></div>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// For simplicity, let's assume a database connection named $conn

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize and validate the search term (important!)
  $searchTerm = htmlspecialchars(trim($searchTerm)); // Prevents XSS

  // Prevent empty search terms
  if (empty($searchTerm)) {
    echo "<p>Please enter a search term.</p>";
  } else {
    // Perform the search (replace with your actual database query)
    // This is a placeholder example - adapt to your table and column names
    $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'"; 

    // Execute the query (replace with your database interaction)
    // Example using mysqli
    $result = mysqli_query($conn, $sql);

    if ($result) {
      // Display the results
      echo "<h2>Search Results for: " . $searchTerm . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>ID</th><th>Column 1</th><th>Column 2</th></tr>"; // Adapt headers

      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>"; // Replace with your actual column names
        echo "<td>" . $row["column1"] . "</td>";
        echo "<td>" . $row["column2"] . "</td>";
        echo "</tr>";
      }

      echo "</table>";

      // Close the database connection (good practice)
      mysqli_close($conn);
    } else {
      echo "<p>Error executing query: " . mysqli_error($conn) . "</p>";
    }
  }
}
?>
