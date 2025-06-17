

<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "shop_db";
$db_user = "your_user";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Basic example, enhance for production)
  $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
  $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Convert to integer and default to 1
  $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
  $customer_email = isset($_POST['customer_email']) ? trim($_POST['customer_email']) : '';


  // Sanitize input (Important to prevent security vulnerabilities)
  $product_id = (int)$product_id; // Ensure product_id is an integer
  $quantity = max(1, (int)$quantity); // Ensure quantity is at least 1


  // Check if product exists (Basic example, enhance for production)
  $product_query = "SELECT * FROM products WHERE id = ?";
  $stmt = $conn->prepare($product_query);
  $stmt->bind_param("i", $product_id); // "i" for integer
  $stmt->execute();
  $product = $stmt->fetch_assoc();

  if (!$product) {
    $error = "Product not found.";
  } elseif ($quantity <= 0) {
     $error = "Invalid quantity.";
  } else {

    //  Add to Cart Logic (Placeholder - Implement your cart system)
    //  This is just a demonstration - replace with your actual cart logic
    $cart_items = json_decode($_SESSION['cart_items'], true);

    // Check if the product is already in the cart
    $product_exists = false;
    foreach ($cart_items as &$item) {
      if ($item['product_id'] == $product_id) {
        $item['quantity'] += $quantity;
        $product_exists = true;
        break;
      }
    }

    if (!$product_exists) {
      $cart_items[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'product_name' => $product->product_name, // Or other relevant product details
        'price' => $product->price
      ];
    }

    // Store the cart items in the session
    $_SESSION['cart_items'] = $cart_items;


    //  Confirmation message
    $success = "Item added to cart!";

  }

}
?>
