

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling - adjust to your needs */
    #search-container {
      margin: 20px;
      width: 300px;
    }

    #search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    #search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    #search-results li {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results">
  </ul>

  <script>
    // Get references to the HTML elements
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    // Add an event listener to the search button
    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim();

      // If the search term is empty, don't do anything
      if (searchTerm === "") {
        return;
      }

      //  Replace this with your actual search logic.
      //  This is just a placeholder example.
      const data = [
        { title: "Apple Pie Recipe", description: "A delicious apple pie recipe." },
        { title: "Chocolate Cake Recipe", description: "A rich chocolate cake recipe." },
        { title: "Strawberry Shortcake", description: "Classic strawberry shortcake recipe." },
        { title: "Banana Bread", description: "Easy banana bread recipe." }
      ];

      // Filter the data based on the search term
      const filteredData = data.filter(item => {
        return item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
               item.description.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Display the search results
      searchResults.innerHTML = ""; // Clear previous results
      if (filteredData.length > 0) {
        filteredData.forEach(item => {
          const listItem = document.createElement('li');
          listItem.textContent = `${item.title} - ${item.description}`;
          searchResults.appendChild(listItem);
        });
      } else {
        searchResults.innerHTML = "<p>No results found.</p>";
      }
    });

    // Add a keypress listener to the input field (for immediate search)
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            searchButton.click(); // Simulate a click
        }
    });
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established
// Replace 'your_database_connection' with your actual connection object

// Example connection (replace with your own)
// $db = new mysqli("localhost", "username", "password", "your_database");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Get the search term from the input field
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$search_term = htmlspecialchars(trim($search_term));


// SQL query construction (example - adjust to your database schema)
$table_name = 'your_table_name'; // Replace with your table name
$column_to_search = 'your_column_name'; // Replace with the column you want to search in
$sql = "SELECT * FROM $table_name WHERE $column_to_search LIKE '%$search_term%'";


// Execute the query
// $result = $db->query($sql);

// Example using mysqli_result (replace with your database's result object)
// if ($result) {
//     if ($result->num_rows > 0) {
//         // Output the results
//         echo '<ul>';
//         while($row = $result->fetch_assoc()) {
//           echo '<li>' . htmlspecialchars($row['your_column_name']) . '</li>'; // Replace 'your_column_name'
//         }
//         echo '</ul>';
//     } else {
//         echo "No results found.";
//     }
// } else {
//     echo "Query failed: " . $db->error;
// }



// ---  Simple HTML Output for the Search Bar ---
?>
