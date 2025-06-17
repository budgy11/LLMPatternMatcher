
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 20px;
            width: 500px;
        }

        #cartTable {
            width: 100%;
            border-collapse: collapse;
        }

        #cartTable th, #cartTable td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <!-- Product Listing (Replace with your actual product listing) -->
    <h2>Products</h2>
    <div class="products">
      <form method="post">
        <label for="product_1">Product 1 (ID: 1)</label><br>
        <input type="number" id="quantity_1" name="quantity_1" value="1"><br><br>
        <input type="hidden" name="product_id" value="1"><br><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
      </form><br>

      <form method="post">
        <label for="product_2">Product 2 (ID: 2)</label><br>
        <input type="number" id="quantity_2" name="quantity_2" value="1"><br><br>
        <input type="hidden" name="product_id" value="2"><br><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
      </form>
    </div>


</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// --- Database Functions ---

/**
 * Connects to the database.
 *
 * @return mysqli Connection object or null on failure.
 */
function connect_to_db() {
  global $db_host, $db_name, $db_user, $db_password;

  try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }
    return $conn;
  } catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    return null;
  }
}

/**
 * Adds a product to the cart.
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id Product ID.
 * @param int $quantity Quantity to add.
 * @return bool True if successful, false otherwise.
 */
function add_to_cart(mysqli $conn, $product_id, $quantity) {
    $product_id = mysqli_real_escape_string($conn, $product_id); // Sanitize input
    $quantity = mysqli_real_escape_string($conn, $quantity);
    $user_id = $_SESSION['user_id']; // Get user ID from session (assuming you have user authentication)

    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";

    if ($conn->query($query) === TRUE) {
      return true;
    } else {
      error_log("Failed to add to cart: " . $conn->error);
      return false;
    }
}

/**
 * Retrieves cart items for a specific user.
 *
 * @param mysqli $conn Database connection.
 * @param int $user_id User ID.
 * @return array An array of cart items, each with 'id' and 'quantity'
 */
function get_cart_items(mysqli $conn, $user_id) {
  $user_id = mysqli_real_escape_string($conn, $user_id);

  $query = "SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $items = [];
    while ($row = $result->fetch_assoc()) {
      $items[] = [
        'id' => $row['product_id'],
        'quantity' => $row['quantity']
      ];
    }
    return $items;
  } else {
    return [];
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id Product ID.
 * @param int $new_quantity New quantity.
 * @return bool True if successful, false otherwise.
 */
function update_cart_quantity(mysqli $conn, $product_id, $new_quantity) {
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $new_quantity = mysqli_real_escape_string($conn, $new_quantity);
    $user_id = $_SESSION['user_id']; // Get user ID from session

    $query = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Failed to update cart quantity: " . $conn->error);
        return false;
    }
}

/**
 * Deletes a product from the cart.
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id Product ID.
 * @param int $user_id User ID.
 * @return bool True if successful, false otherwise.
 */
function remove_from_cart(mysqli $conn, $product_id, $user_id) {
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);

    $query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Failed to remove from cart: " . $conn->error);
        return false;
    }
}

// --- Purchase Functionality ---

/**
 * Handles the purchase process.
 */
function handle_purchase() {
  global $db_conn; // Access the database connection

  // Get cart items
  $cart_items = get_cart_items($db_conn, $_SESSION['user_id']);

  // Check if the cart is empty
  if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  // Calculate the total price (assuming you have a products table with price)
  $total_price = 0;
  foreach ($cart_items as $item) {
    //  Replace this with your actual product price retrieval
    $product_price = get_product_price($item['id']);
    $total_price += $product_price * $item['quantity'];
  }


  // Display confirmation message
  echo "<p><b>Order Confirmation:</b></p>";
  echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>";

  //  Clear the cart (after successful purchase)
  clear_cart($_SESSION['user_id']);

  //  You'd typically process payment here using a payment gateway.
  //  This is just a placeholder.
  echo "<p>Payment is pending.  Thank you for your order!</p>";
}

/**
 *  Placeholder function to get product price - replace with your actual product lookup.
 * @param int $product_id
 * @return float
 */
function get_product_price(int $product_id){
    //This is a placeholder, replace with your product retrieval logic
    if($product_id == 1){
        return 10.00;
    } else if ($product_id == 2){
        return 25.00;
    } else {
        return 0.00;
    }
}

/**
 * Clears the cart for a user.
 * @param int $user_id
 */
function clear_cart(int $user_id){
    handle_purchase(); // This effectively clears the cart
}
?>
