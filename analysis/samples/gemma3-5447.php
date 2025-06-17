

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Important for consistent width */
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("searchResults");

      // Example data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 15", description: "The latest iPhone" },
        { title: "Samsung Galaxy S23", description: "A powerful Android phone" },
        { title: "Google Pixel 8", description: "Google's flagship phone" },
        { title: "Amazon Echo Dot", description: "A smart speaker" },
        { title: "JavaScript Tutorial", description: "Learn JavaScript programming" }
      ];

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Filter the data based on the search term
      for (var i = 0; i < data.length; i++) {
        if (data[i].title.toLowerCase().includes(searchTerm) ||
            data[i].description.toLowerCase().includes(searchTerm)) {
          var listItem = document.createElement("div");
          listItem.innerHTML = `<p>${data[i].title}</p>
                                <p>${data[i].description}</p>`;
          resultsDiv.appendChild(listItem);
        }
      }

      // If no results are found
      if (resultsDiv.innerHTML === "") {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar in PHP</title>
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
</style>
</head>
<body>

<div class="search-container">
  <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
