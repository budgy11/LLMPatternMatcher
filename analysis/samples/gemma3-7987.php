

<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "shop_db";
$db_user = "your_username";
$db_password = "your_password";

// -------------------------------------------------
//  Purchase Functionality
// -------------------------------------------------

/**
 * Processes a purchase.
 *
 * @param array $cart_items  Array of product IDs and quantities in the cart.
 * @return string  Message indicating success or failure.
 */
function processPurchase(array $cart_items) {
  // 1. Validate Cart Items (Check for valid product IDs and quantities)
  foreach ($cart_items as $product_id => $quantity) {
    if (!isset($product_id) || !is_numeric($product_id) || $quantity <= 0) {
      return "Invalid cart item(s). Please check your cart.";
    }
  }

  // 2. Database Interaction - Insert Order Details
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable exception handling

    $stmt = $pdo->prepare("
      INSERT INTO orders (user_id, total_amount)
      VALUES (:user_id, :total_amount)
    ");
    $user_id = 1; // Replace with the actual user ID.  Could get from session/auth.
    $total_amount = calculateTotal($cart_items); // Get total from cart
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->execute();

    $order_id = $pdo->lastInsertId();

    // 3. Insert Order Items
    foreach ($cart_items as $product_id => $quantity) {
      $stmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, quantity)
        VALUES (:order_id, :product_id, :quantity)
      ");
      $stmt->bindParam(':order_id', $order_id);
      $stmt->bindParam(':product_id', $product_id);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->execute();
    }

    // 4. Clear Cart (Remove items from the cart)
    clearCart($product_id => $quantity);

    return "Purchase successful! Order ID: $order_id";

  } catch (PDOException $e) {
    return "Error: " . $e->getMessage();
  }
}


/**
 * Calculates the total amount for the cart.
 *
 * @param array $cart_items Array of product IDs and quantities in the cart.
 * @return float The total amount.
 */
function calculateTotal(array $cart_items) {
  $total = 0;
  // This is a placeholder - replace with your actual product price retrieval
  $product_prices = [
      1 => 25.00, // Example: Product ID 1 - $25.00
      2 => 15.50, // Example: Product ID 2 - $15.50
  ];

  foreach ($cart_items as $product_id => $quantity) {
    if (isset($product_prices[$product_id])) {
      $total_for_product = $product_prices[$product_id] * $quantity;
      $total += $total_for_product;
    } else {
      // Handle the case where product_id is not found.
      return "Error: Product not found";
    }
  }
  return round($total, 2); // Round to 2 decimal places
}

/**
 * Clears the shopping cart. This is a placeholder -  Implement your cart clearing logic here.
 * @param array $cart_items  Array of product IDs and quantities.
 */
function clearCart(array $cart_items) {
  // Placeholder: Implement your logic to clear the shopping cart.
  // This might involve updating the cart table, session, or whatever your
  // cart implementation uses.  For this example, we simply empty the array.

  //This function is needed for the cart_items to be passed to the `processPurchase` function
  $cart_items = [];
}


// -------------------------------------------------
//  Example Usage (This part is for demonstration and testing.  Don't use this directly in a production environment.)
// -------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assuming the cart items are sent as a JSON array (check for this in the form submission)
  $cart_items_json = $_POST["cart_items"];
  $cart_items = json_decode($cart_items_json, true); // Convert JSON to associative array

  if (json_decode($cart_items_json, true)) {
    $result = processPurchase($cart_items);
    echo "<p>$result</p>";
  } else {
    echo "<p>Invalid cart format.</p>";
  }
}

?>
