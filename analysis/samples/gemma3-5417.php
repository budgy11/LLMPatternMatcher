    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 0 auto;
      margin-top: 20px;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 10px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
      margin-left: 5px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchBox" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>


  <script>
    function search() {
      // Get the value from the search box
      var searchTerm = document.getElementById("searchBox").value;

      // Check if the search term is empty
      if (searchTerm === "") {
        alert("Please enter a search term.");
        return;
      }

      // **Replace this with your actual search logic**
      // This is just a placeholder example.  You'll likely want to
      // make an AJAX request to your server to perform the search
      // against your data.

      // Example:  Display a simple message
      alert("Searching for: " + searchTerm);

      // Simulate a search - you'd replace this with your data retrieval code.
      // For example, fetch data from a database or an array.

    }
  </script>

</body>
</html>


<?php
// Assuming you have a function called `searchData()` that performs the search
// and returns an array of results.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Replace this with your actual search logic
  $results = searchData($searchTerm);

  echo json_encode($results); // Send the results as JSON
}
?>
