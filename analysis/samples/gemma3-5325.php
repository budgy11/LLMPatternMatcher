    </div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic Styling - Adjust as needed */
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
      display: none; /* Initially hidden */
    }
  </style>
</head>
<body>

  <div id="search-container">
    <h1>Search</h1>
    <input type="text" id="search-input" placeholder="Enter search term">
    <button id="search-button">Search</button>

    <div id="search-results">
      <!-- Search results will be displayed here -->
    </div>
  </div>

  <script>
    // Get references to the search input and search results div
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResultsDiv = document.getElementById('search-results');

    // Function to perform the search
    function performSearch() {
      const searchTerm = searchInput.value.toLowerCase(); // Case-insensitive search
      const data = [
        { title: "Apple iPhone 15", description: "The latest iPhone." },
        { title: "Samsung Galaxy S23", description: "A powerful Android phone." },
        { title: "Google Pixel 8", description: "Google's flagship phone." },
        { title: "Banana", description: "A yellow fruit." },
        { title: "Orange", description: "Another delicious fruit." }
      ];

      // Filter the data based on the search term
      const results = data.filter(item => {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the search results
      searchResultsDiv.innerHTML = ''; // Clear previous results

      if (results.length > 0) {
        results.forEach(result => {
          const resultElement = document.createElement('div');
          resultElement.innerHTML = `<strong>${result.title}</strong><br>${result.description}`;
          searchResultsDiv.appendChild(resultElement);
        });
      } else {
        searchResultsDiv.innerHTML = '<p>No results found.</p>';
      }
    }

    // Add event listeners to the search input and button
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        performSearch();
      }
    });

    searchButton.addEventListener('click', performSearch);
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
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
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>


  <script>
    document.getElementById("search-button").addEventListener("click", function() {
      var searchTerm = document.getElementById("search-input").value;

      if (searchTerm.trim() === "") {
        // Do nothing if the input is empty
        return;
      }

      //  Replace this with your actual search logic
      var results = search(searchTerm);

      displayResults(results);
    });

    // Example search function (replace with your real search)
    function search(searchTerm) {
      // This is just a placeholder.  Replace with your database or other search logic
      var results = [
        "Result 1 for " + searchTerm,
        "Result 2 for " + searchTerm,
        "Result 3 for " + searchTerm
      ];
      return results;
    }

    // Function to display search results
    function displayResults(results) {
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      if (results.length === 0) {
        resultsDiv.innerHTML = "<p>No results found.</p>";
        return;
      }

      for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement("p");
        resultItem.textContent = results[i];
        resultsDiv.appendChild(resultItem);
      }
    }
  </script>

</body>
</html>


<?php
// ... (HTML code from above) ...

  function search($searchTerm) {
    // Example database connection (replace with your actual connection)
    $host = 'localhost';
    $username = 'your_username';
    $password = 'your_password';
    $database = 'your_database';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Construct the query (sanitize inputs properly in a real application!)
    $query = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%'"; // Assuming you have a "title" column
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      $results = [];
      while ($row = $result->fetch_assoc()) {
        $results[] = $row['title']; // Adjust this based on your table structure
      }
    } else {
      $results = [];
    }

    $conn->close();
    return $results;
  }
?>
