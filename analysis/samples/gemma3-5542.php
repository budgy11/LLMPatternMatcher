
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
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px; /* Adjust width as needed */
    }

    button {
      padding: 8px 15px;
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
    <h1>Simple Search</h1>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here
      // This is just a placeholder - replace with your actual search implementation
      if (searchTerm.length > 0) {
        alert("Searching for: " + searchTerm);  // Replace with your actual search code
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


     <?php
     $items = ["apple", "banana", "orange", "grape"];
     $searchTerm = $_GET["search"] ?? ""; // Get search term from URL (or empty if none)

     if ($searchTerm) {
       $results = [];
       foreach ($items as $item) {
         if (stripos($item, $searchTerm)) { // Case-insensitive search
           $results[] = $item;
         }
       }
       if (count($results) > 0) {
         echo "<ul>";
         foreach ($results as $result) {
           echo "<li>" . $result . "</li>";
         }
         echo "</ul>";
       } else {
         echo "<p>No results found.</p>";
       }
     } else {
       echo "<p>Please enter a search term.</p>";
     }
     ?>
