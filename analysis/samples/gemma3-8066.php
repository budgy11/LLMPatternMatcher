

<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_db";
$db_user = "your_username";
$db_password = "your_password";

// ---  Purchase Functionality  ---

/**
 * Handles a customer's purchase.
 *
 * @param array $cart The shopping cart data (item ID, quantity, etc.).
 * @param string $customer_name The customer's name.
 * @param string $customer_email The customer's email.
 * @return string The response message (success or error).
 */
function processPurchase(array $cart, string $customer_name, string $customer_email) {
  // 1. Validate Cart Data
  if (empty($cart)) {
    return "Error: Empty cart. Please add items to your cart.";
  }

  // 2. Connect to Database
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    return "Error: Database connection failed: " . $e->getMessage();
  }

  // 3.  Get Product Information
  $products = [];
  foreach ($cart as $item => $quantity) {
    // Assuming you have a product table with an 'id' and 'price' column
    try {
      $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id = ?");
      $stmt->execute([$item]);
      $product = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$product) {
        return "Error: Product with ID $item not found.";
      }
      $products[] = [
        'id' => $item,
        'name' => $product['name'], // Assuming you have a 'name' column in the products table
        'price' => $product['price'],
        'quantity' => $quantity,
      ];
    } catch (PDOException $e) {
      return "Error: Database query error: " . $e->getMessage();
    }
  }

  // 4. Calculate Total Amount
  $total = 0;
  foreach ($products as $product) {
    $total += $product['price'] * $product['quantity'];
  }

  // 5. Insert Order into Database
  try {
    $sql = "INSERT INTO orders (customer_name, customer_email, total_amount, order_date) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customer_name, $customer_email, $total]);
    $order_id = $pdo->lastInsertId(); // Get the ID of the newly inserted order

    // 6. Insert Order Items into Order_Items table (associative array for clear association)
    $order_items = [];
    foreach ($products as $product) {
      $order_items[] = [
        'order_id' => $order_id,
        'product_id' => $product['id'],
        'quantity' => $product['quantity'],
        'price' => $product['price'],
      ];
    }

    // 7.  Update Cart (Clear the cart after successful purchase) -  This is crucial!
    //    You'll likely have a cart storage mechanism (e.g., session or database)
    //    This example assumes cart data is stored in the session.  Adapt accordingly.
    session_start();
    $_SESSION['cart'] = []; // Clear the cart
    
    return "Order placed successfully! Order ID: $order_id";

  } catch (PDOException $e) {
    return "Error: Database insertion error: " . $e->getMessage();
  }
}


// --- Example Usage (Replace with actual user input) ---
// Simulate a shopping cart
$cart = [
  1 => 2, // Product ID 1, quantity 2
  2 => 1, // Product ID 2, quantity 1
];

$customer_name = "John Doe";
$customer_email = "john.doe@example.com";

$response = processPurchase($cart, $customer_name, $customer_email);
echo $response;

?>
