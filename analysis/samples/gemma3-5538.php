

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
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

  <ul id="search-results"></ul>

  <script>
    // Get references to the elements
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    // Function to handle the search
    function performSearch() {
      const searchTerm = searchInput.value.toLowerCase(); // Convert to lowercase for case-insensitive search

      // Simulate a search (replace with your actual search logic)
      const data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry", "Pineapple", "Mango", "Watermelon"
      ];

      const results = data.filter(item => item.toLowerCase().includes(searchTerm));

      // Display the results
      searchResults.innerHTML = ''; // Clear previous results

      if (results.length > 0) {
        results.forEach(item => {
          const listItem = document.createElement('li');
          listItem.textContent = item;
          searchResults.appendChild(listItem);
        });
      } else {
        searchResults.innerHTML = '<p>No results found.</p>';
      }
    }

    // Add event listeners
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        performSearch();
      }
    });
  </script>

</body>
</html>


<?php
// ... (HTML code from above) ...

  // ... (JavaScript code from above) ...

  function performSearch() {
    const searchTerm = sanitize_input(searchInput.value.toLowerCase()); //Sanitize input
    // Assuming you have a database connection established
    $db = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
    if (!$db) {
      die('Connection failed: ' . mysqli_connect_error());
    }

    $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($db, $sql);

    mysqli_close($db);

    // ... (rest of the search logic) ...
  }
  //Sanitize input
  function sanitize_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
  }
?>
