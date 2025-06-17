
</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// $db = new mysqli("localhost", "username", "password", "database_name");
// Check connection
// if ($db->connect_error) {
//  die("Connection failed: " . $db->connect_error);
// }

// Example database table structure (adjust to your actual table)
// CREATE TABLE products (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   name VARCHAR(255) NOT NULL,
//   description TEXT
// );

//  Search Bar HTML
echo '<input type="text" id="searchInput" placeholder="Search products...">';

// JavaScript to handle the search
echo '<script>';
echo 'document.getElementById("searchInput").addEventListener("keyup", function() {';
echo '  var searchTerm = this.value.toLowerCase();';
echo '  document.body.innerHTML = ""; // Clear previous results';
echo '  // Replace this with your actual search logic';
echo '  // Example: Filter products based on searchTerm';
echo '  // var results = filterProducts(searchTerm);';
echo '  //  If results is not empty, display them here';
echo '  //  Example: Display results in a div with id="searchResults"';
echo '});';
echo '</script>';


//  Example PHP function to filter products (replace with your logic)
function filterProducts($searchTerm) {
  // Replace this with your actual database query
  // This is just a placeholder for demonstration
  $products = [
    ['id' => 1, 'name' => 'Laptop', 'description' => 'High-performance laptop'],
    ['id' => 2, 'name' => 'Mouse', 'description' => 'Wireless mouse'],
    ['id' => 3, 'name' => 'Keyboard', 'description' => 'Ergonomic keyboard'],
    ['id' => 4, 'name' => 'Headphones', 'description' => 'Noise-cancelling headphones'],
  ];

  $results = [];
  foreach ($products as $product) {
    if (str_contains(strtolower($product['name']), $searchTerm) ||
        str_contains(strtolower($product['description']), $searchTerm)) {
      $results[] = $product;
    }
  }

  return $results;
}
?>
