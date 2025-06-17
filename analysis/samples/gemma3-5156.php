

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic Styling - Customize as needed */
    .search-container {
      margin: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter your search term...">
    <button onclick="searchItems()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function searchItems() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchResultsDiv = document.getElementById("searchResults");

      // Clear previous results
      searchResultsDiv.innerHTML = "";

      if (searchTerm.trim() === "") {
        return; // Don't perform the search if the input is empty
      }

      // *** Replace this with your actual data fetching logic ***
      // This is just a placeholder for demonstration.  You'll need to
      // adapt this part to retrieve data from a database, API, or other source.

      var data = [
        { title: "Apple iPhone 14", description: "The latest iPhone..." },
        { title: "Samsung Galaxy S23", description: "A powerful Android phone..." },
        { title: "Google Pixel 7", description: "Google's flagship phone..." },
        { title: "Amazon Echo Dot (5th Gen)", description: "Smart speaker..." },
        { title: "Sony WH-1000XM5 Headphones", description: "Noise-canceling headphones..." }
      ];

      // Filter data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
               item.description.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Display the results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item, index) {
          var resultDiv = document.createElement("div");
          resultDiv.innerHTML = "<strong>" + item.title + "</strong><br>" + item.description;
          resultDiv.style.marginBottom = "10px";  // Add some spacing
          resultDiv.onclick = function() {
            // Handle click event (e.g., display more details)
            alert("Clicked on: " + item.title);
          };
          searchResultsDiv.appendChild(resultDiv);
        });
      } else {
        var noResultsDiv = document.createElement("div");
        noResultsDiv.innerHTML = "<p>No results found for '" + searchTerm + "'</p>";
        searchResultsDiv.appendChild(noResultsDiv);
      }
    }


    // Add an event listener for the input field to trigger the search on each keypress
    document.getElementById("searchInput").addEventListener("keyup", function(event) {
      searchItems();
    });
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established (e.g., mysqli)
// and you have a database table named 'products' with columns 'title' and 'description'

$searchTerm = $_GET['search']; // Get the search term from the URL

// Example using mysqli (replace with your actual database connection)
$conn = new mysqli("localhost", "username", "password", "database_name");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Construct the SQL query
$sql = "SELECT title, description FROM products WHERE title LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR description LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

$conn->close();

// Pass the data to the HTML file
echo "<script>
  var data = " . json_encode($data) . ";
</script>";
?>
