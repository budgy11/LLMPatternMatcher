    <input type="text" name="search_term" placeholder="Enter search term" />
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 0 auto;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: sans-serif;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
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

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      //  **Here's where you would typically perform your search.**
      //  This is a placeholder; replace this with your actual search logic.
      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }
      
      // Example: Display the search term in an alert (for demonstration)
      alert("Searching for: " + searchTerm);
      
      //  You would typically make an AJAX request to your server here
      //  to send the search term to your server-side script for processing.
      //  Example using JavaScript's fetch API:
      // fetch('/search.php?query=' + encodeURIComponent(searchTerm))
      //   .then(response => response.json())
      //   .then(data => {
      //     // Process the search results
      //   })
      //   .catch(error => console.error('Error:', error));
    }
  </script>

</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $searchTerm = $_GET["query"];
    echo "<p>You searched for: " . htmlspecialchars($searchTerm) . "</p>";
}
?>
