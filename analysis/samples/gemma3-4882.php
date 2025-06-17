

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      width: 80%;
      margin: 20px auto;
      text-align: center;
    }

    #search-input {
      width: 70%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    #search-results {
      margin-top: 20px;
      display: none; /* Initially hide the results */
    }
  </style>
</head>
<body>

  <div id="search-container">
    <h1>Simple Search</h1>
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>

    <div id="search-results">
      <!-- Search results will be displayed here -->
    </div>
  </div>

  <script>
    // Get references to the elements
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    // Function to handle the search
    function performSearch() {
      const searchTerm = searchTerm.value.toLowerCase(); // Convert to lowercase for case-insensitive search

      // Clear existing results
      searchResults.innerHTML = '';

      // Example data (replace with your actual data source)
      const data = [
        { title: 'Apple iPhone 14', description: 'The latest iPhone.' },
        { title: 'Samsung Galaxy S23', description: 'A powerful Android phone.' },
        { title: 'Google Pixel 7', description: 'Google\'s flagship phone.' },
        { title: 'Red Hat Linux', description: 'A popular open-source operating system.' },
        { title: 'PHP Tutorial', description: 'Learn to code with PHP.' }
      ];

      // Filter the data based on the search term
      const filteredData = data.filter(item =>
        item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm)
      );

      // Display the results
      if (filteredData.length > 0) {
        filteredData.forEach(item => {
          const resultItem = document.createElement('div');
          resultItem.innerHTML = `<strong>${item.title}</strong><br>${item.description}`;
          searchResults.appendChild(resultItem);
        });
      } else {
        // No results found message
        const noResultsMessage = document.createElement('p');
        noResultsMessage.textContent = 'No results found.';
        searchResults.appendChild(noResultsMessage);
      }
    }

    // Add event listener to the search button
    searchButton.addEventListener('click', performSearch);

    // Add event listener to the input field (for hitting Enter key)
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        performSearch();
      }
    });
  </script>

</body>
</html>


<?php

// Configuration
$search_term = ""; // Default search term
$results = []; // Array to store search results

// This is just an example data source. Replace this with your actual data source.
$all_data = [
    "apple", "banana", "orange", "grapefruit", "kiwi",
    "red apple", "green apple", "yellow banana", "sweet grape",
    "apple pie", "banana bread", "orange juice"
];


// Function to handle the search
function searchData($searchTerm, $data) {
    $results = [];
    foreach ($data as $item) {
        // Case-insensitive comparison
        if (stripos($item, $searchTerm) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Handle form submission (if any)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = trim($_POST["search_term"]);
    $results = searchData($search_term, $all_data);
}

?>
