    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById('searchInput').value;
      if (searchTerm.trim() !== "") { // Check if search term is not empty after trimming whitespace
        window.location.href = "?search=" + encodeURIComponent(searchTerm);
      }
    }
  </script>

</body>
</html>


$searchTerm = htmlspecialchars($_GET['search']);

$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);


<?php

// Assuming you have a database connection established (e.g., mysqli)
// $conn = mysqli_connect("your_host", "your_user", "your_password", "your_database");
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }

//  Simulating a database table for demonstration.  Replace with your actual database table.
//  This is just for testing when you don't have a live database connection.
$data = [
    ['id' => 1, 'name' => 'Apple'],
    ['id' => 2, 'name' => 'Banana'],
    ['id' => 3, 'name' => 'Orange'],
    ['id' => 4, 'name' => 'Grapes'],
    ['id' => 5, 'name' => 'Strawberry']
];


// Function to search for items
function searchItems($searchTerm, $data) {
    $results = [];
    foreach ($data as $item) {
        // Convert both search term and item name to lowercase for case-insensitive search
        $searchTermLower = strtolower($searchTerm);
        $itemNameLower = strtolower($item['name']);

        // Check if the search term is present in the item name
        if (strpos($itemNameLower, $searchTermLower) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}


// Get the search term from the form (or URL if using GET)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from GET request, default to empty string

// Perform the search
$searchResults = searchItems($searchTerm, $data);

?>
