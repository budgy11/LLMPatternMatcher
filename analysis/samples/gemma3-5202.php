
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic Styling - Feel free to customize */
    .search-container {
      margin: 20px;
    }

    #search-input {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    #search-button {
      padding: 10px 20px;
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
      margin-bottom: 5px;
      padding: 10px;
      border: 1px solid #eee;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>

    <ul id="search-results"></ul>
  </div>

  <script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.toLowerCase(); // Case-insensitive search

      if (searchTerm === "") {
        searchResults.innerHTML = ""; // Clear results if input is empty
        return;
      }

      // Sample data - replace with your data source
      const data = [
        { title: "Apple iPhone 14", description: "The latest iPhone!" },
        { title: "Samsung Galaxy S23", description: "Powerful Android phone." },
        { title: "Google Pixel 7", description: "Great camera and AI features." },
        { title: "PHP Tutorial", description: "Learn PHP programming." },
        { title: "JavaScript Basics", description: "Introduction to JavaScript." }
      ];

      searchResults.innerHTML = ""; // Clear previous results

      data.forEach(item => {
        if (item.title.toLowerCase().includes(searchTerm)) {
          const listItem = document.createElement('li');
          listItem.textContent = `${item.title} - ${item.description}`;
          searchResults.appendChild(listItem);
        }
      });
    });
  </script>

</body>
</html>


<?php

// Assuming you have a form to input the search term
?>
