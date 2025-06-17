

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
      width: 300px;
      box-sizing: border-box;
    }
    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
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

      // **Important: Replace this with your actual search logic**
      // This is just a placeholder to demonstrate how you might filter data.
      var data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry",
        "Kiwi", "Mango", "Pineapple", "Watermelon"
      ];

      var results = [];
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
          results.push(data[i]);
        }
      }

      // Display the results (e.g., in a list)
      var resultsList = document.createElement("ul");
      for (var j = 0; j < results.length; j++) {
        var listItem = document.createElement("li");
        listItem.appendChild(document.createTextNode(results[j]));
        resultsList.appendChild(listItem);
      }

      // You can display the resultsList anywhere on the page (e.g., below the search bar)
      document.getElementById("resultsContainer").innerHTML = resultsList; // Add a resultsContainer element to your HTML
    }
  </script>

  <!-- Add a container for the results -->
  <div id="resultsContainer"></div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
  <style> /* ... (same CSS as above) ... */ </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search Products...">
    <button onclick="search()">Search</button>
  </div>

  <div id="resultsContainer"></div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // **Important: Replace this with your actual database connection and query**
      // This is just a placeholder.

      // Example Database Connection (replace with your credentials)
      $servername = "localhost";
      $username = "your_username";
      $password = "your_password";
      $dbname = "your_database";

      // Construct the SQL query (using LIKE for partial matching)
      var query = "SELECT * FROM products WHERE name LIKE '%" + searchTerm + "%'";  // Use LIKE operator

      // Create a MySQLi connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Execute the query
      $result = $conn->query($query);

      // Display the results
      var resultsList = document.createElement("ul");
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          var listItem = document.createElement("li");
          listItem.appendChild(document.createTextNode(JSON.stringify($row))); // Convert to string for display
          resultsList.appendChild(listItem);
        }
      }

      document.getElementById("resultsContainer").innerHTML = resultsList;

      $conn->close();
    }
  </script>

</body>
</html>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulated Product Data - Replace with your actual data) ---

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15],
];


// 1. Add an item to the cart
addToCart(1, 'T-Shirt', 20, 2);
echo "Added 2 T-Shirts to the cart.
";

// 2. Add another item
addToCart(2, 'Jeans', 50, 1);
echo "Added 1 Jeans to the cart.
";

// 3. Update the quantity of the T-Shirt
updateQuantity(1, 5); // Increase the quantity of T-Shirts to 5
echo "Updated T-Shirt quantity to 5.
";

// 4. Get the cart contents
$cart = getCartContents();
echo "Current Cart Contents:
";
print_r($cart);


// 5.  Remove an item
removeCartItem(2); // Remove the Jeans
echo "Removed Jeans from the cart.
";

// Display updated cart contents
$cart = getCartContents();
print_r($cart);

?>
