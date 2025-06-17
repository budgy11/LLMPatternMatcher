    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="shipping_address">Shipping Address:</label>
    <input type="text" id="shipping_address" name="shipping_address" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
    </select><br><br>

    <button type="submit">Purchase</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// --- Functions ---

/**
 * Connects to the database.
 *
 * @return mysqli Connection object or null on failure.
 */
function connect_to_db() {
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

/**
 * Adds a product to the shopping cart.
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id The ID of the product being purchased.
 * @param int $quantity The quantity of the product being purchased.
 * @return bool True on success, false on failure.
 */
function add_to_cart(mysqli $conn, $product_id, $quantity) {
  // Validate product ID and quantity
  if (!is_numeric($product_id) || $quantity <= 0) {
    return false;
  }

  // Sanitize the input to prevent SQL injection
  $product_id = mysqli_real_escape_string($conn, $product_id);


  // Check if the product already exists in the cart.
  $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Product already exists, update the quantity
    $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
    if (mysqli_query($conn, $query)) {
      return true;
    } else {
      return false;
    }
  } else {
    // Product doesn't exist in the cart, add it.
    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
    if (mysqli_query($conn, $query)) {
      return true;
    } else {
      return false;
    }
  }
}

/**
 * Retrieves the items in the shopping cart.
 *
 * @param mysqli $conn Database connection.
 * @return array An array of product details (name, price, quantity) or an empty array if no items are in the cart.
 */
function get_cart_items(mysqli $conn) {
  $query = "SELECT p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
  $result = mysqli_query($conn, $query);

  $cart_items = array();
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $cart_items[] = $row;
    }
  }
  return $cart_items;
}

/**
 * Removes a product from the shopping cart.
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id The ID of the product to remove.
 * @return bool True on success, false on failure.
 */
function remove_from_cart(mysqli $conn, $product_id) {
  if (!is_numeric($product_id)) {
    return false;
  }

  $product_id = mysqli_real_escape_string($conn, $product_id);
  $query = "DELETE FROM cart WHERE product_id = '$product_id'";
  return mysqli_query($conn, $query);
}


/**
 * Clears the entire shopping cart.
 *
 * @param mysqli $conn Database connection.
 * @return bool True on success, false on failure.
 */
function clear_cart(mysqli $conn) {
  $query = "TRUNCATE TABLE cart";
  return mysqli_query($conn, $query);
}


// --- Example Usage (Handle the Purchase Request) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if a "clear cart" button was clicked.
  if (isset($_POST['clear_cart'])) {
    if (clear_cart($conn)) {
      echo "<p>Cart cleared successfully!</p>";
    } else {
      echo "<p>Error clearing cart.</p>";
    }
  } else {
    // Handle adding to cart
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
      $product_id = $_POST['product_id'];
      $quantity = $_POST['quantity'];
      if (add_to_cart($conn, $product_id, $quantity)) {
        echo "<p>Product added to cart!</p>";
      } else {
        echo "<p>Error adding product to cart.</p>";
      }
    }
  }
}

// --- Display the Cart ---
$cart_items = get_cart_items($conn);

if (count($cart_items) > 0) {
  echo "<h2>Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
