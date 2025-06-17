

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    #search-input {
      width: 300px;
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
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results"></ul>

  <script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim();

      if (searchTerm) {
        // Simulate searching (replace with your actual search logic)
        const results = simulateSearch(searchTerm);

        searchResults.innerHTML = ''; // Clear previous results

        if (results.length > 0) {
          results.forEach(result => {
            const listItem = document.createElement('li');
            listItem.textContent = result;
            searchResults.appendChild(listItem);
          });
        } else {
          searchResults.innerHTML = '<p>No results found.</p>';
        }
      }
    });

    // Example simulateSearch function (replace with your actual search logic)
    function simulateSearch(searchTerm) {
      // In a real application, you'd query a database or search through an array.
      const allData = ['Apple', 'Banana', 'Orange', 'Grape', 'Pineapple', 'Strawberry'];
      const filteredData = allData.filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));
      return filteredData;
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established (e.g., $db)

// ... (HTML & CSS from above)

// JavaScript
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');
const searchResults = document.getElementById('search-results');

searchButton.addEventListener('click', function() {
  const searchTerm = searchInput.value.trim();

  if (searchTerm) {
    // Assuming a 'products' table with a 'name' column
    $sql = "SELECT name FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'";
    $result = $db->query($sql);

    searchResults.innerHTML = '';

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        const listItem = document.createElement('li');
        listItem.textContent = $row['name'];
        searchResults.appendChild(listItem);
      }
    } else {
      searchResults.innerHTML = '<p>No results found.</p>';
    }
  }
});
?>
