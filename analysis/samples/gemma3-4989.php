    <input type="submit" value="Search">
  </form>

</body>
</html>


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
      width: 300px;
      box-sizing: border-box; /* Important for consistent width */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-left: 10px;
    }

    .search-container {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button id="searchButton">Search</button>
  </div>

  <!-- Example of how to process the search results (replace with your actual logic) -->
  <div id="searchResults">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    // Get references to the input and button elements
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');

    // Add an event listener to the button
    searchButton.addEventListener('click', function() {
      // Get the search term from the input field
      const searchTerm = searchTerm.value.toLowerCase(); // Convert to lowercase for case-insensitive search

      // Perform your search logic here
      // Example:  Let's just display a placeholder for now
      if (searchTerm) {
        searchResults.innerHTML = `<p>Searching for: ${searchTerm}</p>`;
      } else {
        searchResults.innerHTML = ""; // Clear the results if the search term is empty
      }

      // Clear the input field after the search
      searchInput.value = "";
    });
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px; /* Adjust as needed */
      margin: 20px auto;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      overflow: hidden;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <script>
    // JavaScript to handle the search functionality
    const searchInput = document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function() {
      // Get the search term
      const searchTerm = this.value.toLowerCase();

      //  Example:  Replace this with your actual logic to search your data
      const items = [
        { name: "Apple", description: "A red fruit" },
        { name: "Banana", description: "A yellow fruit" },
        { name: "Orange", description: "A citrus fruit" },
        { name: "Grape", description: "Small, juicy fruit" }
      ];

      // Filter the items based on the search term
      const filteredItems = items.filter(item => {
        return item.name.toLowerCase().includes(searchTerm);
      });

      // Display the filtered items (example)
      const resultsContainer = document.createElement('div');
      resultsContainer.innerHTML = '<h3>Search Results:</h3>';

      if (filteredItems.length > 0) {
        filteredItems.forEach(item => {
          const listItem = document.createElement('li');
          listItem.textContent = `${item.name}: ${item.description}`;
          resultsContainer.appendChild(listItem);
        });
      } else {
        resultsContainer.innerHTML += '<p>No results found.</p>';
      }

      // Append the results to the document (example)
      document.getElementById('results').innerHTML = ''; // Clear previous results
      document.getElementById('results').appendChild(resultsContainer);
    });
  </script>

  <div id="results"></div>

</body>
</html>


<?php
// PHP code to fetch data from your database
$data = [
  ['name' => 'Apple', 'description' => 'A red fruit'],
  ['name' => 'Banana', 'description' => 'A yellow fruit'],
  ['name' => 'Orange', 'description' => 'A citrus fruit']
];

// Encode the data as JSON
$json_data = json_encode($data);

// Output the JSON data to the HTML
echo "<script>const items = " . $json_data . "</script>";
?>
