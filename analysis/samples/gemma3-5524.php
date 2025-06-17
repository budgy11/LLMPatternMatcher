
</body>
</html>


function searchDatabase($term) {
  global $conn; // Assuming you have a database connection variable

  $query = "SELECT * FROM products WHERE name LIKE '%" . $conn->real_escape_string($term) . "%'"; // Use real_escape_string for security
  $result = $conn->query($query);

  $products = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $products[] = $row['name']; // Or however you want to display the product name
    }
  }
  return $products;
}


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace these placeholders with your actual connection details

// $servername = "your_servername";
// $username = "your_username";
// $password = "your_password";
// $dbname = "your_database_name";

// $conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// Function to handle the search query
function searchDatabase($query) {
    // Replace this with your actual database query logic
    // This is just a placeholder example using a dummy database
    $results = [];

    // **Replace this with your database query logic**
    // This is a placeholder that returns dummy data based on the search term
    $searchTerm = trim($query);

    if (empty($searchTerm)) {
        return $results; // Return empty array if search term is empty
    }

    // Dummy data (replace with your database query)
    $data = [
        ['id' => 1, 'name' => 'Apple iPhone 15 Pro Max'],
        ['id' => 2, 'name' => 'Samsung Galaxy S23 Ultra'],
        ['id' => 3, 'name' => 'Google Pixel 8 Pro'],
        ['id' => 4, 'name' => 'OnePlus 12'],
        ['id' => 5, 'name' => 'Xiaomi 14 Pro'],
    ];

    foreach ($data as $item) {
        if (strpos($item['name'], $searchTerm) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}


// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchDatabase($searchTerm);

?>
