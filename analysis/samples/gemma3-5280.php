

<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
<style>
  /* Basic styling - adjust as needed */
  #search-bar {
    width: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
  }

  #search-button {
    padding: 8px 15px;
    background-color: #4CAF50; /* Green */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  #search-button:hover {
    background-color: #3e8e41;
  }
</style>
</head>
<body>

<h1>Search Example</h1>

<input type="text" id="search-bar" placeholder="Enter search term">
<button id="search-button">Search</button>

<div id="search-results">
  <!-- Results will be displayed here -->
</div>

<script>
  // JavaScript to handle the search
  const searchBar = document.getElementById('search-bar');
  const searchButton = document.getElementById('search-button');
  const searchResults = document.getElementById('search-results');

  searchButton.addEventListener('click', function() {
    const searchTerm = searchBar.value.trim();

    if (searchTerm) {
      // Perform your search logic here
      // Example: Display all items with the search term in the results div.
      // Replace this with your actual data retrieval and filtering logic.
      searchResults.innerHTML = ""; // Clear previous results

      //Simulated Data - Replace with your real data source
      const items = [
          { id: 1, name: "Apple", description: "A red fruit." },
          { id: 2, name: "Banana", description: "A yellow fruit." },
          { id: 3, name: "Orange", description: "A citrus fruit." },
          { id: 4, name: "Grapes", description: "Small, sweet berries." },
        ];


      items.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.innerHTML = `
          <h3>${item.name}</h3>
          <p>${item.description}</p>
        `;
        searchResults.appendChild(itemElement);
      });


    } else {
      searchResults.innerHTML = "<p>Please enter a search term.</p>";
    }
  });
</script>

</body>
</html>


<?php
// Assuming you have a database connection established as $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Your database query to search
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $searchResults = "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      $searchResults .= "<li>" . $row["name"] . " - " . $row["description"] . "</li>";
    }
    $searchResults .= "</ul>";
    echo $searchResults; // Display results in the search_results div
  } else {
    echo "<p>No results found.</p>";
  }
}
?>
