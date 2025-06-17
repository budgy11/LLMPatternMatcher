

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px; /* Adjust width as needed */
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    #results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    #results li {
      margin-bottom: 10px;
      padding: 10px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <h1>Simple Search Bar</h1>

  <input type="text" id="searchInput" placeholder="Search...">
  <button id="searchButton">Search</button>

  <ul id="results">
    <!-- Search results will be displayed here -->
  </ul>

  <script>
    // Sample data for demonstration purposes
    const data = [
      "Apple", "Banana", "Orange", "Grape", "Strawberry", "Kiwi", "Mango"
    ];

    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const resultsList = document.getElementById("results");

    searchButton.addEventListener("click", function() {
      const searchTerm = searchInput.value.toLowerCase(); // Convert to lowercase for case-insensitive search

      // Clear previous results
      resultsList.innerHTML = "";

      // Filter the data based on the search term
      const filteredData = data.filter(item => item.toLowerCase().includes(searchTerm));

      // Display the results
      if (filteredData.length > 0) {
        filteredData.forEach(item => {
          const listItem = document.createElement("li");
          listItem.textContent = item;
          resultsList.appendChild(listItem);
        });
      } else {
        const noResults = document.createElement("li");
        noResults.textContent = "No results found.";
        resultsList.appendChild(noResults);
      }
    });

    // Add a keypress event listener for auto-search
    searchInput.addEventListener("keypress", function(event) {
      if (event.key === "Enter") {
        this.focus(); // Keep the focus on the input field
        this.blur(); // Remove the focus after pressing Enter
        searchButton.click(); // Simulate button click
      }
    });
  </script>

</body>
</html>


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
      font-size: 16px;
    }
    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .search-results {
      margin-top: 20px;
    }
    .search-result {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div class="search-results" id="searchResults" style="display: none;">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchResults = document.getElementById("searchResults");

      // Clear previous results
      searchResults.innerHTML = "";

      if (searchTerm.trim() === "") {
        return; // Don't perform search if the input is empty
      }

      // Simulate data (replace with your actual data retrieval)
      var data = [
        { title: "Apple iPhone 15", description: "The latest iPhone...", link: "#iphone15" },
        { title: "Samsung Galaxy S23", description: "Amazing camera and performance...", link: "#s23" },
        { title: "Google Pixel 8", description: "Best camera in its class...", link: "#pixel8" },
        { title: "Laptop", description: "Powerful laptop for work and play.", link: "#laptop" },
        { title: "Headphones", description: "High-quality noise-canceling headphones.", link: "#headphones" }
      ];

      // Filter data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
               item.description.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Display the search results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var resultDiv = document.createElement("div");
          resultDiv.className = "search-result";
          resultDiv.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
            <a href="${item.link}" target="_blank">More Info</a>
          `;
          searchResults.appendChild(resultDiv);
        });
      } else {
        // Display a "No results found" message
        var noResultsDiv = document.createElement("p");
        noResultsDiv.innerHTML = "No results found for: " + searchTerm;
        searchResults.appendChild(noResultsDiv);
      }
    }
  </script>

</body>
</html>


<?php
// Assume you have a database connection established
$searchTerm = $_GET['search']; // Get the search term from the URL

// Replace with your actual database query
$result = mysqli_query($conn, "SELECT * FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'");

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

// ... (rest of the JavaScript code, using $data instead of the array)
?>
