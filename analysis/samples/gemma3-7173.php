
    <form action="" method="post">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Add this to prevent SQL injection - VERY IMPORTANT
$db_connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($db_connection->connect_error) {
  die("Connection failed: " . $db_connection->connect_error);
}


// Function to add a product to the cart
function addToCart($product_id, $quantity, $db_connection) {
  $product_id = mysqli_real_escape_string($db_connection, $product_id); // Prevent SQL injection
  $quantity = mysqli_real_escape_string($db_connection, $quantity);

  // Check if the product already exists in the cart
  $sql = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = $db_connection->query($sql);

  if ($result->num_rows > 0) {
    // Product already exists, update the quantity
    $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
    if ($db_connection->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  } else {
    // Product doesn't exist, add a new row
    $sql = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
    if ($db_connection->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }
}

// Function to get the cart contents
function getCartContents($db_connection) {
  $sql = "SELECT * FROM cart";
  $result = $db_connection->query($sql);

  if ($result->num_rows > 0) {
    $cart_contents = array();
    while ($row = $result->fetch_assoc()) {
      $cart_contents[] = $row;
    }
    return $cart_contents;
  } else {
    return array(); // Return an empty array if the cart is empty
  }
}


// Handle the purchase request (example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if 'submit_purchase' form is submitted
  if (isset($_POST["submit_purchase"])) {
    // Get cart items
    $cart_items = getCartContents($db_connection);

    // Process the purchase (example: print the cart items)
    echo "<h2>Purchase Order</h2>";
    echo "<ul>";
    foreach ($cart_items as $item) {
      echo "<li>Product ID: " . $item["product_id"] . ", Quantity: " . $item["quantity"] . "</li>";
    }
    echo "</ul>";

    // Clear the cart after purchase (optional)
    clearCart($db_connection); // Implement clearCart function below

  }
}


// Optional:  Function to clear the cart (empty the table)
function clearCart($db_connection) {
  $sql = "DELETE FROM cart";
  if ($db_connection->query($sql) === TRUE) {
    echo "<p>Cart cleared successfully!</p>";
  } else {
    echo "<p>Error clearing cart: " . $db_connection->error . "</p>";
  }
}


//  --- Example of a Product Table (create this in your database) ---
/*
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
*/

?>
