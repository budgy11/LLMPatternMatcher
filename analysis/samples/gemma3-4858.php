
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
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }

    #search-input {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
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
      border-radius: 5px;
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
    // Get references to the search input and results list
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResultsList = document.getElementById('search-results');

    // Sample data (replace with your actual data source)
    const data = [
      { title: "PHP Tutorial", description: "Learn PHP basics." },
      { title: "JavaScript Guide", description: "A comprehensive JavaScript tutorial." },
      { title: "HTML5 Reference", description: "The latest HTML5 specifications." },
      { title: "CSS Styling", description: "Learn how to style your web pages." },
      { title: "React Framework", description: "Introduction to ReactJS." }
    ];

    // Function to handle the search
    function performSearch() {
      const searchTerm = searchInput.value.toLowerCase();
      const results = data.filter(item => {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the search results
      searchResultsList.innerHTML = ''; // Clear previous results
      if (results.length > 0) {
        results.forEach(result => {
          const listItem = document.createElement('li');
          listItem.textContent = `${result.title} - ${result.description}`;
          searchResultsList.appendChild(listItem);
        });
      } else {
        searchResultsList.innerHTML = 'No results found.';
      }
    }

    // Add event listeners to the input and button
    searchInput.addEventListener('keyup', performSearch); // Trigger on key release
    searchButton.addEventListener('click', performSearch); // Trigger on button click
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin-bottom: 10px;
  }

  input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Important for consistent sizing */
  }

  .search-results {
    margin-top: 20px;
    border: 1px solid #ccc;
    padding: 10px;
  }
</style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <div id="searchResults" class="search-results" style="display: none;">
    <h2>Search Results:</h2>
    <ul id="resultsList"></ul>
  </div>


  <script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultsList = document.getElementById('resultsList');

    searchInput.addEventListener('keyup', function(event) {
      const searchTerm = searchTerm.toLowerCase(); //Convert to lowercase for case-insensitive search

      // Sample data (replace with your actual data source)
      const data = [
        { title: 'Apple iPhone 13', description: 'The latest iPhone with a great camera.' },
        { title: 'Samsung Galaxy S22', description: 'A powerful Android phone.' },
        { title: 'Google Pixel 6', description: 'The best camera phone.' },
        { title: 'MacBook Air M2', description: 'A lightweight and powerful laptop.' },
        { title: 'Microsoft Surface Pro 9', description: 'A versatile 2-in-1 tablet.' }
      ];

      // Filter the data based on the search term
      const filteredData = data.filter(item => {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the search results
      if (filteredData.length > 0) {
        searchResults.style.display = 'block';
        resultsList.innerHTML = ''; // Clear previous results

        filteredData.forEach(item => {
          const listItem = document.createElement('li');
          listItem.textContent = `${item.title} - ${item.description}`;
          resultsList.appendChild(listItem);
        });
      } else {
        searchResults.style.display = 'none'; // Hide if no results
      }
    });
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established as $conn

$searchTerm = $_GET['search']; // Get the search term from the URL

if ($searchTerm) {
    $sql = "SELECT * FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Process the results and display them in HTML
        while($row = $result->fetch_assoc()) {
            // Display the row data (title, description, etc.)
            echo "<div>" . $row['title'] . " - " . $row['description'] . "</div>";
        }
    } else {
        echo "No results found.";
    }
}
?>
