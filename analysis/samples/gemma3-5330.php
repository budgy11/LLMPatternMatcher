
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-results {
      margin-top: 20px;
      border: 1px solid #ccc;
      padding: 10px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

  <h1>Search</h1>

  <form action="" method="post">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// Example using mysqli:
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// --- Example Data (replace with your actual database results) ---
// This simulates retrieving data from a table.
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A delicious red fruit.'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit, great for potassium.'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit, full of Vitamin C.'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, sweet berries.'],
    ['id' => 5, 'name' => 'Strawberry', 'description' => 'Red, juicy berries, popular for desserts.'],
];

// --- Search Bar HTML ---
?>
