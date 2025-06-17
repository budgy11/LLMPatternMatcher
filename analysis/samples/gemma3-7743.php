

<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you have a 'products' table with columns like 'id', 'name', 'price'.

// Example database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}


// Function to add a purchase to the database
function addPurchase($productId, $quantity, $userId) {
  global $conn; // Access the database connection

  // Validate inputs (important for security - prevent SQL injection)
  if (!$productId || !$quantity || !$userId) {
    return false; // Invalid input
  }

  $productId = (int)$productId; // Convert to integer for safety
  $quantity = (int)$quantity;
  $userId = (int)$userId;

  // Construct the SQL query
  $sql = "INSERT INTO purchases (product_id, quantity, user_id) VALUES ($productId, $quantity, $userId)";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }
}


// Function to display products (for the shopping cart interface)
function displayProducts($conn) {
    $sql = "SELECT id, name, price FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Available Products:</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
            echo "<p>Price: $" . htmlspecialchars($row["price"]) . "</p>";
            // Add a button to add to cart (or a quantity selection)
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row["id"]) . "'>";
            echo "<input type='number' name='quantity' value='1' min='1' style='width:50px;'>";
            echo "<input type='submit' value='Add to Cart'>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
}

// Example Usage (for demonstration - this would typically be in your shopping cart page)

// 1. Display the products:
displayProducts($conn);

// 2. Handle the form submission (if adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate quantity (important for security - prevent injection)
    if (is_numeric($quantity) && $quantity > 0) {
        if (addPurchase($product_id, $quantity, 1)) { // Assuming user ID 1 for now
            echo "<p>Product added to cart!</p>";
        } else {
            echo "<p>Error adding product to cart.</p>";
        }
    } else {
        echo "<p>Invalid quantity.</p>";
    }
}
?>
