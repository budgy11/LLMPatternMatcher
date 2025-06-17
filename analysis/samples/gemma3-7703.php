

<?php

// Configuration (Database details, etc.)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// ------------------ Database Connection ------------------
try {
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// ------------------ Helper Functions ------------------

/**
 * Adds a product to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return bool True on success, false on failure.
 */
function addToCart(int $product_id, int $quantity) {
  global $pdo;

  try {
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
      return false;
    }

    // Check if the product already exists in the cart
    $stmt = $pdo->prepare("SELECT id FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
      // Update the quantity of the existing cart item
      $quantity_to_update = $quantity + $cart_item['quantity'];
      $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
      $stmt->execute([$quantity_to_update, $product_id]);
    } else {
      // Add a new item to the cart
      $stmt = $pdo->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
      $stmt->execute([$product_id, $quantity]);
    }

    return true;
  } catch(PDOException $e) {
    error_log("Error adding to cart: " . $e->getMessage()); // Log the error for debugging
    return false;
  }
}

/**
 * Retrieves the contents of the shopping cart.
 *
 * @return array An array of cart items, each with 'id', 'name', 'price', and 'quantity'.
 */
function getCartContents() {
    global $pdo;

    $stmt = $pdo->prepare("SELECT p.id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateCartTotal() {
  global $pdo;
  $cart_items = getCartContents();
  $total = 0;
  foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

/**
 * Clears the entire shopping cart.
 *
 * @return bool True on success, false on failure.
 */
function clearCart() {
  global $pdo;
  try {
    $stmt = $pdo->prepare("DELETE FROM cart");
    $stmt->execute();
    return true;
  } catch(PDOException $e) {
    error_log("Error clearing cart: " . $e->getMessage());
    return false;
  }
}

// ------------------ Example Usage (Controller - This would be in a separate file) ------------------
// This is just an example - you'd typically use a framework like Laravel or Symfony
// or handle this logic in a separate controller class.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["product_id"]) && isset($_POST["quantity"])) {
    $product_id = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    if (addToCart($product_id, $quantity)) {
      echo "<p>Product added to cart successfully!</p>";
    } else {
      echo "<p>Error adding product to cart.</p>";
    }
  }

  if (isset($_POST["clear_cart"])) {
    if (clearCart()) {
      echo "<p>Cart cleared successfully!</p>";
    } else {
      echo "<p>Error clearing cart.</p>";
    }
  }
}

// ------------------ Display Cart Contents (Example - would be in a separate view file) ------------------

$cart_contents = getCartContents();
$cart_total = calculateCartTotal();

?>
